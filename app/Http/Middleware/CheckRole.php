<?php

namespace App\Http\Middleware;

use App\Services\UserService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function __construct(private UserService $userService)
    {
        //
    }
    public function handle(Request $request, Closure $next, string $role = ''): Response
    {
        if (!Auth::check() || !in_array(Auth::user()->role, explode('|', $role))) {
            $routeName = $this->userService->redirectAuthPath() ?? 'login';

            return redirect()->route($routeName);
        }
        return $next($request);
    }
}
