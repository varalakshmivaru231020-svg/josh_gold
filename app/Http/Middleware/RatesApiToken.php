<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Lets the rates API through only with the secret from RATES_API_TOKEN,
// sent as "Authorization: Bearer <token>".
class RatesApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = (string) config('services.rates_api.token');

        if ($token === '' || ! hash_equals($token, (string) $request->bearerToken())) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        return $next($request);
    }
}
