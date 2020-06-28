<?php


namespace App\Services\Auth;


use Illuminate\Support\Facades\App;

class AuthServiceFactory
{
    public static function create()
    {
        if (App::environment('local')) {
            return new AuthServiceDev();
        }
        
        return new AuthServiceProd();
    }
}