<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class AddUserToRequest
 * @package App\Http\Middleware
 */
class AddUserToRequest
{
    public function handle(Request $request, Closure $next)
    {
        if (!empty($request->cookie('auth_token'))) {
            self::addTokenToHeader($request);
        }

        return $next($request);
    }

    private function addTokenToHeader(Request $request)
    {
        $request->headers->set('Authorization', 'Bearer ' . $request->cookie('auth_token'));
    }
}
