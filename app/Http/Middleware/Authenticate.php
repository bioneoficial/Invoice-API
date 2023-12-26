<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class Authenticate extends Middleware
{

    /**
     * Handle an unauthenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $guards
     * @throws \Illuminate\Validation\UnauthorizedException
     */
    protected function unauthenticated($request, array $guards)
    {
        if ($request->expectsJson()) {
            response()->json(['message' => 'Unauthenticated.'], 401);
        } else {
            // Put your redirection logic here if not expecting Json.
            // As it's an API, it mostly likely expects Json,
            // but it's here in case you have parts of your application that don't.
        }
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            throw new UnauthorizedException('Unauthenticated.');
        }
        return 'redirecionado';
    }
}
