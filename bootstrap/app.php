<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        then: fn() => require __DIR__.'/../routes/install.php'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->throttleApi();

        $middleware->web(
            prepend: [
                \Illuminate\Routing\Middleware\ThrottleRequests::class.':web',
            ],
            append: [
                \App\Http\Middleware\ImplementPageSpeed::class,
            ]
        );

        $middleware->append([
            \Spatie\Csp\AddCspHeaders::class,
            \App\Http\Middleware\AddSecureHeaderRequest::class,
        ]);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'verification_email_access' => \App\Http\Middleware\VerificationEmailAccess::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'prevent_installation' => \App\Http\Middleware\PreventInstallationWhenInstalled::class,
            'prevent_invalid_registration' => \App\Http\Middleware\PreventInvalidRegistration::class,
        ]);

        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(fn () => route('dashboard.index'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function (Response $response, Throwable $exception, Request $request) {
            if (!$request->is('api/*')) {
                return (new \App\Http\Middleware\ImplementPageSpeed())
                    ->handle($request, fn ($req) => $response);
            }

            return match (true) {
            $exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException,
            $exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException =>
                response()->json([
                    'status' => 'error',
                    'message' => 'Resource not found',
                    'data' => null,
                ], 404),

            $exception instanceof \Illuminate\Auth\Access\AuthorizationException =>
                response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                    'data' => null,
                ], 403),

            $exception instanceof \Illuminate\Validation\ValidationException =>
                response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'data' => $exception->errors(),
                ], 422),

            $exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException =>
                response()->json([
                    'status' => 'error',
                    'message' => 'Method Not Allowed',
                    'data' => null,
                ], 405),

            $exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException =>
                response()->json([
                    'status' => 'error',
                    'message' => $exception->getMessage(),
                    'data' => null,
                ], $exception->getStatusCode()),

            default =>
                response()->json([
                    'status' => 'error',
                    'message' => 'An error occurred',
                    'data' => null,
                ], 500),
            };
        });
    })->create();
