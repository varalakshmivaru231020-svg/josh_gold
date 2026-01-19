<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show registration form
    public function showRegistrationForm()
    {
        return view('agent.auth.register');
    }

    // Send OTP for registration
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10|unique:agents,mobile'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' =>$validator->errors()->first() , 'errors' => $validator->errors()]);
        }

        $otp = rand(1000, 9999);
        session(['registration_otp' => $otp, 'registration_mobile' => $request->mobile]);

        try {
            $whatsapp = new WhatsAppService();
            $whatsapp->sendTemplate(
                $request->mobile,
                "aunthetication_otp",
                [$otp.''],
                ["verify-user"]
            );
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send OTP', 'error' => $e->getMessage()]);
        }

        return response()->json(['success' => true, 'otp' => $otp]);
    }

    // Register agent
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile' => 'required|digits:10|unique:agents,mobile',
            'email' => 'nullable|email|unique:agents,email',
            'otp' => 'required|digits:4',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->otp != session('registration_otp')) {
            return redirect()->back()->with('error', 'Invalid OTP')->withInput();
        }

        $agent = Agent::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile_verified_at' => now()
        ]);
       $agent->referral_code = $agent->generateReferralCode();
       $agent->save();

        Auth::guard('agent')->login($agent);
        session()->forget(['registration_otp', 'registration_mobile']);

        return redirect()->route('agent.dashboard');
    }

    // Show login form
    public function showLoginForm()
    {
        return view('agent.auth.login');
    }

    // Login agent
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'mobile' => 'required',
            'password' => 'required'
        ]);

        if (Auth::guard('agent')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('agent.dashboard');
        }

        return back()->withErrors([
            'mobile' => 'The provided credentials do not match our records.',
        ]);
    }

    // Forgot Password Form
    public function showForgotPasswordForm()
    {
        return view('agent.auth.forgot-password');
    }

    public function sendForgotPasswordOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10|exists:agents,mobile',
        ]);

        $otp = rand(1000, 9999);
        session(['forgot_password_otp' => $otp, 'forgot_password_mobile' => $request->mobile]);

        try {
            $whatsapp = new WhatsAppService();
            $whatsapp->sendTemplate(
                $request->mobile,
                "aunthetication_otp",
                [$otp.''],
                ["verify-user"]
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send OTP. Try again.');
        }

        return redirect()->route('agent.verify-otp')->with('success', 'OTP sent to your WhatsApp.');
    }

    public function resendOtp(Request $request)
    {
        $mobile = session('forgot_password_mobile');
        if (!$mobile) {
            return redirect()->route('agent.login')->with('error', 'Session expired. Please login again.');
        }

        $otp = rand(1000, 9999);
        session(['forgot_password_otp' => $otp]);

        try {
            $whatsapp = new WhatsAppService();
            $whatsapp->sendTemplate(
                $mobile,
                "aunthetication_otp",
                [$otp.''],
                ["verify-user"]
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to resend OTP. Try again.');
        }

        return redirect()->back()->with('success', 'OTP resent successfully.');
    }

    public function showVerifyOtpForm()
    {
        return view('agent.auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:4'
        ]);

        if ($request->otp != session('forgot_password_otp')) {
            return back()->with('error', 'Invalid OTP');
        }

        session(['otp_verified' => true]);

        return redirect()->route('agent.reset-password');
    }

    public function showResetPasswordForm()
    {
        if (!session('otp_verified')) {
            return redirect()->route('agent.forgot-password');
        }

        return view('agent.auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed'
        ]);

        $mobile = session('forgot_password_mobile');
        $agent = Agent::where('mobile', $mobile)->first();
        $agent->password = Hash::make($request->password);
        $agent->save();

        session()->forget(['forgot_password_otp', 'forgot_password_mobile', 'otp_verified']);

        return redirect()->route('agent.login')->with('success', 'Password reset successfully! Please login.');
    }

    public function logout(Request $request)
    {
        Auth::guard('agent')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('agent.login');
    }
}
