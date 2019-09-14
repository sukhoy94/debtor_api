<?php


namespace App\Repositories\User;


use App\Models\User;

class UserEloquentRepository implements UserRepositoryInterface
{
    /**
     * @param string $email
     * @return User|null
     */
    public function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }
}
