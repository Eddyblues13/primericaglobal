<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use App\Models\EmailVerificationCode;
use App\Mail\WelcomeEmail;
use App\Mail\PasswordResetMail;
use App\Mail\VerificationCodeEmail;
use App\Mail\LoginNotificationEmail;
use App\Services\IpGeolocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request, IpGeolocationService $geoService)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Get authenticated user
            $user = Auth::user();

            // Collect login data
            $ip = $request->ip();
            $userAgent = $request->userAgent();
            
            // Get location from IP
            $location = $geoService->getLocation($ip);
            
            // Parse user agent
            $deviceInfo = $geoService->parseUserAgent($userAgent);
            
            // Prepare login data
            $loginData = [
                'ip' => $ip,
                'location' => $location,
                'browser' => $deviceInfo['browser'],
                'os' => $deviceInfo['os'],
                'device' => $deviceInfo['device'],
                'time' => Carbon::now()->format('F j, Y \a\t g:i A'),
            ];

            // Send login notification email (asynchronously, don't block login)
            try {
                Mail::to($user->email)->send(new LoginNotificationEmail($user, $loginData));
            } catch (\Exception $e) {
                // Log error but don't fail login if email fails
                \Log::error('Login notification email failed: ' . $e->getMessage());
            }

            return redirect()->intended(route('dashboard.index'));
        }

        throw ValidationException::withMessages([
            'email' => __('The provided credentials do not match our records.'),
        ]);
    }

    /**
     * Show the registration form
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Generate a 4-digit verification code
        $code = EmailVerificationCode::generateCode();

        // Delete any existing verification codes for this user
        EmailVerificationCode::where('user_id', $user->id)->delete();

        // Create new verification code
        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Send verification code email
        try {
            Mail::to($user->email)->send(new VerificationCodeEmail($user, $code));
        } catch (\Exception $e) {
            // Log error but don't fail registration if email fails
            \Log::error('Verification email failed: ' . $e->getMessage());
        }

        // Store email in session for verification page
        $request->session()->put('verification_email', $user->email);
        $request->session()->put('verification_user_id', $user->id);

        return redirect()->route('verify-email')->with('success', 'Registration successful! Please check your email for the verification code.');
    }

    /**
     * Show the email verification form
     */
    public function showVerificationForm(Request $request)
    {
        $email = $request->session()->get('verification_email');
        
        if (!$email) {
            return redirect()->route('login')->with('error', 'Please register or log in first.');
        }

        return view('auth.verify-email', ['email' => $email]);
    }

    /**
     * Verify the email with the provided code
     */
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:4'],
        ]);

        $userId = $request->session()->get('verification_user_id');
        
        if (!$userId) {
            throw ValidationException::withMessages([
                'code' => ['Session expired. Please register again.'],
            ]);
        }

        $user = User::find($userId);

        if (!$user) {
            throw ValidationException::withMessages([
                'code' => ['User not found. Please register again.'],
            ]);
        }

        // Find the verification code
        $verificationCode = EmailVerificationCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->first();

        if (!$verificationCode) {
            throw ValidationException::withMessages([
                'code' => ['Invalid verification code. Please try again.'],
            ]);
        }

        // Check if code is expired
        if ($verificationCode->isExpired()) {
            $verificationCode->delete();
            throw ValidationException::withMessages([
                'code' => ['Verification code has expired. Please request a new one.'],
            ]);
        }

        // Mark email as verified
        $user->email_verified_at = Carbon::now();
        $user->save();

        // Delete the verification code
        $verificationCode->delete();

        // Clear session data
        $request->session()->forget(['verification_email', 'verification_user_id']);

        // Send welcome email
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));
        } catch (\Exception $e) {
            \Log::error('Welcome email failed: ' . $e->getMessage());
        }

        // Create welcome notification
        try {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'account_created',
                'title' => 'Welcome to TESLA!',
                'message' => 'Your account has been successfully created. Start exploring investment opportunities, trading stocks, and browsing our premium vehicle inventory.',
                'link' => route('dashboard.index'),
            ]);
        } catch (\Exception $e) {
            \Log::error('Welcome notification failed: ' . $e->getMessage());
        }

        // Log the user in
        Auth::login($user);

        return redirect()->route('dashboard.index')->with('success', 'Email verified successfully! Welcome to TESLA.');
    }

    /**
     * Resend verification code
     */
    public function resendVerificationCode(Request $request)
    {
        $userId = $request->session()->get('verification_user_id');
        
        if (!$userId) {
            return back()->with('error', 'Session expired. Please register again.');
        }

        $user = User::find($userId);

        if (!$user) {
            return back()->with('error', 'User not found. Please register again.');
        }

        // Generate a new 4-digit verification code
        $code = EmailVerificationCode::generateCode();

        // Delete any existing verification codes for this user
        EmailVerificationCode::where('user_id', $user->id)->delete();

        // Create new verification code
        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Send verification code email
        try {
            Mail::to($user->email)->send(new VerificationCodeEmail($user, $code));
        } catch (\Exception $e) {
            \Log::error('Verification email failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send verification code. Please try again.');
        }

        return back()->with('success', 'A new verification code has been sent to your email.');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Show the forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Check if user exists
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['No account found with this email address.'],
            ]);
        }

        // Delete any existing tokens for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Generate a new token
        $token = Str::random(64);

        // Store the token in database
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now(),
        ]);

        // Send email with reset link
        try {
            Mail::to($user->email)->send(new PasswordResetMail($user, $token));
        } catch (\Exception $e) {
            \Log::error('Password reset email failed: ' . $e->getMessage());
        }

        return back()->with('status', 'If an account exists with this email, you will receive a password reset link.');
    }

    /**
     * Show the reset password form
     */
    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset the password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Find the token record
        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenRecord) {
            throw ValidationException::withMessages([
                'email' => ['Invalid or expired reset token.'],
            ]);
        }

        // Check if token matches
        if (!Hash::check($request->token, $tokenRecord->token)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid or expired reset token.'],
            ]);
        }

        // Check if token is expired (60 minutes)
        $tokenAge = Carbon::parse($tokenRecord->created_at)->diffInMinutes(Carbon::now());
        if ($tokenAge > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            throw ValidationException::withMessages([
                'email' => ['This reset token has expired. Please request a new one.'],
            ]);
        }

        // Find the user and update password
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['We could not find a user with this email address.'],
            ]);
        }

        // Update the password
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Log the user in
        Auth::login($user);

        return redirect()->route('dashboard.index')->with('success', 'Your password has been reset successfully!');
    }
}
