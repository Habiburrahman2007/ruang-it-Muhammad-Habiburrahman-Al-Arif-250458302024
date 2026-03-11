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
            Log::error('Application Error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'url' => request()->fullUrl(),
                'user_id' => Auth::id(),
            ]);
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
