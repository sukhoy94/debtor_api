<?php

return [
    'jwt' => [
        'secret' => env('JWT_SECRET', 'DEFAULT_SECRET_KEY'),
    ],

   /*
   |--------------------------------------------------------------------------
   | Valid password for each user on development environment
   |--------------------------------------------------------------------------
   |
   | That password will be valid for any user on development environment
   |
   */
    'dev_password' => env('DEV_PASSWORD', 12345678),
];

