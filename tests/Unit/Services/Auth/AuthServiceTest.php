<?php


namespace Tests\Unit\Services\Auth;


use App\Models\User;
use App\Services\Auth\AuthService;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{    
    protected $authService;
    
    
    public function setUp(): void
    {
        $this->authService = new class extends AuthService {    
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
        $userFromDB = User::get()->first(); 
        
        $this->assertInstanceOf(
            User::class,
            $this->authService->getUser($userFromDB->email)     
        );
    }
    
    /**
     * vendor\bin\phpunit --filter get_user_throws_exception_if_user_instance_not_exists tests/Unit/Services/Auth/AuthServiceTest.php
     * @test
     */
    public function get_user_throws_exception_if_user_instance_not_exists() 
    {
        $dummyEmail = 'lol@test123@test.test';        
        $this->expectException(\Exception::class);
        
        $this->authService->getUser($dummyEmail);
    }
            
}