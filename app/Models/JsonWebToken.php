<?php


namespace App\Models;


class JsonWebToken
{
    const ACCESS_TOKEN_LIFE_TIME = 60*60;
    const ACCESS_TOKEN_ISS = 'debtor-jwt-access';
    const REFRESH_TOKEN_LIFE_TIME = 24*60*60;
    const REFRESH_TOKEN_ISS = "debtor-jwt-refresh";
}
