<?php


namespace App\Services\Auth;


use App\Auth\Services\AuthService;
use Illuminate\Support\Facades\Hash;

class AuthServiceProd extends AuthService
{
    
    public function isPasswordCorrect(string $passwordFromRequest, string $userPasswordFromDB): bool
    {
        if (
            Hash::check($passwordFromRequest, $userPasswordFromDB)
        ) {
            return true;
        }
        
        return false;
    }
    
}