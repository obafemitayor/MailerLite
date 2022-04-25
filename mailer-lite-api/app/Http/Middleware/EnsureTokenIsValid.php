<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Interface\SubscriberProvider;

class EnsureTokenIsValid
{
    protected $subscriberProvider;

    public function __construct(SubscriberProvider $_subscriberProvider)
    {
        $this->subscriberProvider = $_subscriberProvider;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Prevent Cross Site Forgery Attack
        $access_token = $request->header('authToken');
        if (is_null($access_token)) {
            return response('No Token Present In Header', 401);
        }
        $isauthcodevalid = $this->subscriberProvider->validate_auth_token($access_token);
        if (! $isauthcodevalid) {
            return response('Invalid Token', 401);
        }
        return $next($request);
    }
}
