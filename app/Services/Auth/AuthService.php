<?php


namespace App\Services\Auth;


use App\Exceptions\Auth\InvalidTokenException;
use App\Exceptions\Auth\InvalidTokenIssuerException;
use App\Exceptions\Auth\RefreshTokenNotProvidedException;
use App\Exceptions\Auth\TokenExpiredException;
use App\Exceptions\Auth\UserNotFoundForTokenException;
use App\Models\JsonWebToken;
use App\Models\User;
use App\Repositories\User\UserEloquentRepository;
use App\Traits\ApiResponser;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;


abstract class AuthService
{
    use ApiResponser;
    
    /**
     * @param string $email
     * @param string $password
     * 
     * @return array
     * @throws \Exception
     */
    public function authenticate(string $email, string $password): array
    {
        $user = $this->getUser($email);
        
        if ($this->isPasswordCorrect($password, $user->password)) {
            return JsonWebToken::generateJWTTokensForUser($user);
        }

        throw new \Exception(Lang::get('info.password_wrong'), Response::HTTP_UNPROCESSABLE_ENTITY);      
    }
    
    /**
     * @param string $email
     * @return \App\Models\User
     * @throws \Exception
     */
    public function getUser(string $email): \App\Models\User
    {
        
        $repository = new UserEloquentRepository();
        
        try {
            $user = $repository->getByEmail($email);
        } catch (\Exception $exception) {
            throw new \Exception(Lang::get('info.email_does_not_exist'), Response::HTTP_NOT_FOUND);
        }        
        
        return $user;
    }
    
    
    /**
     * @param String $token
     * @return array
     *
     * @throws InvalidTokenException
     * @throws InvalidTokenIssuerException
     * @throws RefreshTokenNotProvidedException
     * @throws TokenExpiredException
     * @throws UserNotFoundForTokenException
     */
    public function refreshToken(String $token): array
    {    
        if (!$token) {
            throw new RefreshTokenNotProvidedException(Lang::get('info.token_not_provided'),Response::HTTP_UNAUTHORIZED);
        }
    
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {
            throw new TokenExpiredException(Lang::get('info.token_is_expired'), Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            throw new InvalidTokenException(Lang::get('info.invalid_token'), Response::HTTP_UNAUTHORIZED);
        }
    
        if ($credentials->iss !== JsonWebToken::REFRESH_TOKEN_ISS) {
            throw new InvalidTokenIssuerException(Lang::get('info.invalid_token'), Response::HTTP_UNAUTHORIZED);
        }
    
        $user = User::find($credentials->sub);
        
        if (!$user) {
            throw new UserNotFoundForTokenException(Lang::get('info.invalid_token'), Response::HTTP_UNAUTHORIZED);             
        }
        
        return JsonWebToken::generateJWTTokensForUser($user);    
    }
    
    
    /**
     * logic of this function is different for each environment
     * 
     * @param string $passwordFromRequest
     * @param string $userPasswordFromDB
     * @return bool
     */
    abstract public function isPasswordCorrect(string $passwordFromRequest, string $userPasswordFromDB): bool;
}