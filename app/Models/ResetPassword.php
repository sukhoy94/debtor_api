<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ResetPassword extends Model
{
    const RESET_PASSWORD_TOKEN_DURATION_IN_HOURS = 24;

    public static function boot()
    {
        parent::boot();

        static::creating(function($item) {
            $item->attributes['valid_until'] = Carbon::now()
                ->addHours(self::RESET_PASSWORD_TOKEN_DURATION_IN_HOURS)
                ->format('Y-m-d H:i:s');
        });
    }

    public static function generateToken($userId)
    {
        return Str::random(8).md5($userId).md5(time());
    }
}
