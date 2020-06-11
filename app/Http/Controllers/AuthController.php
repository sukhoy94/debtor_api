<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserAuthenticateRequest;
use App\Models\JsonWebToken;
use App\Repositories\User\UserEloquentRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Traits\ApiResponser;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\JsonResponse;
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
     * Create a pair of 2 tokens for user: access and refresz token
     *
     * @param User $user
     * @return array
     */
    protected function generateJWTTokensForUser(User $user)
    {
        $access_token = [
            'iss' => JsonWebToken::ACCESS_TOKEN_ISS,
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + JsonWebToken::ACCESS_TOKEN_LIFE_TIME,
        ];

        $refresh_token = [
            'iss' => JsonWebToken::REFRESH_TOKEN_ISS,
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + JsonWebToken::REFRESH_TOKEN_LIFE_TIME,
        ];

        return [
            'access_token' => JWT::encode($access_token, config('api.jwt.secret')),
            'refresh_token' => JWT::encode($refresh_token, config('api.jwt.secret')),
        ];
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @param UserAuthenticateRequest $request
     * @return mixed
     */
    public function authenticate(UserAuthenticateRequest $request)
    {
        try {
            $user = $this->userRepository->getByEmail($request->input('email'));
        } catch (\Exception $exception) {
            return $this->errorResponse(Lang::get('info.email_does_not_exist'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (Hash::check($request->input('password'), $user->password))
        {
            $tokens = JsonWebToken::generateJWTTokensForUser($user);
            return $this->successResponseWithData($tokens);
        }

        return $this->errorResponse(Lang::get('info.password_wrong'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function refreshToken(Request $request)
    {
        $token = $request->token;
        if (!$token)
        {
            return $this->errorResponse(Lang::get('info.token_not_provided'), Response::HTTP_UNAUTHORIZED);
        }
        dd($token);
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch(ExpiredException $e) {
            return $this->errorResponse(Lang::get('info.token_is_expired'), Response::HTTP_UNAUTHORIZED);
        } catch(\Exception $e) {
            return $this->errorResponse('An error while decoding token.', Response::HTTP_UNAUTHORIZED);
        }

        if ($credentials->iss !== JsonWebToken::REFRESH_TOKEN_ISS)
        {
            return $this->errorResponse('An error while decoding token.', Response::HTTP_UNAUTHORIZED);
        }

        $user = User::find($credentials->sub);

        if ($user)
        {
            $tokens = JsonWebToken::generateJWTTokensForUser($user);
            return $this->successResponseWithData($tokens);
        }
        else
        {
            return $this->errorResponse('Invalid token', Response::HTTP_UNAUTHORIZED);
        }
    }
}
