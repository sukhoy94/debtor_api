<?php


namespace App\Repositories\User;


use App\Models\User;

class UserEloquentRepository implements UserRepositoryInterface
{
    /**
     * @param string $email
     * @return User|null
     */
    public function getByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    /**
     * @param array $userData
     * @return mixed|void
     */
    public function create($userData)
    {
        User::create($userData);
    }
}
