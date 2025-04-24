<?php

namespace App\Http\Controllers;

use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    )
    {
    }

    public function index()
    {
        //
    }
}
