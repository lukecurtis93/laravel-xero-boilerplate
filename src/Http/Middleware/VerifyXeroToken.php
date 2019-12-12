<?php

namespace Lukecurtis\LaravelXeroBoilerplate\Http\Middleware;

use Closure;
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Webhook;

/**
 * Class VerifyXeroToken.
 */
class VerifyXeroToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $application = new PrivateApplication(config('laravel-xero-boilerplate'));
        $webhook = new Webhook($application, $request->getContent());

        if (! $webhook->validate($request->headers->get('x-xero-signature'))) {
            $response = response()->json(null, 401);
            $response->setContent(null);

            return $response;
        }

        return $next($request);
    }
}
