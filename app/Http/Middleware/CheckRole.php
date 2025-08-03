<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        Log::info('CheckRole middleware triggered', [
            'requested_url' => $request->url(),
            'required_roles' => $roles,
            'user_authenticated' => Auth::check()
        ]);

        if (!Auth::check()) {
            Log::warning('CheckRole: User not authenticated, redirecting to login');
            return redirect()->route('login');
        }

        $user = Auth::user();

        Log::info('CheckRole: User authenticated', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'is_active' => $user->is_active
        ]);

        if (!$user->is_active) {
            Log::warning('CheckRole: User account inactive', [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
            ]);
        }

        if (!in_array($user->role, $roles)) {
            Log::warning('CheckRole: User role not authorized', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'required_roles' => $roles
            ]);

            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        Log::info('CheckRole: Access granted', [
            'user_id' => $user->id,
            'user_role' => $user->role
        ]);

        return $next($request);
    }
}