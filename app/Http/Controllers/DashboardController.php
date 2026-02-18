<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Stock;
use App\Models\InvestmentPlan;
use App\Models\WalletTransaction;
use App\Models\PaymentMethod;
use App\Models\TeslaCar;
use App\Models\Order;
use App\Models\UserInvestment;
use App\Models\StockPurchase;
use App\Models\KycSubmission;
use App\Models\SupportTicket;
use App\Models\Notification;
use App\Mail\SupportTicketCreatedEmail;
use App\Mail\StockPurchaseConfirmationEmail;
use App\Mail\InvestmentConfirmationEmail;
use App\Mail\VehicleOrderConfirmationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        // Wallet-based stats
        $transactions = WalletTransaction::where('user_id', $user?->id)
            ->orderByDesc('occurred_at')
            ->get();

        $totalDeposits = $transactions->where('direction', 'credit')->sum('amount');
        $totalWithdrawals = $transactions->where('type', 'withdrawal')->where('direction', 'debit')->sum('amount');
        $totalInvested = $transactions->where('type', 'investment')->where('direction', 'debit')->sum('amount');
        $totalProfitDebits = $transactions->where('type', 'profit_distribution')->where('direction', 'debit')->sum('amount');

        // Compute a live balance from transactions
        $computedBalance = $totalDeposits - $totalWithdrawals - $totalInvested - $totalProfitDebits;

        // Use stored balance on the user if present, otherwise fall back to computed
        $availableBalance = $user?->available_balance ?? $computedBalance;

        // If migrating fresh, we can initialize the stored balance from the computed value
        if ($user && is_null($user->available_balance)) {
            $user->available_balance = $computedBalance;
            $user->save();
        }

        // Simple derived stats for the dashboard cards
        $portfolioValue = $totalDeposits; // overall money flowed into the platform
        $investmentsValue = $totalInvested;
        $stockHoldingsValue = max(0, $portfolioValue - $investmentsValue - $totalWithdrawals);

        // Tesla vehicles owned == completed orders count
        $teslaVehiclesCount = Order::where('user_id', $user?->id)
            ->where('status', 'Completed')
            ->count();

        $activeInvestmentsCount = UserInvestment::where('user_id', $user?->id)->count();
        $stockPositionsCount = StockPurchase::where('user_id', $user?->id)->count();

        // Recent orders for the "Recent Orders" panel
        $recentOrders = Order::with('car')
            ->where('user_id', $user?->id)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Top stocks for Market Overview (top 5)
        $topStocks = Stock::orderByDesc('change_percent')
            ->take(5)
            ->get();

        // Chart data: last 7 days - balance, investments, profit (live-style)
        $chartLabels = [];
        $balanceData = [];
        $investmentsData = [];
        $profitData = [];
        $baseProfit = max(0, (float) ($portfolioValue - $totalInvested));
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('D M j');
            $endOfDay = $date->copy()->endOfDay();
            $txUntil = WalletTransaction::where('user_id', $user?->id)
                ->where('occurred_at', '<=', $endOfDay)
                ->get();
            $dep = $txUntil->where('direction', 'credit')->sum('amount');
            $wit = $txUntil->where('type', 'withdrawal')->where('direction', 'debit')->sum('amount');
            $inv = $txUntil->where('type', 'investment')->where('direction', 'debit')->sum('amount');
            
            // Calculate real profit from transactions
            $profit = $txUntil->where('type', 'profit_distribution')->where('direction', 'credit')->sum('amount') 
                    - $txUntil->where('type', 'profit_distribution')->where('direction', 'debit')->sum('amount');

            // Balance is deposits (includes profit credits) - withdrawals - investments
            // Note: If profit subtraction (debit) happens, it should ideally be treated as a withdrawal-like reduction or negative deposit?
            // Currently $dep sums all CREDITS.
            // If we have a profit debit, it is NOT in $dep.
            // We need to subtract profit debits from balance.
            
            $profitDebits = $txUntil->where('type', 'profit_distribution')->where('direction', 'debit')->sum('amount');
            $bal = $dep - $wit - $inv - $profitDebits;

            $balanceData[] = round($bal, 2);
            $investmentsData[] = round($inv, 2);
            $profitData[] = round($profit, 2);
        }

        return view('user.dashboard', [
            'user' => $user,
            'dashboardStats' => [
                'available_balance' => $availableBalance,
                'portfolio_value' => $portfolioValue,
                'investments_value' => $investmentsValue,
                'stock_holdings_value' => $stockHoldingsValue,
                'tesla_vehicles_count' => $teslaVehiclesCount,
                'total_deposits' => $totalDeposits,
                'total_invested' => $totalInvested,
                'active_investments_count' => $activeInvestmentsCount,
                'stock_positions_count' => $stockPositionsCount,
            ],
            'recentOrders' => $recentOrders,
            'topStocks' => $topStocks,
            'chartData' => [
                'labels' => $chartLabels,
                'balance' => $balanceData,
                'investments' => $investmentsData,
                'profit' => $profitData,
            ],
        ]);
    }

    /**
     * Show the support page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function support()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $tickets = SupportTicket::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('user.support', [
            'tickets' => $tickets,
        ]);
    }

    /**
     * Handle support form submission.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitSupport(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Please log in to submit a support ticket.']);
        }

        $validated = $request->validate([
            'category' => ['required', 'string', 'in:account,investment,stock,inventory,payment,technical,other'],
            'subject' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
        ]);

        try {
            // Create support ticket
            $ticket = SupportTicket::create([
                'user_id' => $user->id,
                'subject' => $validated['subject'],
                'category' => $validated['category'],
                'email' => $validated['email'],
                'message' => $validated['message'],
                'status' => 'Open',
            ]);

            // Create notification for user
            try {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'support_ticket_created',
                    'title' => 'Support Ticket Created',
                    'message' => "Your support ticket #{$ticket->ticket_number} has been created. We'll respond within 24 hours.",
                    'link' => route('dashboard.support'),
                ]);
            } catch (\Exception $e) {
                \Log::error('Support notification creation failed: ' . $e->getMessage());
            }

            // Send email notification
            try {
                Mail::to($user->email)->send(new SupportTicketCreatedEmail($user, $ticket));
            } catch (\Exception $e) {
                \Log::error('Support ticket email failed: ' . $e->getMessage());
            }

            return redirect()->route('dashboard.support')
                ->with('success', "Your support ticket #{$ticket->ticket_number} has been created. We'll get back to you within 24 hours.");
        } catch (\Exception $e) {
            \Log::error('Support ticket creation failed: ' . $e->getMessage());
            \Log::error('Support ticket creation error trace: ' . $e->getTraceAsString());

            return back()
                ->withErrors(['error' => 'An error occurred while creating your support ticket. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Show the wallet page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function wallet()
    {
        $user = Auth::user();

        $transactions = WalletTransaction::where('user_id', $user->id ?? null)
            ->orderByDesc('occurred_at')
            ->get();

        $totalDeposits = $transactions->where('type', 'deposit')->sum('amount');
        $totalWithdrawals = $transactions->where('type', 'withdrawal')->sum('amount');
        $totalInvested = $transactions->where('type', 'investment')->sum('amount');

        // Use the user's stored available balance
        $currentBalance = $user?->available_balance ?? 0.0;

        return view('user.wallet', [
            'walletSummary' => [
                'current_balance' => $currentBalance,
                'total_deposits' => $totalDeposits,
                'total_withdrawals' => $totalWithdrawals,
                'total_invested' => $totalInvested,
            ],
            'walletTransactions' => $transactions,
        ]);
    }

    /**
     * Show deposit page.
     */
    public function walletDeposit()
    {
        $user = Auth::user();
        $currentBalance = $user?->available_balance ?? 0.0;

        $methods = PaymentMethod::where('is_active', true)
            ->whereIn('type', ['deposit', 'both'])
            ->orderBy('display_order')
            ->get();

        return view('user.wallet-deposit', [
            'currentBalance' => $currentBalance,
            'depositMethods' => $methods,
        ]);
    }

    /**
     * Show withdrawal page.
     */
    public function walletWithdraw()
    {
        $user = Auth::user();
        $currentBalance = $user?->available_balance ?? 0.0;

        $methods = PaymentMethod::where('is_active', true)
            ->whereIn('type', ['withdrawal', 'both'])
            ->orderBy('display_order')
            ->get();

        return view('user.wallet-withdraw', [
            'currentBalance' => $currentBalance,
            'withdrawMethods' => $methods,
        ]);
    }

    /**
     * Handle deposit submission.
     */
    public function walletDepositSubmit(Request $request)
    {
        $user = Auth::user();

        try {
            $validated = $request->validate([
                'amount' => ['required', 'numeric', 'min:1'],
                'payment_method_id' => ['required', 'exists:payment_methods,id'],
            ]);

            $method = PaymentMethod::where('is_active', true)
                ->whereIn('type', ['deposit', 'both'])
                ->findOrFail($validated['payment_method_id']);

            $amount = (float) $validated['amount'];

            // Create wallet transaction record with Pending status
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'asset' => $method->name,
                'title' => 'Deposit via ' . $method->name,
                'amount' => $amount,
                'direction' => 'credit',
                'status' => 'Pending',
                'occurred_at' => now(),
            ]);

            // Don't update balance until admin confirms

            return redirect()
                ->route('dashboard.wallet.deposit')
                ->with('success', 'Deposit request of $' . number_format($amount, 2) . ' via ' . $method->name . ' has been submitted. Please wait for confirmation from administration.');
        } catch (\Exception $e) {
            \Log::error('Wallet deposit submission failed: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred while creating your deposit. Please try again.');
        }
    }

    /**
     * Handle withdrawal submission.
     */
    public function walletWithdrawSubmit(Request $request)
    {
        $user = Auth::user();

        try {
            $method = PaymentMethod::where('is_active', true)
                ->whereIn('type', ['withdrawal', 'both'])
                ->findOrFail($request->payment_method_id);

            // Dynamic validation based on payment method category
            $rules = [
                'amount' => ['required', 'numeric', 'min:1'],
                'payment_method_id' => ['required', 'exists:payment_methods,id'],
            ];

            // Add validation rules based on payment method category
            if ($method->category === 'crypto') {
                $rules['wallet_address'] = ['required', 'string', 'min:10'];
            } elseif ($method->category === 'bank') {
                $rules['bank_name'] = ['required', 'string'];
                $rules['account_name'] = ['required', 'string'];
                $rules['account_number'] = ['required', 'string'];
                $rules['iban'] = ['nullable', 'string'];
                $rules['swift_bic'] = ['nullable', 'string'];
            } elseif ($method->category === 'wallet') {
                $rules['wallet_email'] = ['required', 'email'];
            }

            $validated = $request->validate($rules);

            $currentBalance = $user?->available_balance ?? 0.0;
            $amount = (float) $validated['amount'];

            if ($amount > $currentBalance) {
                return back()
                    ->withErrors(['amount' => 'Withdrawal amount cannot exceed your available balance.'])
                    ->withInput();
            }

            // Build withdrawal details based on category
            $withdrawalDetails = [];
            if ($method->category === 'crypto') {
                $withdrawalDetails = [
                    'wallet_address' => $validated['wallet_address'],
                ];
            } elseif ($method->category === 'bank') {
                $withdrawalDetails = [
                    'bank_name' => $validated['bank_name'],
                    'account_name' => $validated['account_name'],
                    'account_number' => $validated['account_number'],
                    'iban' => $validated['iban'] ?? null,
                    'swift_bic' => $validated['swift_bic'] ?? null,
                ];
            } elseif ($method->category === 'wallet') {
                $withdrawalDetails = [
                    'wallet_email' => $validated['wallet_email'],
                ];
            }

            // Create wallet transaction record with Pending status
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'withdrawal',
                'asset' => $method->name,
                'title' => 'Withdrawal to ' . $method->name,
                'withdrawal_details' => json_encode($withdrawalDetails),
                'amount' => $amount,
                'direction' => 'debit',
                'status' => 'Pending',
                'occurred_at' => now(),
            ]);

            // Don't update balance until admin confirms

            return redirect()
                ->route('dashboard.wallet.withdraw')
                ->with('success', 'Withdrawal request of $' . number_format($amount, 2) . ' to ' . $method->name . ' has been submitted. Please wait for confirmation from administration.');
        } catch (\Exception $e) {
            \Log::error('Wallet withdrawal submission failed: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred while creating your withdrawal. Please try again.');
        }
    }

    /**
     * Show the stocks page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function stocks()
    {
        $stocks = Stock::orderBy('ticker')->get();

        // Pre-format stocks for the frontend to avoid complex Blade expressions
        $stocksForJs = $stocks->map(function (Stock $stock) {
            return [
                'id' => $stock->id,
                'ticker' => $stock->ticker,
                'name' => $stock->name,
                'price' => (float) $stock->price,
                'change' => (float) $stock->change,
                'changePercent' => (float) $stock->change_percent,
                'volume' => $stock->volume,
                'marketCap' => $stock->market_cap,
                'domain' => $stock->domain,
                'description' => $stock->description,
            ];
        });

        $user = Auth::user();
        $currentBalance = $user?->available_balance ?? 0.0;

        return view('user.stocks', [
            'stocks' => $stocks,
            'stocksForJs' => $stocksForJs,
            'currentBalance' => $currentBalance,
        ]);
    }

    /**
     * Handle stock purchase submission.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function stockPurchaseSubmit(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'stock_id' => ['required', 'exists:stocks,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'order_type' => ['required', 'in:market,limit'],
            'limit_price' => ['nullable', 'numeric', 'min:0.01', 'required_if:order_type,limit'],
        ]);

        $stock = Stock::findOrFail($validated['stock_id']);
        $quantity = (int) $validated['quantity'];
        $pricePerShare = (float) $stock->price;
        $totalAmount = $quantity * $pricePerShare;
        $currentBalance = $user?->available_balance ?? 0.0;

        // Check if user has sufficient balance
        if ($totalAmount > $currentBalance) {
            return back()
                ->withErrors(['quantity' => 'Insufficient balance. Your current balance is $' . number_format($currentBalance, 2) . '. Total cost: $' . number_format($totalAmount, 2) . '.'])
                ->withInput();
        }

        // For limit orders, validate limit price
        if ($validated['order_type'] === 'limit') {
            $limitPrice = (float) $validated['limit_price'];
            if ($limitPrice < $pricePerShare) {
                return back()
                    ->withErrors(['limit_price' => 'Limit price must be greater than or equal to the current market price ($' . number_format($pricePerShare, 2) . ').'])
                    ->withInput();
            }
        }

        // Start database transaction
        DB::beginTransaction();
        try {
            // Reduce user balance
            $user->available_balance = $currentBalance - $totalAmount;
            $user->save();

            // Create stock purchase record
            $stockPurchase = StockPurchase::create([
                'user_id' => $user->id,
                'stock_id' => $stock->id,
                'quantity' => $quantity,
                'price_per_share' => $pricePerShare,
                'total_amount' => $totalAmount,
                'order_type' => $validated['order_type'],
                'limit_price' => $validated['order_type'] === 'limit' ? (float) $validated['limit_price'] : null,
                'status' => $validated['order_type'] === 'market' ? 'Completed' : 'Pending',
            ]);

            // Create wallet transaction record
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'stock_purchase',
                'asset' => $stock->ticker,
                'title' => 'Stock Purchase: ' . $quantity . ' shares of ' . $stock->name . ' (' . $stock->ticker . ')',
                'amount' => $totalAmount,
                'direction' => 'debit',
                'status' => $validated['order_type'] === 'market' ? 'Completed' : 'Pending',
                'occurred_at' => now(),
            ]);

            DB::commit();

            // Send email notification
            try {
                $orderTypeText = $validated['order_type'] === 'market' ? 'Market Order' : 'Limit Order';
                $statusText = $validated['order_type'] === 'market' ? 'Completed' : 'Pending';
                
                Mail::raw(
                    "Hello {$user->name},\n\n" .
                    "Your stock purchase has been successfully processed.\n\n" .
                    "Purchase Details:\n" .
                    "- Stock: {$stock->name} ({$stock->ticker})\n" .
                    "- Quantity: {$quantity} shares\n" .
                    "- Price per Share: $" . number_format($pricePerShare, 2) . "\n" .
                    "- Total Amount: $" . number_format($totalAmount, 2) . "\n" .
                    "- Order Type: {$orderTypeText}\n" .
                    ($validated['order_type'] === 'limit' ? "- Limit Price: $" . number_format($validated['limit_price'], 2) . "\n" : "") .
                    "- Status: {$statusText}\n\n" .
                    "Your new balance: $" . number_format($user->available_balance, 2) . "\n\n" .
                    "Thank you for trading with us!\n\n" .
                    "Best regards,\nTESLA Trading Team",
                    function ($message) use ($user) {
                        $message->to($user->email)
                            ->subject('Stock Purchase Confirmation - TESLA');
                    }
                );
            } catch (\Exception $e) {
                // Log email error but don't fail the transaction
                \Log::error('Failed to send stock purchase email: ' . $e->getMessage());
            }

            $successMessage = $validated['order_type'] === 'market' 
                ? 'Stock purchase successful! Your purchase has been completed and a confirmation email has been sent.'
                : 'Limit order placed! Your order will be executed when the stock reaches your limit price. A confirmation email has been sent.';

            return redirect()->route('dashboard.stocks')
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Stock purchase submission failed: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'An error occurred while processing your stock purchase. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Show the investments page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function investments()
    {
        $plans = InvestmentPlan::orderBy('display_order')->get();

        $plansForJs = $plans->map(function (InvestmentPlan $plan) {
            return [
                'id' => $plan->id,
                'name' => $plan->name,
                'slug' => $plan->slug,
                'category' => $plan->category,
                'strategy' => $plan->strategy,
                'riskLevel' => $plan->risk_level,
                'nav' => (float) ($plan->nav ?? 0),
                'oneYearReturn' => (float) ($plan->profit_margin ?? $plan->one_year_return ?? 0),
                'minInvestment' => (float) $plan->min_investment,
                'maxInvestment' => $plan->max_investment === null ? null : (float) $plan->max_investment,
                'profitMargin' => (float) ($plan->profit_margin ?? 0),
                'durationDays' => (int) ($plan->duration_days ?? 0),
                'durationLabel' => $plan->duration_label ?? '',
                'isFeatured' => (bool) $plan->is_featured,
            ];
        });

        $user = Auth::user();
        $currentBalance = $user?->available_balance ?? 0.0;

        return view('user.investments', [
            'investmentPlans' => $plans,
            'investmentPlansForJs' => $plansForJs,
            'currentBalance' => $currentBalance,
        ]);
    }

    /**
     * Handle investment submission.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function investSubmit(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'investment_plan_id' => ['required', 'exists:investment_plans,id'],
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $plan = InvestmentPlan::findOrFail($validated['investment_plan_id']);
        $amount = (float) $validated['amount'];
        $currentBalance = $user?->available_balance ?? 0.0;

        // Check if amount meets minimum investment requirement
        if ($amount < $plan->min_investment) {
            return back()
                ->withErrors(['amount' => "Minimum investment for this plan is $" . number_format((float) $plan->min_investment, 2) . "."])
                ->withInput();
        }

        // Check if amount exceeds maximum investment (when plan has a max)
        if ($plan->max_investment !== null && $amount > $plan->max_investment) {
            return back()
                ->withErrors(['amount' => "Maximum investment for this plan is $" . number_format((float) $plan->max_investment, 2) . "."])
                ->withInput();
        }

        // Check if user has sufficient balance
        if ($amount > $currentBalance) {
            return back()
                ->withErrors(['amount' => 'Insufficient balance. Your current balance is $' . number_format($currentBalance, 2) . '.'])
                ->withInput();
        }

        // Start database transaction
        DB::beginTransaction();
        try {
            // Reduce user balance
            $user->available_balance = $currentBalance - $amount;
            $user->save();

            // Create user investment record
            $userInvestment = UserInvestment::create([
                'user_id' => $user->id,
                'investment_plan_id' => $plan->id,
                'amount' => $amount,
                'status' => 'Active',
            ]);

            // Create wallet transaction record
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'investment',
                'asset' => $plan->name,
                'title' => 'Investment in ' . $plan->name,
                'amount' => $amount,
                'direction' => 'debit',
                'status' => 'Completed',
                'occurred_at' => now(),
            ]);

            \DB::commit();

            // Send email notification
            try {
                Mail::to($user->email)->send(new InvestmentConfirmationEmail(
                    $user,
                    $plan,
                    $amount,
                    $user->available_balance
                ));
            } catch (\Exception $e) {
                // Log email error but don't fail the transaction
                \Log::error('Failed to send investment email: ' . $e->getMessage());
            }

            return redirect()->route('dashboard.investments')
                ->with('success', 'Investment successful! Your investment has been processed and a confirmation email has been sent.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Investment submission failed: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'An error occurred while processing your investment. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Show the inventory page (Tesla cars).
     */
    public function inventory()
    {
        $cars = TeslaCar::where('is_available', true)
            ->orderBy('display_order')
            ->get();

        // Prepare cars data for JavaScript
        $carsForJs = $cars->map(function($car) {
            return [
                'id' => $car->id,
                'name' => $car->name,
                'model' => $car->model ?? '',
                'year' => $car->year ?? '',
                'variant' => $car->variant ?? '',
                'price' => (float) $car->price,
                'range_miles' => $car->range_miles ?? null,
                'top_speed_mph' => $car->top_speed_mph ?? null,
                'zero_to_sixty' => $car->zero_to_sixty ? (float) $car->zero_to_sixty : null,
                'drivetrain' => $car->drivetrain ?? 'Electric',
                'image_url' => $car->getPrimaryImage() ?? null,
                'images' => $car->images ?? [],
            ];
        })->values();

        return view('user.inventory', [
            'cars' => $cars,
            'carsForJs' => $carsForJs,
        ]);
    }

    /**
     * Show the orders page.
     */
    public function orders()
    {
        $user = Auth::user();

        $orders = Order::with('car')
            ->where('user_id', $user?->id)
            ->orderByDesc('created_at')
            ->get();

        return view('user.orders', [
            'orders' => $orders,
        ]);
    }

    /**
     * Place a new car order from inventory page.
     */
    public function placeOrder(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'car_id' => ['required', 'exists:tesla_cars,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $car = TeslaCar::findOrFail($validated['car_id']);
        $qty = $validated['quantity'] ?? 1;
        $total = $car->price * $qty;

        // Check if user has sufficient balance
        $currentBalance = $user->available_balance ?? 0.0;

        if ($total > $currentBalance) {
            return redirect()->route('dashboard.inventory')
                ->withErrors(['error' => 'Insufficient balance. You need $' . number_format($total, 2) . ' but only have $' . number_format($currentBalance, 2) . ' available.'])
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Deduct from user balance
            $user->available_balance = $currentBalance - $total;
            $user->save();

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'tesla_car_id' => $car->id,
                'quantity' => $qty,
                'total_price' => $total,
                'status' => 'Completed',
                'order_number' => 'ORD-' . now()->format('Ymd') . '-' . strtoupper(uniqid()),
            ]);

            // Create wallet transaction record
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'vehicle_purchase',
                'title' => "Vehicle purchase: {$car->name}",
                'direction' => 'debit',
                'amount' => $total,
                'status' => 'Completed',
                'occurred_at' => now(),
            ]);

            DB::commit();

            // Send email notification
            try {
                Mail::to($user->email)->send(new VehicleOrderConfirmationEmail(
                    $user,
                    $order,
                    $car,
                    $qty,
                    $total,
                    $user->available_balance
                ));
            } catch (\Exception $e) {
                // Log email error but don't fail the transaction
                \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
            }

            return redirect()->route('dashboard.orders')
                ->with('success', 'Your order for ' . $car->name . ' has been placed. A confirmation email has been sent.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order placement failed: ' . $e->getMessage());

            return redirect()->route('dashboard.inventory')
                ->withErrors(['error' => 'An error occurred while processing your order. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Proxy stock logo to avoid CORS issues
     *
     * @param string $ticker
     * @return \Illuminate\Http\Response
     */
    public function stockLogo($ticker)
    {
        $ticker = strtoupper($ticker);
        
        // Get domain from database
        $stock = Stock::where('ticker', $ticker)->first();
        $domain = $stock ? $stock->domain : null;
        
        // Try multiple logo sources in order of reliability
        $sources = [];
        
        if ($domain) {
            $sources[] = "https://logo.clearbit.com/{$domain}?size=128";
        }
        
        $sources[] = "https://storage.googleapis.com/iex/api/logos/{$ticker}.png";
        $sources[] = "https://assets2.polygon.io/logos/" . strtolower($ticker) . "/logo.png";
        
        foreach ($sources as $url) {
            try {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
                curl_setopt($ch, CURLOPT_REFERER, 'https://www.google.com/');
                $image = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
                curl_close($ch);
                
                if ($httpCode === 200 && $image && strlen($image) > 100) {
                    $mimeType = $contentType ?: 'image/png';
                    return response($image, 200)
                        ->header('Content-Type', $mimeType)
                        ->header('Cache-Control', 'public, max-age=86400')
                        ->header('Access-Control-Allow-Origin', '*');
                }
            } catch (\Exception $e) {
                // Continue to next source
                continue;
            }
        }
        
        // Return 404 if no logo found
        return response('', 404);
    }

    /**
     * Show the investment dashboard page with investment history.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function investmentDashboard()
    {
        $user = Auth::user();

        // Get user investments with plan details
        $investments = UserInvestment::with('investmentPlan')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        // Calculate statistics and earnings
        $totalInvested = $investments->sum('amount');
        $activeInvestments = $investments->where('status', 'Active')->sum('amount');
        $completedInvestments = $investments->where('status', 'Completed')->sum('amount');
        
        // Calculate total earnings (for active investments, calculate based on plan return)
        $totalEarnings = 0;
        $investmentsWithEarnings = $investments->map(function ($investment) use (&$totalEarnings) {
            if ($investment->status === 'Active' && $investment->investmentPlan) {
                // Calculate earnings based on one_year_return percentage
                $returnRate = $investment->investmentPlan->one_year_return / 100;
                // Calculate earnings based on time invested (simplified: assume 1 year for now)
                $earnings = $investment->amount * $returnRate;
                $investment->calculated_earnings = $earnings;
                $totalEarnings += $earnings;
            } else {
                $investment->calculated_earnings = 0;
            }
            return $investment;
        });

        return view('user.investment-dashboard', [
            'investments' => $investmentsWithEarnings,
            'totalInvested' => $totalInvested,
            'activeInvestments' => $activeInvestments,
            'completedInvestments' => $completedInvestments,
            'totalEarnings' => $totalEarnings,
        ]);
    }

    /**
     * Show the account settings page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function account()
    {
        $user = Auth::user();
        return view('user.account', [
            'user' => $user,
        ]);
    }

    /**
     * Update user account information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'current_password' => ['nullable', 'required_with:password'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Update name and email
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Update password if provided
        if (!empty($validated['password'])) {
            // Verify current password
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'The current password is incorrect.'])
                    ->withInput();
            }

            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('dashboard.account')
            ->with('success', 'Your account information has been updated successfully.');
    }

    /**
     * Show the KYC verification page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function kyc()
    {
        $user = Auth::user();
        $latestSubmission = $user->latestKycSubmission;

        return view('user.kyc', [
            'user' => $user,
            'latestSubmission' => $latestSubmission,
        ]);
    }

    /**
     * Handle KYC document submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitKyc(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'id_document' => ['required', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'], // 5MB max
            'proof_of_address' => ['required', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
            'selfie' => ['required', 'file', 'mimes:jpeg,jpg,png', 'max:5120'],
        ]);

        try {
            // Store files in storage/app/public/kyc/{user_id}/
            $kycPath = 'kyc/' . $user->id;
            
            $idDocumentPath = $request->file('id_document')->store($kycPath, 'public');
            $proofOfAddressPath = $request->file('proof_of_address')->store($kycPath, 'public');
            $selfiePath = $request->file('selfie')->store($kycPath, 'public');

            // Create KYC submission record
            KycSubmission::create([
                'user_id' => $user->id,
                'id_document_path' => $idDocumentPath,
                'proof_of_address_path' => $proofOfAddressPath,
                'selfie_path' => $selfiePath,
                'status' => 'Pending',
            ]);

            return redirect()->route('dashboard.kyc')
                ->with('success', 'Your KYC documents have been submitted successfully. Our team will review them and you will be notified once the verification is complete.');
        } catch (\Exception $e) {
            \Log::error('KYC submission failed: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'An error occurred while uploading your documents. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Get notifications for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications()
    {
        $user = Auth::user();
        $notifications = $user->unreadNotifications();
        $unreadCount = $user->unreadNotificationsCount();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark notification as read.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markNotificationAsRead(Request $request)
    {
        $user = Auth::user();
        $notificationId = $request->input('notification_id');

        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllNotificationsAsRead()
    {
        $user = Auth::user();
        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
