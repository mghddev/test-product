<?php
namespace App\Http\Middleware;

use App\Constant\UserType;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

/**
 * Class ApiCheckAuthentication
 * @package App\Http\Middleware
 */
class AdminAuthentication
{

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!empty($user) && $user->type == UserType::ADMIN) {
            return $next($request);
        }

        return redirect()->route('index');
    }
}
