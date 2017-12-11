<?php

namespace App\Http\Middleware;

use Closure;

use App\Repositories\UserSessionRepository;

class RefreshUserSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $repoSession = new UserSessionRepository();
        $repoSession->contract();

        return $next($request);
    }
}

