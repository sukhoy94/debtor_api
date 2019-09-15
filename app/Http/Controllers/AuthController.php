<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserAuthenticateRequest;
use App\Repositories\User\UserEloquentRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\UserService;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    const ACCESS_TOKEN_LIFE_TIME = 60*60;
    const ACCESS_TOKEN_ISS = 'debtor-jwt-access';
    const REFRESH_TOKEN_LIFE_TIME = 24*60*60;
    const REFRESH_TOKEN_ISS = "debtor-jwt-refresh";

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
            'iss' => self::ACCESS_TOKEN_ISS,
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + self::ACCESS_TOKEN_LIFE_TIME,
        ];

        $refresh_token = [
            'iss' => self::REFRESH_TOKEN_ISS,
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + self::REFRESH_TOKEN_LIFE_TIME,
        ];

        return [
            'access_token' => JWT::encode($access_token, env('JWT_SECRET')),
            'refresh_token' => JWT::encode($refresh_token, env('JWT_SECRET')),
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
        $user = $this->userRepository->getUserByEmail($request->input('email'));

        if (!$user) {
            return response()->json([
                'error' => 'Email does not exist.'
            ], Response::HTTP_BAD_REQUEST);
        }

        if (Hash::check($request->input('password'), $user->password)) {
            $tokens = $this->generateJWTTokensForUser($user);
            return response()->json([
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
            ], Response::HTTP_OK);
        }

        return response()->json([
            'error' => 'Email or password is wrong.'
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function refreshToken(Request $request)
    {
        $token = $request->token;

        if(!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'error' => 'Token not provided.'
            ], Response::HTTP_UNAUTHORIZED);
        }
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch(ExpiredException $e) {
            return response()->json([
                'error' => 'Provided token is expired.'
            ], Response::HTTP_UNAUTHORIZED);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'An error while decoding token.'
            ], Response::HTTP_UNAUTHORIZED);
        }


        if ($credentials->iss !== 'debtor-jwt-refresh') {
            return response()->json([
                'error' => 'An error while decoding token.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::find($credentials->sub);

        if ($user) {
            $tokens = $this->generateJWTTokensForUser($user);
            return response()->json([
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
            ]);
        }
        else {
            return response()->json([
                'error' => 'Invalid token'
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
