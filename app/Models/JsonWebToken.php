<?php


namespace App\Models;


use Firebase\JWT\JWT;

class JsonWebToken
{
    const ACCESS_TOKEN_LIFE_TIME = 60 * 60;
    const ACCESS_TOKEN_ISS = 'debtor-jwt-access';
    const REFRESH_TOKEN_LIFE_TIME = 24 * 60 * 60;
    const REFRESH_TOKEN_ISS = "debtor-jwt-refresh";

    /**
     * @param User $user
     * @return array
     */
    public static function generateJWTTokensForUser(User $user)
    {
        $access_token = [
            'iss' => self::ACCESS_TOKEN_ISS,
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + JsonWebToken::ACCESS_TOKEN_LIFE_TIME,
        ];

        $refresh_token = [
            'iss' => self::REFRESH_TOKEN_ISS,
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + self::REFRESH_TOKEN_LIFE_TIME,
        ];

        return [
            'accessToken' => JWT::encode($access_token, config('api.jwt.secret')),
            'refreshToken' => JWT::encode($refresh_token, config('api.jwt.secret')),
        ];
    }
}
