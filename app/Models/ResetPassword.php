<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model
{
    const RESET_PASSWORD_TOKEN_DURATION_IN_HOURS = 24;
}
