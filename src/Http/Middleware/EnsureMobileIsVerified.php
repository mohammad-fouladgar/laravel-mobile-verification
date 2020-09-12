<?php

namespace Fouladgar\MobileVerification\Http\Middleware;

use Closure;
use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;

class EnsureMobileIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param null    $redirectToRoute
     *
     * @return mixed|void
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        $user = auth()->user();

        if (!$user || ($user instanceof MustVerifyMobile && !$user->hasVerifiedMobile())) {
            return $request->expectsJson()
                ? abort(403, 'Your mobile number is not verified.')
                : redirect('/')->withErrors(['mobile' => 'Your mobile number is not verified.']);
        }

        return $next($request);
    }
}
