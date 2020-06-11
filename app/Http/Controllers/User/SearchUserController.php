<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\SearchUserRequest;
use App\Models\User;
use App\Traits\ApiResponser;

class SearchUserController extends Controller
{
    use ApiResponser;
    
    public function search(SearchUserRequest $request)
    {
        $name = $request->name;        
        // TODO: crete SearchUserService 
        $users = User::where('name', 'like', '%' . $name . '%')->take(5)->get();    
        
        //TODO: Create transform layer for user
        return $this->successResponseWithData($users);        
    }
}
