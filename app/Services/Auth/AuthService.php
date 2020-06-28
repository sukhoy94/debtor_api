<?php


namespace App\Services\Auth;


use App\Models\JsonWebToken;
use App\Repositories\User\UserEloquentRepository;
use App\Traits\ApiResponser;
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
     * @param string $emai
     * @return \App\Models\User
     * @throws \Exception
     */
    public function getUser(string $emai): \App\Models\User
    {
        
        $repository = new UserEloquentRepository();
        
        try {
            $user = $repository->getByEmail($emai);
        } catch (\Exception $exception) {
            throw new \Exception(Lang::get('info.email_does_not_exist'), Response::HTTP_NOT_FOUND);
        }        
        
        return $user;
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