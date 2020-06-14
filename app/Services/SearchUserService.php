<?php


namespace App\Services;


use App\Models\User;
use App\Transformers\UserTransformer;
use App\ValueObjects\UserFilters;

class SearchUserService
{
    private $limit = 15;
    
    public function searchUsersByFilters(UserFilters $userFilters)
    {
        $usersPaginated = User::where([
            ['name', 'like', '%' . $userFilters->getName() . '%']
        ])->paginate($this->limit);
        
        
        $usersTransformed = collect($usersPaginated->getCollection()->transformWith(new UserTransformer()));
        $usersPaginated->setCollection(collect($usersTransformed->get('data')));
        
        return $usersPaginated;
    }
}