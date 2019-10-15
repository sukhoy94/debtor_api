<?php


namespace App\Repositories\User;


use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserEloquentRepository implements UserRepositoryInterface
{
    /**
     * @param string $email
     * @return User|null
     */
    public function getByEmail($email) {
        return User::where('email', $email)->firstOrFail();
    }

    /**
     * @param array $userData
     * @return mixed|void
     */
    public function create($userData) {
        User::create($userData);
    }

    /**
     * @param string $token
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function getByVerificationToken($token) {
        return User::where(['email_verification_token' => $token])->firstOrFail();
    }
}
