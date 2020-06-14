<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\SearchUserRequest;
use App\Models\User;
use App\Services\SearchUserService;
use App\Traits\ApiResponser;
use App\Transformers\UserTransformer;
use App\ValueObjects\UserFilters;

use League\Fractal;
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
        $userFilters = new UserFilters($request->all());      
        $users = $this->searchUserService->searchUsersByFilters($userFilters);
    
        return $this->successResponse($users);
    }
}
