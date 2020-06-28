<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DebugController extends Controller
{
    public function __invoke()
    {
        $user = User::get()->first();
        dd(config('api.dev_password'));            
    }
}
