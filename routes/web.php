<?php

use Illuminate\Support\Facades\Route;

use App\Services\StockMarketService;
use App\Services\NewsService;
use App\Models\InvestmentPlan;
use App\Models\TeslaCar;

Route::get('/', function (StockMarketService $stockService, NewsService $newsService) {
    // Always return data, even if APIs fail (they have fallbacks)
    $featuredStocks = [];
    $topGainers = [];
    $topLosers = [];
    $mostActive = [];
    $marketNews = [];
    
    try {
        // Fetch stock market data with timeout protection
        $featuredStocks = $stockService->getFeaturedStocks() ?: [];
        $topGainers = $stockService->getTopGainers(5) ?: [];
        $topLosers = $stockService->getTopLosers(5) ?: [];
        $mostActive = $stockService->getMostActive(5) ?: [];
    } catch (\Exception $e) {
        \Log::error('Stock data fetch error: ' . $e->getMessage());
    }
    
    try {
        // Fetch market news
        $marketNews = $newsService->getMarketNews(6) ?: [];
    } catch (\Exception $e) {
        \Log::error('News fetch error: ' . $e->getMessage());
    }

    // Tesla Investment Plans (4 plans as defined in seeder)
    $investmentPlans = InvestmentPlan::orderBy('display_order')->get();
    
    // Tesla Cars for homepage (show featured and available cars, limit to 4 for homepage)
    $featuredCars = TeslaCar::where('is_available', true)
        ->where('is_featured', true)
        ->orderBy('display_order')
        ->take(4)
        ->get();
    
    return view('home.index', [
        'featuredStocks' => $featuredStocks,
        'topGainers' => $topGainers,
        'topLosers' => $topLosers,
        'mostActive' => $mostActive,
        'marketNews' => $marketNews,
        'investmentPlans' => $investmentPlans,
        'featuredCars' => $featuredCars,
    ]);
})->name('home');

Route::get('/inventory', function () {
    $cars = TeslaCar::where('is_available', true)
        ->orderBy('display_order')
        ->get();
    return view('inventory.index', ['cars' => $cars]);
})->name('inventory');

Route::get('/inventory/{car}', function (TeslaCar $car) {
    if (!$car->is_available) {
        abort(404);
    }
    return view('inventory.show', ['car' => $car]);
})->name('inventory.show');

Route::get('/invest', function () {
    $plans = InvestmentPlan::orderBy('display_order')->get();
    return view('invest.index', ['investmentPlans' => $plans]);
})->name('invest');

Route::get('/stocks', function (StockMarketService $stockService) {
    try {
        // Fetch stock market data
        $featuredStocks = $stockService->getFeaturedStocks() ?: [];
        $topGainers = $stockService->getTopGainers(5) ?: [];
        $topLosers = $stockService->getTopLosers(5) ?: [];
        $mostActive = $stockService->getMostActive(5) ?: [];
        
        return view('stocks.index', [
            'featuredStocks' => $featuredStocks,
            'topGainers' => $topGainers,
            'topLosers' => $topLosers,
            'mostActive' => $mostActive,
        ]);
    } catch (\Exception $e) {
        \Log::error('Stocks page data fetch error: ' . $e->getMessage());
        return view('stocks.index', [
            'featuredStocks' => [],
            'topGainers' => [],
            'topLosers' => [],
            'mostActive' => [],
        ]);
    }
})->name('stocks');

Route::get('/portfolio', function () {
    return view('portfolio.index');
})->name('portfolio');


Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/help', function () {
    return view('pages.help');
})->name('help');

Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');

// SEO Routes
use App\Http\Controllers\SeoController;

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');

use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Email Verification Routes
Route::get('/verify-email', [AuthController::class, 'showVerificationForm'])->name('verify-email');
Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->name('verify-email.post');
Route::post('/resend-verification-code', [AuthController::class, 'resendVerificationCode'])->name('resend-verification-code');

// Forgot Password Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('forgot-password.post');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('reset-password');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password.post');

use App\Http\Controllers\DashboardController;

