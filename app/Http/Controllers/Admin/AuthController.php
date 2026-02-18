<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Mail\AdminPasswordResetMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Show the admin login form
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => __('The provided credentials do not match our records.'),
        ]);
    }

    /**
     * Handle admin logout request
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /**
     * Show the forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Send password reset link email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Check if admin exists
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            throw ValidationException::withMessages([
                'email' => ['No admin account found with this email address.'],
            ]);
        }

        // Delete any existing tokens for this email
        DB::table('admin_password_reset_tokens')->where('email', $request->email)->delete();

        // Generate a new token
        $token = Str::random(64);

        // Store the token in database
        DB::table('admin_password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now(),
        ]);

        // Send email with reset link
        try {
            Mail::to($admin->email)->send(new AdminPasswordResetMail($admin, $token));
        } catch (\Exception $e) {
            \Log::error('Admin password reset email failed: ' . $e->getMessage());
        }

        return back()->with('status', 'If an admin account exists with this email, you will receive a password reset link.');
    }

    /**
     * Show the reset password form
     */
    public function showResetPasswordForm($token)
    {
        return view('admin.auth.reset-password', ['token' => $token]);
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
        $tokenRecord = DB::table('admin_password_reset_tokens')
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
            DB::table('admin_password_reset_tokens')->where('email', $request->email)->delete();
            throw ValidationException::withMessages([
                'email' => ['This reset token has expired. Please request a new one.'],
            ]);
        }

        // Find the admin and update password
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            throw ValidationException::withMessages([
                'email' => ['We could not find an admin with this email address.'],
            ]);
        }

        // Update the password
        $admin->password = Hash::make($request->password);
        $admin->save();

        // Delete the token
        DB::table('admin_password_reset_tokens')->where('email', $request->email)->delete();

        // Log the admin in
        Auth::guard('admin')->login($admin);

        return redirect()->route('admin.dashboard')->with('success', 'Your password has been reset successfully!');
    }
}
