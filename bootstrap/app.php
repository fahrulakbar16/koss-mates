<?php

use App\Exceptions\CustomException;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\Online;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
            Online::class,
        ]);

        $middleware->alias([
            'penyewa.verified' => \App\Http\Middleware\EnsurePenyewaEmailIsVerified::class,
            'check_activation' => \App\Http\Middleware\CheckAccountActivation::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'midtrans/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $exception, $request) {
            $isApiRequest = $request->is('api/*');

            // 1. Handle API Specific Responses
            if ($isApiRequest) {
                if ($exception instanceof NotFoundHttpException || $exception instanceof ModelNotFoundException) {
                    return response()->json(['message' => 'Endpoint/Data tidak ditemukan.'], 404);
                }
                if ($exception instanceof MethodNotAllowedHttpException) {
                    return response()->json(['message' => 'Metode tidak ditemukan.'], 405);
                }
                if ($exception instanceof AuthenticationException) {
                    return response()->json(['message' => 'Token tidak valid.'], 401);
                }
                if ($exception instanceof AccessDeniedHttpException || $exception instanceof AuthorizationException) {
                    return response()->json(['message' => $exception->getMessage()], 403);
                }
                if ($exception instanceof ValidationException) {
                    $errors = [];
                    foreach ($exception->errors() as $field => $message) {
                        $errors[$field] = $message[0];
                    }
                    return response()->json(['message' => 'Data tidak valid.', 'data' => $errors], 422);
                }
                if ($exception instanceof CustomException) {
                    return response()->json(['message' => $exception->getMessage()], $exception->getCode());
                }
            }

            // 2. Handle Web Custom Logic

            // Redirect to login if session expired (Authentication)
            if ($exception instanceof AuthenticationException) {
                return redirect()->route('login');
            }

            // For CustomException, usually redirect back with error message
            if ($exception instanceof CustomException) {
                return redirect()->back()->with('error', $exception->getMessage());
            }

            // Redirect back for 403 ONLY if not GET request (e.g. form submit failed auth)
            // If GET (view page), let it proceed to show Error 403 page.
            if (!$request->isMethod('get') && ($exception instanceof AccessDeniedHttpException || $exception instanceof AuthorizationException)) {
                return redirect()->back()->with('error', $exception->getMessage());
            }

            // 3. Handle Inertia Error Pages (403, 404, 500, 503)
            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                $status = $exception->getStatusCode();
                if (in_array($status, [403, 404, 500, 503])) {
                    return \Inertia\Inertia::render('Error', ['status' => $status])
                        ->toResponse($request)
                        ->setStatusCode($status);
                }
            }
        });
    })->create();
