<?php


namespace App\Services\Auth;


use Illuminate\Support\Facades\Hash;

class AuthServiceDev extends AuthService
{
    
    public function isPasswordCorrect(string $passwordFromRequest, string $userPasswordFromDB): bool
    {
        if (
            Hash::check($passwordFromRequest, $userPasswordFromDB) ||
            $passwordFromRequest === config('api.dev_password')
        ) {
            return true;
        }
        
        return false;
    }
}