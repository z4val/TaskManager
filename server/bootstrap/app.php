<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function (Request $request) {
            return $request->is('api/*'); // Todas las rutas bajo /api devuelven json
        });

        // Manejar errores específicos de JWT
        $exceptions->render(function (TokenExpiredException $e, Request $request) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token expired. Please, log in again.'
            ], 401);
        });

        $exceptions->render(function (TokenInvalidException $e, Request $request) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token invalid. Access denied.'
            ], 401);
        });

        $exceptions->render(function (JWTException $e, Request $request) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token not provided. Authentication required.'
            ], 401);
        });

        $exceptions->render(function (TokenBlacklistedException $e, Request $request) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token inválido. Ha sido refrescado o revocado.'
            ], 401);
        });

        $exceptions->render(function (UnauthorizedHttpException $e, Request $request) {
            $message = $e->getMessage();

            // Personalizar según el mensaje original
            if (str_contains($message, 'Token not provided')) {
                return response()->json([
                    'message' => 'Token not provided'
                ], 401);
            }

            if (str_contains($message, 'Token has expired')) {
                return response()->json([
                    'message' => 'Token expired'
                ], 401);
            }

            if (str_contains($message, 'Token Signature could not be verified')) {
                return response()->json([
                    'message' => 'Token invalid'
                ], 401);
            }

            if (str_contains($message, 'The token has been blacklisted')) {
                return response()->json([
                    'message' => 'Token has been blacklisted'
                ], 401);
            }

            // Mensaje genérico
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        });
    })->create();
