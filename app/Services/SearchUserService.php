<?php


namespace App\Services;


use App\Models\User;
use App\ValueObjects\UserFilters;

class SearchUserService
{
    public function searchUsersByFilters(UserFilters $userFilters)
    {
        $users = User::where([
            ['name', 'like', '%' . $userFilters->getName() . '%']
        ])->paginate(15);

        return $users;
    }
}