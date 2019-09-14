<?php

namespace App\Repositories\User;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * @param string $email
     * @return User|null
     */
    public function getUserByEmail($email);
}
