<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    /**
     * @param string $email
     * @throws ModelNotFoundException
     * @return User
     */
    public function getUserByEmail($email);
}
