<?php

namespace Fouladgar\MobileVerifier\Middleware;

use Fouladgar\MobileVerifier\Contracts\MustVerifyMobile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Closure;

class EnsureMobileIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $redirectToRoute
     * @return Response|RedirectResponse
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if (!$request->user() || ($request->user() instanceof MustVerifyMobile && !$request->user()->hasVerifiedMobile())) {
            abort(403, 'Your mobile number is not verified.');
        }

        return $next($request);
    }
}
