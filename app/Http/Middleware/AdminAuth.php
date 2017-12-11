<?php

namespace App\Http\Middleware;

use App\Repositories\Admin\AdminAuthRepository;
use Closure;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $uid = $request->security()->get('uid');
        $adminauth = new AdminAuthRepository(['uid' => $uid]);
        $adminauth->contract();
        if (! $adminauth->passes()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json($adminauth->wrap(), $adminauth->status);
            } else {
                if($adminauth->errorType == 1){
                    return redirect('/');
                }elseif ($adminauth->errorType == 2){
                    return redirect('/admin/forbidden');
                }
            }
        }
        return $next($request);
    }
}
