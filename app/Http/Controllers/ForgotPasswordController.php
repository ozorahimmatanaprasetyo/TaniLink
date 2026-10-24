<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan form minta link reset password
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim link reset password ke email pengguna via Password Broker
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Gunakan Password Broker bawaan Laravel (dengan throttling & token expiry)
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Pesan aman yang netral agar tidak membocorkan apakah email terdaftar atau tidak
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Jika alamat email tersebut terdaftar di sistem TaniLink, tautan pengaturan ulang password telah dikirimkan ke kotak masuk Anda.');
        }

        if ($status === Password::RESET_THROTTLED) {
            return back()->withErrors([
                'email' => 'Permintaan terlalu sering. Harap tunggu beberapa saat sebelum mencoba kembali.',
            ]);
        }

        // Jika email tidak ditemukan, tetap tampilkan pesan netral demi privasi dan keamanan
        return back()->with('status', 'Jika alamat email tersebut terdaftar di sistem TaniLink, tautan pengaturan ulang password telah dikirimkan ke kotak masuk Anda.');
    }

    /**
     * Tampilkan form untuk memasukkan password baru berdasarkan token
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with([
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Proses penyimpanan password baru menggunakan Password Broker
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password Anda berhasil diperbarui! Silakan masuk dengan password baru.');
        }

        return back()->withErrors([
            'email' => 'Tautan pengaturan ulang password tidak valid atau telah kedaluwarsa. Silakan ajukan tautan baru.',
        ]);
    }
}

