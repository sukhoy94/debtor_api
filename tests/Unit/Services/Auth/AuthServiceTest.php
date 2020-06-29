<?php


namespace Tests\Unit\Services\Auth;


use App\Services\Auth\AuthService;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{    
    protected $anonymousClassInstanceFromAuthServiceAbstract;
    
    
    public function setUp(): void
    {
        $this->anonymousClassInstanceFromAuthServiceAbstract = new class extends AuthService {    
            public function isPasswordCorrect(string $passwordFromRequest, string $userPasswordFromDB): bool
            {   
                return true;
            }
        };

    }
    
    /**
     * vendor\bin\phpunit --filter get_user_returns_instance_of_user_if_user_exists tests/Unit/Services/Auth/AuthServiceTest.php
     * @test
     */
    public function get_user_returns_instance_of_user_if_user_exists() 
    {
//        $this->assertTrue(1==1);
    }
        
}