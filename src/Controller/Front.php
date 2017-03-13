<?php

namespace App\Controller;

class Front
{
    public static function index($request, $response)
    {
        return $response->setContent('HELLO');
    }
}