<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;

class AutoLogout
{
    protected int $timeout = 60 * 5; // Set auto logout timeout in seconds


    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $key = 'last_active_' . Auth::user()->id;
            if (!Cache::has($key)) {
                Cache::forever($key, time());
            } elseif ((time() - Cache::get($key)) > $this->timeout) {
                Cache::forget($key);
                Auth::logout();
                return Redirect::route('login');
            }
        }

        return $next($request);
    }
}
