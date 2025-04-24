<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use PhpParser\Node\Stmt\TraitUseAdaptation\Alias;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web()
            ->alias([
                'role' => CheckRole::class
            ])
            ->redirectGuestsTo(function () {
                return route('login');
            });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
