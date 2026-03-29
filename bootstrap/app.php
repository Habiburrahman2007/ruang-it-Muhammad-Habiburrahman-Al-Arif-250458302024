<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
            'banned' => \App\Http\Middleware\CheckBanned::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Log all exceptions for debugging
        $exceptions->report(function (Throwable $e) {
            $data = [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];

            // Only log request URL if running in a web context and request is bound
            // Avoid using app() if it might be in a bad state, but here it should be fine
            try {
                if (function_exists('app') && !app()->runningInConsole() && app()->bound('request')) {
                    $data['url'] = request()->fullUrl();
                }
            } catch (\Throwable $t) {}

            // Use auth()->id() if possible, or just skip if auth is not ready
            try {
                if (function_exists('app') && app()->bound('auth')) {
                    $data['user_id'] = auth()->id();
                }
            } catch (\Throwable $t) {}

            // Use logger instance directly to avoid Facade root issues
            try {
                if (function_exists('app') && app()->bound('log')) {
                    app('log')->error('Application Error: ' . $e->getMessage(), $data);
                }
            } catch (\Throwable $t) {
                // Last resort: if everything fails, we can't log here
            }
        });

        // Handle database errors gracefully
        $exceptions->render(function (\Illuminate\Database\QueryException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Terjadi kesalahan database. Silakan coba lagi.'
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan database. Silakan coba lagi.');
        });

        // Handle authentication errors
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Sesi Anda telah berakhir. Silakan login kembali.']);
        });
    })->create();
