<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmailQueueController extends Controller
{
    public function run()
    {
        DB::table('jobs')->update(['available_at' => time()]);
    }
}
