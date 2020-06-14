<?php


namespace App\Services;


use App\Models\User;
use App\ValueObjects\UserFilters;

class SearchUserService
{
    private $limit = 15;
    
    public function searchUsersByFilters(UserFilters $userFilters)
    {
        $users = User::where([
            ['name', 'like', '%' . $userFilters->getName() . '%']
        ])->paginate($this->limit);

        return $users;
    }
}