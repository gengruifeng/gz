<?php

namespace App\Http\Middleware;

use App\Http\Requests\Request;
use Closure;

use App\Repositories\UserSessionRepository;

class UserSession
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

        if (! $repoSession->passes()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json($repoSession->wrap(), $repoSession->status);
            } else {
                $oUrl = urlencode($_SERVER['REQUEST_URI']);
                return redirect('login?l='.$oUrl);
            }
        }

        return $next($request);
    }
}
