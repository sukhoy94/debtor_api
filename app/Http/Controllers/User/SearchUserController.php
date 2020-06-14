<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\SearchUserRequest;
use App\Models\User;
use App\Services\SearchUserService;
use App\Traits\ApiResponser;
use App\ValueObjects\UserFilters;

class SearchUserController extends Controller
{
    use ApiResponser;
    
    private $searchUserService;
    
    public function __construct(SearchUserService $service)
    {
        $this->searchUserService = $service;        
    }
    
    public function search(SearchUserRequest $request)
    {
        $name = $request->name; 
        $userFilters = new UserFilters(['name' => $name,]);      
        
        $users = $this->searchUserService->searchUsersByFilters($userFilters);
        
        //TODO: Create transform layer for user
        return $this->successResponse($users);        
    }
}
