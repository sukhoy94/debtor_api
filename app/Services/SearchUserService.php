<?php


namespace App\Services;


use App\Models\User;
use App\Transformers\UserTransformer;
use App\ValueObjects\UserFilters;

class SearchUserService
{
    private $limit = 15;
    
    
    /**
     * return paginated result of searching users
     * 
     * sample return result: 
     * [
            "current_page" => 1
            "data" => array:1 [
                0 => array:2 [
                    "id" => 2715
                    "name" => "Felicia Hodkiewicz II"
                ]
            ]
            "first_page_url" => "http://debtor_api.test/api/users/filters?page=1"
            "from" => 1
            "last_page" => 1
            "last_page_url" => "http://debtor_api.test/api/users/filters?page=1"
            "next_page_url" => null
            "path" => "http://debtor_api.test/api/users/filters"
            "per_page" => 15
            "prev_page_url" => null
            "to" => 1
            "total" => 1
        ]
     * 
     * 
     * 
     * @param UserFilters $userFilters
     * @return mixed
     */
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