<?php

namespace App\Repositories\User;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * @param string $email
     * @return User|null
     */
    public function getByEmail($email);

    /**
     * @param array $userData
     * [
     *  'name',
     *  'email'
     *  'password',
     * ]
     * @return mixed
     */
    public function create($userData);
}
