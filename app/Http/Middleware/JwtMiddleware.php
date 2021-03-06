<?php

namespace App\Http\Middleware;

use App\Models\JsonWebToken;
use App\Models\User;
use Closure;
use App\Traits\ApiResponser;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use http\Exception;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    use ApiResponser;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token)
        {
            return $this->errorResponseWithMessage('Authorization token not provided', Response::HTTP_UNAUTHORIZED);
        }

        try {
            $credentials = JWT::decode($token, config('api.jwt.secret'), ['HS256']);
        } catch(ExpiredException $e) {
            return $this->errorResponseWithMessage('Provided token is expired', Response::HTTP_UNAUTHORIZED);
        } catch(\Exception $e) {
            return $this->errorResponseWithMessage('An error while decoding token', Response::HTTP_UNAUTHORIZED);
        }

        if ($credentials->iss !== JsonWebToken::ACCESS_TOKEN_ISS)
        {
            return $this->errorResponseWithMessage('An error while decoding token', Response::HTTP_UNAUTHORIZED);
        }

        $user = User::find($credentials->sub);

        if (!$user)
        {
            return $this->errorResponseWithMessage('No such user', Response::HTTP_NOT_FOUND);
        }

        $request->currentUser = $user;
        return $next($request);
    }
}