// Public stock logo route (for homepage)
Route::get('/stock-logo/{ticker}', [DashboardController::class, 'stockLogo'])->name('stock-logo');

Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/support', [DashboardController::class, 'support'])->name('support');
    Route::post('/support', [DashboardController::class, 'submitSupport'])->name('support.submit');
    Route::get('/stock-logo/{ticker}', [DashboardController::class, 'stockLogo'])->name('stock-logo');
    Route::get('/wallet', [DashboardController::class, 'wallet'])->name('wallet');
    Route::get('/wallet/deposit', [DashboardController::class, 'walletDeposit'])->name('wallet.deposit');
    Route::get('/wallet/withdraw', [DashboardController::class, 'walletWithdraw'])->name('wallet.withdraw');
    Route::post('/wallet/deposit', [DashboardController::class, 'walletDepositSubmit'])->name('wallet.deposit.submit');
    Route::post('/wallet/withdraw', [DashboardController::class, 'walletWithdrawSubmit'])->name('wallet.withdraw.submit');
    Route::get('/investments', [DashboardController::class, 'investments'])->name('investments');
    Route::post('/investments', [DashboardController::class, 'investSubmit'])->name('investments.submit');
    Route::get('/stocks', [DashboardController::class, 'stocks'])->name('stocks');
    Route::post('/stocks', [DashboardController::class, 'stockPurchaseSubmit'])->name('stocks.purchase.submit');
    Route::get('/portfolio', function () {
        return view('user.portfolio');
    })->name('portfolio');
    Route::get('/investment-dashboard', [DashboardController::class, 'investmentDashboard'])->name('investment-dashboard');
    Route::get('/inventory', [DashboardController::class, 'inventory'])->name('inventory');
    Route::get('/orders', [DashboardController::class, 'orders'])->name('orders');
    Route::post('/orders', [DashboardController::class, 'placeOrder'])->name('orders.place');
    Route::get('/account', [DashboardController::class, 'account'])->name('account');
    Route::put('/account', [DashboardController::class, 'updateAccount'])->name('account.update');
    Route::get('/kyc', [DashboardController::class, 'kyc'])->name('kyc');
    Route::post('/kyc', [DashboardController::class, 'submitKyc'])->name('kyc.submit');
    Route::get('/notifications', [DashboardController::class, 'getNotifications'])->name('notifications.get');
    Route::post('/notifications/mark-read', [DashboardController::class, 'markNotificationAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [DashboardController::class, 'markAllNotificationsAsRead'])->name('notifications.mark-all-read');
});

Route::get('/test', function () {
    return 'Laravel is working!';
});

// Admin Authentication Routes
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Middleware\AdminMiddleware;

