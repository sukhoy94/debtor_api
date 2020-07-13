<?php


namespace Tests\Unit\Services\Auth;


use App\Exceptions\Auth\RefreshTokenNotProvidedException;
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
        $dummyEmail = 'dummy_lol@test123@test.testdummy';        
        $this->expectException(\Exception::class);
        
        $this->authService->getUser($dummyEmail);
    }
    
    
    /**
     * vendor\bin\phpunit --filter refresh_token_throws_RefreshTokenNotProvidedException_if_no_token_provided tests/Unit/Services/Auth/AuthServiceTest.php
     * @test
    */
    public function refresh_token_throws_RefreshTokenNotProvidedException_if_no_token_provided()
    {
        $this->expectException(RefreshTokenNotProvidedException::class);
        $this->authService->refreshToken('');
    }
            
}