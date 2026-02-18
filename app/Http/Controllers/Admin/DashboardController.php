<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\UserInvestment;
use App\Models\StockPurchase;
use App\Models\WalletTransaction;
use App\Models\KycSubmission;
use App\Models\SupportTicket;
use App\Models\TeslaCar;
use App\Models\InvestmentPlan;
use App\Models\Stock;
use App\Models\PaymentMethod;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomMail;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        // Overall statistics
        $stats = [
            'total_users' => User::count(),
            'total_orders' => Order::count(),
            'total_investments' => UserInvestment::count(),
            'total_stock_purchases' => StockPurchase::count(),
            'total_wallet_transactions' => WalletTransaction::count(),
            'pending_kyc' => KycSubmission::where('status', 'Pending')->count(),
            'pending_support_tickets' => SupportTicket::where('status', 'open')->count(),
            'total_revenue' => WalletTransaction::where('type', 'deposit')
                ->where('status', 'Completed')
                ->sum('amount'),
            'pending_deposits' => WalletTransaction::where('type', 'deposit')
                ->where('status', 'Pending')
                ->count(),
            'pending_withdrawals' => WalletTransaction::where('type', 'withdrawal')
                ->where('status', 'Pending')
                ->count(),
        ];

        // Recent activity
        $recentUsers = User::latest()->take(5)->get();
        $recentOrders = Order::with(['user', 'car'])->latest()->take(5)->get();
        $recentTransactions = WalletTransaction::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentOrders', 'recentTransactions'));
    }

    /**
     * Manage users
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users', compact('users'));
    }

    /**
     * Update user balance
     */
    public function updateUserBalance(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'action' => 'required|in:add,subtract,set',
        ]);

        $currentBalance = $user->available_balance ?? 0;
        $amount = (float) $request->amount;

        switch ($request->action) {
            case 'add':
                $newBalance = $currentBalance + $amount;
                break;
            case 'subtract':
                $newBalance = max(0, $currentBalance - $amount);
                break;
            case 'set':
                $newBalance = $amount;
                break;
        }

        $user->available_balance = $newBalance;
        $user->save();

        // Create transaction record
        WalletTransaction::create([
            'user_id' => $user->id,
            'type' => 'admin_adjustment',
            'asset' => 'USD',
            'title' => 'Admin balance adjustment',
            'amount' => abs($newBalance - $currentBalance),
            'direction' => $newBalance > $currentBalance ? 'credit' : 'debit',
            'status' => 'Completed',
            'occurred_at' => now(),
        ]);

        return back()->with('success', 'User balance updated successfully.');
    }

    /**
     * Update user profit
     */
    public function updateUserProfit(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'action' => 'required|in:add,subtract,set',
        ]);

        $currentProfit = $user->total_profit ?? 0;
        $currentBalance = $user->available_balance ?? 0;
        $amount = (float) $request->amount;
        $adjustmentAmount = 0;
        $direction = '';

        switch ($request->action) {
            case 'add':
                $newProfit = $currentProfit + $amount;
                $adjustmentAmount = $amount;
                $direction = 'credit';
                break;
            case 'subtract':
                $newProfit = max(0, $currentProfit - $amount);
                $adjustmentAmount = $currentProfit - $newProfit; // Calculate actual reduction
                $direction = 'debit';
                break;
            case 'set':
                $newProfit = $amount;
                if ($newProfit > $currentProfit) {
                    $adjustmentAmount = $newProfit - $currentProfit;
                    $direction = 'credit';
                } else {
                    $adjustmentAmount = $currentProfit - $newProfit;
                    $direction = 'debit';
                }
                break;
        }

        // Update profit
        $user->total_profit = $newProfit;
        
        // Update balance based on direction
        // Only update balance if there is an actual change
        if ($adjustmentAmount > 0) {
            if ($direction === 'credit') {
                $user->available_balance = $currentBalance + $adjustmentAmount;
            } elseif ($direction === 'debit') {
                $user->available_balance = max(0, $currentBalance - $adjustmentAmount);
            }
            
            // Create transaction record for the balance adjustment
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'profit_distribution',
                'asset' => 'USD',
                'title' => 'Profit Adjustment',
                'amount' => $adjustmentAmount,
                'direction' => $direction,
                'status' => 'Completed',
                'occurred_at' => now(),
            ]);
        }
        
        $user->save();

        return back()->with('success', 'User profit and balance updated successfully.');
    }

    /**
     * Show user details
     */
    public function showUser(User $user)
    {
        // Load KYC submissions (we only need latest, but load all to avoid window function issues)
        $user->load('kycSubmissions');

        // Get transactions
        $transactions = WalletTransaction::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $orders = Order::where('user_id', $user->id)
            ->with('car')
            ->latest()
            ->take(10)
            ->get();

        $investments = UserInvestment::where('user_id', $user->id)
            ->with('investmentPlan')
            ->latest()
            ->take(10)
            ->get();

        $stockPurchases = StockPurchase::where('user_id', $user->id)
            ->with('stock')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.user-show', compact('user', 'transactions', 'orders', 'investments', 'stockPurchases'));
    }

    /**
     * Show create user form
     */
    public function createUser()
    {
        return view('admin.user-form');
    }

    /**
     * Store new user
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'available_balance' => 'nullable|numeric|min:0',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['available_balance'] = $validated['available_balance'] ?? 0;

        $user = User::create($validated);

        return redirect()->route('admin.users.show', $user)->with('success', 'User created successfully.');
    }

    /**
     * Show edit user form
     */
    public function editUser(User $user)
    {
        return view('admin.user-form', compact('user'));
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'available_balance' => 'nullable|numeric|min:0',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)->with('success', 'User updated successfully.');
    }

    /**
     * Delete user
     */
    public function deleteUser(User $user)
    {
        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users')->with('success', "User {$userName} deleted successfully.");
    }

    /**
     * View user transactions
     */
    public function userTransactions(User $user, Request $request)
    {
        $query = WalletTransaction::where('user_id', $user->id);

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->latest()->paginate(20);

        return view('admin.user-transactions', compact('user', 'transactions'));
    }

    /**
     * Send email to user
     */
    public function sendEmailToUser(Request $request, User $user)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            Mail::to($user->email)->send(new CustomMail($request->subject, $request->message));
            return back()->with('success', 'Email sent successfully to ' . $user->email);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Impersonate user (login as user)
     */
    public function impersonateUser(User $user)
    {
        // Store admin ID in session for later return
        session(['admin_id' => Auth::guard('admin')->id()]);
        
        // Logout admin
        Auth::guard('admin')->logout();
        
        // Login as user
        Auth::guard('web')->login($user);

        return redirect()->route('dashboard.index')->with('success', 'You are now logged in as ' . $user->name);
    }

    /**
     * Stop impersonating and return to admin
     */
    public function stopImpersonating()
    {
        $adminId = session('admin_id');
        
        if (!$adminId) {
            return redirect()->route('admin.login')->with('error', 'No admin session found.');
        }

        // Logout user
        Auth::guard('web')->logout();
        
        // Remove admin_id from session
        session()->forget('admin_id');
        
        // Login as admin
        $admin = \App\Models\Admin::find($adminId);
        if ($admin) {
            Auth::guard('admin')->login($admin);
            return redirect()->route('admin.dashboard')->with('success', 'Returned to admin panel.');
        }

        return redirect()->route('admin.login')->with('error', 'Admin not found.');
    }

    /**
     * Manage orders
     */
    public function orders(Request $request)
    {
        $query = Order::with(['user', 'car']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.orders', compact('orders'));
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Pending,Processing,Completed,Cancelled',
        ]);

        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'Order status updated successfully.');
    }

    /**
     * Manage wallet transactions
     */
    public function transactions(Request $request)
    {
        $query = WalletTransaction::with('user');

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $transactions = $query->latest()->paginate(20);

        return view('admin.transactions', compact('transactions'));
    }

    /**
     * Update transaction status
     */
    public function updateTransactionStatus(Request $request, WalletTransaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:Pending,Completed,Rejected',
        ]);

        $oldStatus = $transaction->status;
        $transaction->status = $request->status;

        // If approving a deposit, add to user balance
        if ($oldStatus === 'Pending' && $request->status === 'Completed' && $transaction->type === 'deposit' && $transaction->direction === 'credit') {
            $user = $transaction->user;
            $user->available_balance = ($user->available_balance ?? 0) + $transaction->amount;
            $user->save();
        }

        // If approving a withdrawal, subtract from user balance
        if ($oldStatus === 'Pending' && $request->status === 'Completed' && $transaction->type === 'withdrawal' && $transaction->direction === 'debit') {
            $user = $transaction->user;
            $user->available_balance = max(0, ($user->available_balance ?? 0) - $transaction->amount);
            $user->save();
        }

        // If rejecting, don't change balance (it was never changed)
        $transaction->save();

        return back()->with('success', 'Transaction status updated successfully.');
    }

    /**
     * Show create transaction form
     */
    public function createTransaction()
    {
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        return view('admin.transaction-form', compact('users'));
    }

    /**
     * Store new transaction
     */
    public function storeTransaction(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:deposit,withdrawal,investment,admin_adjustment,other',
            'asset' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'direction' => 'required|in:credit,debit',
            'status' => 'required|in:Pending,Completed,Rejected',
            'withdrawal_details' => 'nullable|string|max:2000',
        ]);

        $validated['asset'] = $validated['asset'] ?? 'USD';
        $validated['occurred_at'] = now();

        $transaction = WalletTransaction::create($validated);

        // If created as Completed, update user balance accordingly
        if ($validated['status'] === 'Completed') {
            $user = User::find($validated['user_id']);
            if ($validated['direction'] === 'credit') {
                $user->available_balance = ($user->available_balance ?? 0) + (float) $validated['amount'];
            } else {
                $user->available_balance = max(0, ($user->available_balance ?? 0) - (float) $validated['amount']);
            }
            $user->save();
        }

        return redirect()->route('admin.transactions')->with('success', 'Transaction created successfully.');
    }

    /**
     * Show edit transaction form
     */
    public function editTransaction(WalletTransaction $transaction)
    {
        $transaction->load('user');
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        return view('admin.transaction-form', compact('transaction', 'users'));
    }

    /**
     * Update transaction
     */
    public function updateTransaction(Request $request, WalletTransaction $transaction)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:deposit,withdrawal,investment,admin_adjustment,other',
            'asset' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'direction' => 'required|in:credit,debit',
            'status' => 'required|in:Pending,Completed,Rejected',
            'withdrawal_details' => 'nullable|string|max:2000',
        ]);

        $validated['asset'] = $validated['asset'] ?? 'USD';

        $oldStatus = $transaction->status;
        $oldAmount = (float) $transaction->amount;
        $oldDirection = $transaction->direction;
        $oldUserId = $transaction->user_id;

        $transaction->update($validated);

        $newStatus = $validated['status'];
        $newAmount = (float) $validated['amount'];
        $newDirection = $validated['direction'];
        $newUserId = (int) $validated['user_id'];

        // Revert old balance effects if status was Completed
        if ($oldStatus === 'Completed' && $oldUserId) {
            $oldUser = User::find($oldUserId);
            if ($oldUser) {
                if ($oldDirection === 'credit') {
                    $oldUser->available_balance = max(0, ($oldUser->available_balance ?? 0) - $oldAmount);
                } else {
                    $oldUser->available_balance = ($oldUser->available_balance ?? 0) + $oldAmount;
                }
                $oldUser->save();
            }
        }

        // Apply new balance effects if status is Completed
        if ($newStatus === 'Completed' && $newUserId) {
            $newUser = User::find($newUserId);
            if ($newUser) {
                if ($newDirection === 'credit') {
                    $newUser->available_balance = ($newUser->available_balance ?? 0) + $newAmount;
                } else {
                    $newUser->available_balance = max(0, ($newUser->available_balance ?? 0) - $newAmount);
                }
                $newUser->save();
            }
        }

        return redirect()->route('admin.transactions')->with('success', 'Transaction updated successfully.');
    }

    /**
     * Delete transaction
     */
    public function deleteTransaction(WalletTransaction $transaction)
    {
        $user = $transaction->user;
        $status = $transaction->status;
        $amount = (float) $transaction->amount;
        $direction = $transaction->direction;

        $transaction->delete();

        // Revert balance if it was Completed
        if ($status === 'Completed' && $user) {
            if ($direction === 'credit') {
                $user->available_balance = max(0, ($user->available_balance ?? 0) - $amount);
            } else {
                $user->available_balance = ($user->available_balance ?? 0) + $amount;
            }
            $user->save();
        }

        return redirect()->route('admin.transactions')->with('success', 'Transaction deleted successfully.');
    }

    /**
     * Approve transaction (quick action)
     */
    public function approveTransaction(WalletTransaction $transaction)
    {
        if ($transaction->status !== 'Pending') {
            return back()->with('error', 'Only pending transactions can be approved.');
        }

        $transaction->status = 'Completed';
        $transaction->save();

        $user = $transaction->user;
        if ($transaction->direction === 'credit') {
            $user->available_balance = ($user->available_balance ?? 0) + (float) $transaction->amount;
        } else {
            $user->available_balance = max(0, ($user->available_balance ?? 0) - (float) $transaction->amount);
        }
        $user->save();

        return back()->with('success', 'Transaction approved successfully.');
    }

    /**
     * Reject transaction (quick action)
     */
    public function rejectTransaction(WalletTransaction $transaction)
    {
        if ($transaction->status !== 'Pending') {
            return back()->with('error', 'Only pending transactions can be rejected.');
        }

        $transaction->status = 'Rejected';
        $transaction->save();

        return back()->with('success', 'Transaction rejected successfully.');
    }

    /**
     * Manage KYC submissions
     */
    public function kycSubmissions(Request $request)
    {
        $query = KycSubmission::with('user');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $submissions = $query->latest()->paginate(20);

        return view('admin.kyc', compact('submissions'));
    }

    /**
     * Update KYC status
     */
    public function updateKycStatus(Request $request, KycSubmission $kyc)
    {
        $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected',
            'notes' => 'nullable|string|max:1000',
        ]);

        $kyc->status = $request->status;
        if ($request->has('notes') && !empty($request->notes)) {
            $kyc->rejection_reason = $request->notes;
        }
        if ($request->status !== 'Pending') {
            $kyc->reviewed_at = now();
        }
        $kyc->save();

        // Create notification
        Notification::create([
            'user_id' => $kyc->user_id,
            'type' => 'kyc_status_update',
            'title' => 'KYC Status Update',
            'message' => 'Your KYC submission has been ' . strtolower($request->status) . '.',
            'link' => route('dashboard.kyc'),
        ]);

        return back()->with('success', 'KYC status updated successfully.');
    }

    /**
     * Manage support tickets
     */
    public function supportTickets(Request $request)
    {
        $query = SupportTicket::with('user');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()->paginate(20);

        return view('admin.support', compact('tickets'));
    }

    /**
     * Reply to support ticket
     */
    public function replyToTicket(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        // Update ticket status if needed
        if ($ticket->status === 'open') {
            $ticket->status = 'in_progress';
            $ticket->save();
        }

        // Create notification for user
        Notification::create([
            'user_id' => $ticket->user_id,
            'type' => 'support_ticket_reply',
            'title' => 'Support Ticket Update',
            'message' => 'You have a new reply on your support ticket: ' . $ticket->subject,
            'link' => route('dashboard.support'),
        ]);

        return back()->with('success', 'Reply sent successfully.');
    }

    /**
     * Manage Tesla cars inventory
     */
    public function inventory(Request $request)
    {
        $query = TeslaCar::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('year', 'like', "%{$search}%");
            });
        }

        $cars = $query->latest()->paginate(20);

        return view('admin.inventory', compact('cars'));
    }

    /**
     * Show create inventory form
     */
    public function createInventory()
    {
        return view('admin.inventory-form');
    }

    /**
     * Store new inventory item
     */
    public function storeInventory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'variant' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'range_miles' => 'nullable|integer|min:0',
            'top_speed_mph' => 'nullable|integer|min:0',
            'zero_to_sixty' => 'nullable|numeric|min:0',
            'drivetrain' => 'nullable|string|max:255',
            'image_url' => 'nullable|url|max:500',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:5120', // 5MB max per image
            'display_order' => 'nullable|integer|min:0',
            'is_available' => 'nullable|boolean',
        ]);

        $validated['is_available'] = $request->has('is_available');

        // Remove images from validated (we'll handle it separately)
        unset($validated['images']);

        // Create car first to get ID
        $car = TeslaCar::create($validated);

        // Handle image uploads
        $uploadedImages = [];
        if ($request->hasFile('images')) {
            $uploadPath = public_path('cars/' . $car->id);
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($uploadPath, $filename);
                $uploadedImages[] = 'cars/' . $car->id . '/' . $filename;
            }
        }

        // Update car with uploaded images
        if (!empty($uploadedImages)) {
            $car->images = $uploadedImages;
            $car->save();
        }

        return redirect()->route('admin.inventory')->with('success', 'Vehicle created successfully.');
    }

    /**
     * Show edit inventory form
     */
    public function editInventory(TeslaCar $car)
    {
        return view('admin.inventory-form', compact('car'));
    }

    /**
     * Update inventory item
     */
    public function updateInventory(Request $request, TeslaCar $car)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'variant' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'range_miles' => 'nullable|integer|min:0',
            'top_speed_mph' => 'nullable|integer|min:0',
            'zero_to_sixty' => 'nullable|numeric|min:0',
            'drivetrain' => 'nullable|string|max:255',
            'image_url' => 'nullable|url|max:500',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:5120',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'string',
            'display_order' => 'nullable|integer|min:0',
            'is_available' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['is_available'] = $request->has('is_available');
        $validated['is_featured'] = $request->has('is_featured');

        // Remove images from validated (we'll handle it separately)
        unset($validated['images']);

        // Handle image deletions
        $currentImages = $car->images ?? [];
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageToDelete) {
                $imagePath = public_path($imageToDelete);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $currentImages = array_filter($currentImages, function($img) use ($imageToDelete) {
                    return $img !== $imageToDelete;
                });
            }
            $currentImages = array_values($currentImages); // Re-index array
        }

        // Handle new image uploads
        $uploadedImages = $currentImages;
        if ($request->hasFile('images')) {
            $uploadPath = public_path('cars/' . $car->id);
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($uploadPath, $filename);
                $uploadedImages[] = 'cars/' . $car->id . '/' . $filename;
            }
        }

        // Update car with uploaded images
        $validated['images'] = $uploadedImages;
        $car->update($validated);

        return redirect()->route('admin.inventory')->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Delete inventory item
     */
    public function deleteInventory(TeslaCar $car)
    {
        $car->delete();

        return redirect()->route('admin.inventory')->with('success', 'Vehicle deleted successfully.');
    }

    /**
     * Manage investment plans
     */
    public function investmentPlans(Request $request)
    {
        $plans = InvestmentPlan::latest()->paginate(20);

        return view('admin.investment-plans', compact('plans'));
    }

    /**
     * Show create investment plan form
     */
    public function createInvestmentPlan()
    {
        return view('admin.investment-plan-form');
    }

    /**
     * Store new investment plan
     */
    public function storeInvestmentPlan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:investment_plans,slug',
            'category' => 'nullable|string|max:255',
            'strategy' => 'nullable|string|max:255',
            'risk_level' => 'required|in:low,medium,high',
            'nav' => 'nullable|numeric|min:0',
            'one_year_return' => 'nullable|numeric',
            'min_investment' => 'required|numeric|min:0',
            'max_investment' => 'nullable|numeric|min:0',
            'profit_margin' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:0',
            'duration_label' => 'nullable|string|max:64',
            'is_featured' => 'nullable|boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['nav'] = $validated['nav'] ?? 0;
        $validated['max_investment'] = $validated['max_investment'] ?? null;
        if ($validated['max_investment'] !== null) {
            $validated['max_investment'] = (float) $validated['max_investment'];
        }

        InvestmentPlan::create($validated);

        return redirect()->route('admin.investment-plans')->with('success', 'Investment plan created successfully.');
    }

    /**
     * Show edit investment plan form
     */
    public function editInvestmentPlan(InvestmentPlan $plan)
    {
        return view('admin.investment-plan-form', compact('plan'));
    }

    /**
     * Update investment plan
     */
    public function updateInvestmentPlan(Request $request, InvestmentPlan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:investment_plans,slug,' . $plan->id,
            'category' => 'nullable|string|max:255',
            'strategy' => 'nullable|string|max:255',
            'risk_level' => 'required|in:low,medium,high',
            'nav' => 'nullable|numeric|min:0',
            'one_year_return' => 'nullable|numeric',
            'min_investment' => 'required|numeric|min:0',
            'max_investment' => 'nullable|numeric|min:0',
            'profit_margin' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:0',
            'duration_label' => 'nullable|string|max:64',
            'is_featured' => 'nullable|boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['nav'] = $validated['nav'] ?? 0;
        $validated['max_investment'] = $validated['max_investment'] ?? null;
        if ($validated['max_investment'] !== null) {
            $validated['max_investment'] = (float) $validated['max_investment'];
        }

        $plan->update($validated);

        return redirect()->route('admin.investment-plans')->with('success', 'Investment plan updated successfully.');
    }

    /**
     * Delete investment plan
     */
    public function deleteInvestmentPlan(InvestmentPlan $plan)
    {
        $plan->delete();

        return redirect()->route('admin.investment-plans')->with('success', 'Investment plan deleted successfully.');
    }

    /**
     * Manage stocks
     */
    public function stocks(Request $request)
    {
        $stocks = Stock::latest()->paginate(20);

        return view('admin.stocks', compact('stocks'));
    }

    /**
     * Show create stock form
     */
    public function createStock()
    {
        return view('admin.stock-form');
    }

    /**
     * Store new stock
     */
    public function storeStock(Request $request)
    {
        $validated = $request->validate([
            'ticker' => 'required|string|max:10|unique:stocks,ticker',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'change' => 'nullable|numeric',
            'change_percent' => 'nullable|numeric',
            'volume' => 'nullable',
            'market_cap' => 'nullable|string|max:255',
            'domain' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        // Set default values for non-nullable fields
        $validated['volume'] = isset($validated['volume']) && $validated['volume'] !== '' ? (string)$validated['volume'] : '0';
        $validated['change'] = $validated['change'] ?? 0;
        $validated['change_percent'] = $validated['change_percent'] ?? 0;
        $validated['market_cap'] = $validated['market_cap'] ?? '';

        Stock::create($validated);

        return redirect()->route('admin.stocks')->with('success', 'Stock created successfully.');
    }

    /**
     * Show edit stock form
     */
    public function editStock(Stock $stock)
    {
        return view('admin.stock-form', compact('stock'));
    }

    /**
     * Update stock
     */
    public function updateStock(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'ticker' => 'required|string|max:10|unique:stocks,ticker,' . $stock->id,
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'change' => 'nullable|numeric',
            'change_percent' => 'nullable|numeric',
            'volume' => 'nullable',
            'market_cap' => 'nullable|string|max:255',
            'domain' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        // Set default values for non-nullable fields if empty
        $validated['volume'] = isset($validated['volume']) && $validated['volume'] !== '' ? (string)$validated['volume'] : ($stock->volume ?? '0');
        $validated['change'] = $validated['change'] ?? $stock->change ?? 0;
        $validated['change_percent'] = $validated['change_percent'] ?? $stock->change_percent ?? 0;
        $validated['market_cap'] = $validated['market_cap'] ?? $stock->market_cap ?? '';

        $stock->update($validated);

        return redirect()->route('admin.stocks')->with('success', 'Stock updated successfully.');
    }

    /**
     * Delete stock
     */
    public function deleteStock(Stock $stock)
    {
        $stock->delete();

        return redirect()->route('admin.stocks')->with('success', 'Stock deleted successfully.');
    }

    /**
     * Show payment settings page
     */
    public function paymentSettings()
    {
        // Keep legacy "settings" view for now, but the main CRUD is in paymentMethods.*
        // Redirect to the new payment methods index where admin can fully manage methods.
        return redirect()->route('admin.payment-methods');
    }

    /**
     * Update payment settings
     */
    public function updatePaymentSettings(Request $request)
    {
        // Legacy endpoint no longer used; keep for BC but point admins to the new CRUD.
        return redirect()->route('admin.payment-methods')
            ->with('success', 'Please use the Payment Methods page to manage deposit methods.');
    }

    /**
     * List all payment methods (admin)
     */
    public function paymentMethods()
    {
        $methods = PaymentMethod::orderBy('display_order')->orderBy('name')->paginate(20);

        return view('admin.payment-methods', compact('methods'));
    }

    /**
     * Show create payment method form
     */
    public function createPaymentMethod()
    {
        return view('admin.payment-method-form');
    }

    /**
     * Store new payment method
     */
    public function storePaymentMethod(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:payment_methods,slug',
            'type' => 'required|in:deposit,withdrawal,both',
            'category' => 'nullable|string|max:50',
            'logo_url' => 'nullable|string|max:500',
            'details' => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        }

        $validated['category'] = $validated['category'] ?? 'other';
        $validated['display_order'] = $validated['display_order'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        PaymentMethod::create($validated);

        return redirect()->route('admin.payment-methods')->with('success', 'Payment method created successfully.');
    }

    /**
     * Show edit payment method form
     */
    public function editPaymentMethod(PaymentMethod $method)
    {
        return view('admin.payment-method-form', compact('method'));
    }

    /**
     * Update payment method
     */
    public function updatePaymentMethod(Request $request, PaymentMethod $method)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:payment_methods,slug,' . $method->id,
            'type' => 'required|in:deposit,withdrawal,both',
            'category' => 'nullable|string|max:50',
            'logo_url' => 'nullable|string|max:500',
            'details' => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        }

        $validated['category'] = $validated['category'] ?? 'other';
        $validated['display_order'] = $validated['display_order'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        $method->update($validated);

        return redirect()->route('admin.payment-methods')->with('success', 'Payment method updated successfully.');
    }

    /**
     * Delete payment method
     */
    public function deletePaymentMethod(PaymentMethod $method)
    {
        $method->delete();

        return redirect()->route('admin.payment-methods')->with('success', 'Payment method deleted successfully.');
    }
}
