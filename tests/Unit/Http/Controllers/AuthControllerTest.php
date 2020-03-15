<?php


namespace Tests\Unit\Http\Controllers;


use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    /**
     * @test
     */
    public function login_return_200_if_credentials_are_fine()
    {
        $response = $this->post('/api/auth/login', [
            'email' => 'andrii@jedai.com',
            'password' => 12345678,
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function success_login_returns_access_and_refresh_tokens_in_data_object()
    {
        $user = User::get()->first();

        $response = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => 12345678,
        ]);

        $this->assertArrayHasKey('accessToken', $response->json()['data']);
        $this->assertArrayHasKey('refreshToken', $response->json()['data']);
    }

    /**
     * @test
     */
    public function wrong_email_returns_422_status()
    {
        $response = $this->post('/api/auth/login', [
            'email' => 'wrong@wrong.wrong',
            'password' => 12345678,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function wrong_password_returns_422_status()
    {
        $user = User::get()->first();

        $response = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrong',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
