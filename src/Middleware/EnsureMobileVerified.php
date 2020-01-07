<?php

namespace Fouladgar\MobileVerifier\Middleware;

use Closure;
use Fouladgar\MobileVerifier\Contracts\MustVerifyMobile;
use Illuminate\Support\Facades\Redirect;

class EnsureMobileIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if (! $request->user() ||
            ($request->user() instanceof MustVerifyMobile &&
            ! $request->user()->hasVerifiedMobile())) {
            
                abort(403, 'Your mobile number is not verified.');
        }

        return $next($request);
    }
}
