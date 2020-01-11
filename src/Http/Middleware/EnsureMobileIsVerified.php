<?php

namespace Fouladgar\MobileVerifier\Http\Middleware;

use Fouladgar\MobileVerifier\Contracts\MustVerifyMobile;
use Closure;

class EnsureMobileIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param null $redirectToRoute
     * @return mixed|void
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        $user = auth()->user();

        if (!$user || ($user instanceof MustVerifyMobile && !$user->hasVerifiedMobile())) {
            return abort(403, 'Your mobile number is not verified.');
        }

        return $next($request);
    }
}
