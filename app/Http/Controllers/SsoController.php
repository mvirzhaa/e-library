<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SsoController extends Controller
{
    public function callback(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect('/login')->withErrors(['sso' => 'Token SSO tidak ditemukan.']);
        }

        $eportalApi = config('services.eportal.url');

        try {
            $response = Http::withHeaders([
                'X-SSO-Client-ID'     => config('services.eportal.client_id'),
                'X-SSO-Client-Secret' => config('services.eportal.client_secret'),
                'Authorization'       => 'Bearer ' . $token,
            ])->post($eportalApi . '/sso/introspect');

            $data = $response->json();

            if (!($data['valid'] ?? false)) {
                return redirect('/login')->withErrors(['sso' => 'Sesi E-Portal tidak valid atau sudah kedaluwarsa.']);
            }

            $eportalUser = $data['user'];
            $email = $eportalUser['email'] ?? null;

            if (!$email) {
                return redirect('/login')->withErrors(['sso' => 'Data email tidak ditemukan dari E-Portal.']);
            }

            $user = User::where('email', $email)->first();

            if (!$user) {
                $user = User::create([
                    'name'      => $eportalUser['email'],
                    'email'     => $email,
                    'password'  => bcrypt(Str::random(32)),
                    'role'      => $eportalUser['role'] ?? 'user',
                    'is_active' => true,
                ]);
            }

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        } catch (\Exception $e) {
            \Log::error('SSO Callback Error: ' . $e->getMessage());
            return redirect('/login')->withErrors(['sso' => 'Gagal memproses login SSO.']);
        }
    }
}
