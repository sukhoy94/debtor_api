<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserAuthenticateRequest;
use App\Models\JsonWebToken;
use App\Repositories\User\UserEloquentRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Auth\AuthServiceDev;
use App\Services\Auth\AuthServiceFactory;
use App\Traits\ApiResponser;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Psy\Util\Json;
use Symfony\Component\HttpFoundation\Response;
use Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponser;


    private $userRepository;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->userRepository = $repository;
    }

    
    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @param UserAuthenticateRequest $request
     * @return mixed
     */
    public function authenticate(UserAuthenticateRequest $request)
    {
        $authService = AuthServiceFactory::create();
        try {
            $tokens = $authService->authenticate($request->email, $request->password);    
            return $this->successResponseWithData($tokens);
        } catch (\Exception $exception) {
            return $this->errorResponseWithMessage($exception->getMessage(), $exception->getCode());            
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function refreshToken(Request $request)
    {
        $token = $request->token;
    
        if (!$token) {
            return $this->errorResponseWithMessage(Lang::get('info.token_not_provided'), Response::HTTP_UNAUTHORIZED);
        }
    
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {
            return $this->errorResponseWithMessage(Lang::get('info.token_is_expired'), Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return $this->errorResponseWithMessage('An error while decoding token.', Response::HTTP_UNAUTHORIZED);
        }
    
        if ($credentials->iss !== JsonWebToken::REFRESH_TOKEN_ISS) {
            return $this->errorResponseWithMessage('An error while decoding token.', Response::HTTP_UNAUTHORIZED);
        }
    
        $user = User::find($credentials->sub);
    
        if ($user) {
            $tokens = JsonWebToken::generateJWTTokensForUser($user);
            return $this->successResponseWithData($tokens);
        } else {
            return $this->errorResponseWithMessage('Invalid token', Response::HTTP_UNAUTHORIZED);
        }
    }
}
