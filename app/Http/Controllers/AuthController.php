<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Proses otentikasi via NIK & password.
     * Disesuaikan dengan mekanisme dan pesan error LoginController PMMT-v2.
     */
    public function login(Request $request)
    {
        $request->validate([
            'nik'      => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $nik      = trim($request->input('nik'));
        $password = $request->input('password');

        // Cari di mst_anggota berdasarkan NIK (fallback NIP)
        $user = User::where('nik', $nik)->orWhere('nip', $nik)->first();

        if ($user && (Hash::check($password, $user->password_hash ?? '') || md5($password) === ($user->password ?? ''))) {
            // Validasi role level_pmmt sesuai standar PMMT-v2
            if ($user->level_pmmt != null && $user->level_pmmt != "") {
                Auth::login($user, $request->boolean('remember'));
                $request->session()->regenerate();

                return redirect()->intended(route('home'))
                    ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'nik' => 'Maaf anda tidak punya akses menu, silahkan hubungi admin',
                ])->onlyInput('nik');
            }
        }

        return back()->withErrors([
            'nik' => 'Nik atau Password anda salah!',
        ])->onlyInput('nik');
    }

    /**
     * SSO Handshake Endpoint: Login via PEHA ID.
     * Disesuaikan dengan LoginController@login_pehaid PMMT-v2 dan format token dokumen PEHA ID.
     * 1. Mendukung format URL PMMT-v2: /loginpehaid/{nik}/{password}
     * 2. Mendukung format Token Terenkripsi Dokumen PEHA ID: /loginpehaid?nik=...&ts=...&token=...
     */
    public function login_pehaid(Request $request, $nik = null, $password = null)
    {
        // Format 1: Parameter URL langsung ({nik}/{password}) persis seperti di PMMT-v2
        if (!empty($nik) && !empty($password)) {
            $user = User::where('nik', $nik)->orWhere('nip', $nik)->first();

            if ($user && (Hash::check($password, $user->password_hash ?? '') || md5($password) === ($user->password ?? ''))) {
                if ($user->level_pmmt != null && $user->level_pmmt != "") {
                    Auth::login($user);
                    $request->session()->regenerate();
                    return redirect()->intended(route('home'));
                }
            }

            Auth::logout();
            return redirect()->away('https://app.phapros.co.id/peha_id/main.php?route=production');
        }

        // Format 2: Token Terenkripsi dokumen PEHA ID (?nik=...&ts=...&token=...)
        $encryptedNik = $request->query('nik');
        $timestamp    = $request->query('ts');
        $token        = $request->query('token');

        if ($encryptedNik && $timestamp && $token) {
            $secretKey = config('services.peha.key') ?: env('PEHA_ENCRYPT_KEY', 'Passw0rdRahasia');
            $salt      = config('services.peha.salt') ?: env('PEHA_ENCRYPT_SALT', 'appPeha_salt');

            $decryptedNik  = self::decryptWithSalt($encryptedNik, $secretKey, $salt);
            $expectedToken = hash_hmac('sha256', ($decryptedNik ?: '') . '|' . $timestamp, $secretKey);

            if ($decryptedNik && hash_equals($expectedToken, $token) && abs(time() - (int)$timestamp) <= 300) {
                $user = User::where('nik', $decryptedNik)->orWhere('nip', $decryptedNik)->first();

                if ($user && $user->level_pmmt != null && $user->level_pmmt != "") {
                    Auth::login($user);
                    $request->session()->regenerate();
                    return redirect()->intended(route('home'));
                }
            }

            Auth::logout();
            return redirect()->away('https://app.phapros.co.id/peha_id/main.php?route=production');
        }

        // Fallback jika tanpa kredensial valid (seperti PMMT-v2)
        Auth::logout();
        return redirect()->away('https://app.phapros.co.id/peha_id/main.php?route=production');
    }

    /**
     * Helper dekripsi AES-256-CBC dengan salt (standar PEHA ID).
     */
    public static function decryptWithSalt(string $cipherText, string $secretKey, string $salt): ?string
    {
        $method    = 'AES-256-CBC';
        $key       = hash('sha256', $secretKey . $salt, true);
        $iv        = substr(hash('sha256', 'iv_' . $salt), 0, 16);
        $cipherRaw = base64_decode(strtr($cipherText, '-_', '+/'));

        if ($cipherRaw === false) {
            return null;
        }

        $decrypted = openssl_decrypt($cipherRaw, $method, $key, OPENSSL_RAW_DATA, $iv);
        return $decrypted !== false ? $decrypted : null;
    }

    /**
     * Keluar dari sistem.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->away('https://app.phapros.co.id/peha_id/main.php?route=production');
    }
}
