<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserNotBlocked
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->is_blocked) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Ваш аккаунт заблокирован. Обратитесь в поддержку.']);
        }

        return $next($request);
    }
}
