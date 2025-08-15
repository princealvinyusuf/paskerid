<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\App\Http\Middleware\LogVisitor::class);
        // Trust common proxy headers so HTTPS is detected correctly behind load balancers
        $middleware->trustProxies(at: '*', headers: Request::HEADER_X_FORWARDED_ALL);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