Route::prefix('admin')->name('admin.')->group(function () {
    // Admin login routes (public)
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    
    // Admin logout route
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    
    // Admin Forgot Password Routes
    Route::get('/forgot-password', [AdminAuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
    Route::post('/forgot-password', [AdminAuthController::class, 'sendResetLinkEmail'])->name('forgot-password.post');
    Route::get('/reset-password/{token}', [AdminAuthController::class, 'showResetPasswordForm'])->name('reset-password');
    Route::post('/reset-password', [AdminAuthController::class, 'resetPassword'])->name('reset-password.post');
    
    // Admin protected routes
    Route::middleware('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Users Management
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
        Route::get('/users/create', [AdminDashboardController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminDashboardController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}', [AdminDashboardController::class, 'showUser'])->name('users.show');
        Route::get('/users/{user}/edit', [AdminDashboardController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminDashboardController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminDashboardController::class, 'deleteUser'])->name('users.delete');
        Route::get('/users/{user}/transactions', [AdminDashboardController::class, 'userTransactions'])->name('users.transactions');
        Route::post('/users/{user}/balance', [AdminDashboardController::class, 'updateUserBalance'])->name('users.balance');
        Route::post('/users/{user}/profit', [AdminDashboardController::class, 'updateUserProfit'])->name('users.profit');
        Route::post('/users/{user}/email', [AdminDashboardController::class, 'sendEmailToUser'])->name('users.email');
        Route::post('/users/{user}/impersonate', [AdminDashboardController::class, 'impersonateUser'])->name('users.impersonate');
        
        // Orders Management
        Route::get('/orders', [AdminDashboardController::class, 'orders'])->name('orders');
        Route::post('/orders/{order}/status', [AdminDashboardController::class, 'updateOrderStatus'])->name('orders.status');
        
        // Transactions Management
        Route::get('/transactions', [AdminDashboardController::class, 'transactions'])->name('transactions');
        Route::get('/transactions/create', [AdminDashboardController::class, 'createTransaction'])->name('transactions.create');
        Route::post('/transactions', [AdminDashboardController::class, 'storeTransaction'])->name('transactions.store');
        Route::get('/transactions/{transaction}/edit', [AdminDashboardController::class, 'editTransaction'])->name('transactions.edit');
        Route::put('/transactions/{transaction}', [AdminDashboardController::class, 'updateTransaction'])->name('transactions.update');
        Route::delete('/transactions/{transaction}', [AdminDashboardController::class, 'deleteTransaction'])->name('transactions.delete');
        Route::post('/transactions/{transaction}/status', [AdminDashboardController::class, 'updateTransactionStatus'])->name('transactions.status');
        Route::post('/transactions/{transaction}/approve', [AdminDashboardController::class, 'approveTransaction'])->name('transactions.approve');
        Route::post('/transactions/{transaction}/reject', [AdminDashboardController::class, 'rejectTransaction'])->name('transactions.reject');
        
        // KYC Management
        Route::get('/kyc', [AdminDashboardController::class, 'kycSubmissions'])->name('kyc');
        Route::post('/kyc/{kyc}/status', [AdminDashboardController::class, 'updateKycStatus'])->name('kyc.status');
        
        // Support Tickets Management
        Route::get('/support', [AdminDashboardController::class, 'supportTickets'])->name('support');
        Route::post('/support/{ticket}/reply', [AdminDashboardController::class, 'replyToTicket'])->name('support.reply');
        
        // Inventory Management
        Route::get('/inventory', [AdminDashboardController::class, 'inventory'])->name('inventory');
        Route::get('/inventory/create', [AdminDashboardController::class, 'createInventory'])->name('inventory.create');
        Route::post('/inventory', [AdminDashboardController::class, 'storeInventory'])->name('inventory.store');
        Route::get('/inventory/{car}/edit', [AdminDashboardController::class, 'editInventory'])->name('inventory.edit');
        Route::put('/inventory/{car}', [AdminDashboardController::class, 'updateInventory'])->name('inventory.update');
        Route::delete('/inventory/{car}', [AdminDashboardController::class, 'deleteInventory'])->name('inventory.delete');
        
        // Investment Plans Management
        Route::get('/investment-plans', [AdminDashboardController::class, 'investmentPlans'])->name('investment-plans');
        Route::get('/investment-plans/create', [AdminDashboardController::class, 'createInvestmentPlan'])->name('investment-plans.create');
        Route::post('/investment-plans', [AdminDashboardController::class, 'storeInvestmentPlan'])->name('investment-plans.store');
        Route::get('/investment-plans/{plan}/edit', [AdminDashboardController::class, 'editInvestmentPlan'])->name('investment-plans.edit');
        Route::put('/investment-plans/{plan}', [AdminDashboardController::class, 'updateInvestmentPlan'])->name('investment-plans.update');
        Route::delete('/investment-plans/{plan}', [AdminDashboardController::class, 'deleteInvestmentPlan'])->name('investment-plans.delete');
        
        // Stocks Management
        Route::get('/stocks', [AdminDashboardController::class, 'stocks'])->name('stocks');
        Route::get('/stocks/create', [AdminDashboardController::class, 'createStock'])->name('stocks.create');
        Route::post('/stocks', [AdminDashboardController::class, 'storeStock'])->name('stocks.store');
        Route::get('/stocks/{stock}/edit', [AdminDashboardController::class, 'editStock'])->name('stocks.edit');
        Route::put('/stocks/{stock}', [AdminDashboardController::class, 'updateStock'])->name('stocks.update');
        Route::delete('/stocks/{stock}', [AdminDashboardController::class, 'deleteStock'])->name('stocks.delete');
        
        // Payment Settings Management
        Route::get('/payment-settings', [AdminDashboardController::class, 'paymentSettings'])->name('payment-settings');
        Route::post('/payment-settings', [AdminDashboardController::class, 'updatePaymentSettings'])->name('payment-settings.update');

        // Payment Methods CRUD
        Route::get('/payment-methods', [AdminDashboardController::class, 'paymentMethods'])->name('payment-methods');
        Route::get('/payment-methods/create', [AdminDashboardController::class, 'createPaymentMethod'])->name('payment-methods.create');
        Route::post('/payment-methods', [AdminDashboardController::class, 'storePaymentMethod'])->name('payment-methods.store');
        Route::get('/payment-methods/{method}/edit', [AdminDashboardController::class, 'editPaymentMethod'])->name('payment-methods.edit');
        Route::put('/payment-methods/{method}', [AdminDashboardController::class, 'updatePaymentMethod'])->name('payment-methods.update');
        Route::delete('/payment-methods/{method}', [AdminDashboardController::class, 'deletePaymentMethod'])->name('payment-methods.delete');
    });
});

// Stop impersonation route (accessible without admin auth since user is logged in)
Route::post('/stop-impersonating', [AdminDashboardController::class, 'stopImpersonating'])->name('stop.impersonating');

