<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Create a new token.
     *
     * @param User $user
     * @return array
     */
    protected function jwt(User $user)
    {
        $access_token = [
            'iss' => "debtor-jwt-access", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60 * 60 // Expiration time
        ];

        $refresh_token = [
            'iss' => "debtor-jwt-refresh", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60 * 60 * 60 // Expiration time
        ];


        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return [
            'access_token' => JWT::encode($access_token, env('JWT_SECRET')),
            'refresh_token' => JWT::encode($refresh_token, env('JWT_SECRET')),
        ];
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @param User $user
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(User $user)
    {
        $this->validate($this->request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $this->request->input('email'))->first();

        if (!$user) {
            return response()->json([
                'error' => 'Email does not exist.'
            ], Response::HTTP_BAD_REQUEST);
        }
        if (Hash::check($this->request->input('password'), $user->password)) {
            $tokens = $this->jwt($user);
            return response()->json([
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
            ], Response::HTTP_OK);
        }
        return response()->json([
            'error' => 'Email or password is wrong.'
        ], Response::HTTP_BAD_REQUEST);
    }

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
            ], Response::HTTP_BAD_REQUEST);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'An error while decoding token.'
            ], Response::HTTP_BAD_REQUEST);
        }


        if ($credentials->iss !== 'debtor-jwt-refresh') {
            return response()->json([
                'error' => 'An error while decoding token.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = User::find($credentials->sub);

        if ($user) {
            $tokens = $this->jwt($user);
            return response()->json([
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
            ]);
        }
        else {
            return response()->json([
                'error' => 'Invalid token'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
