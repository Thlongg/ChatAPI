<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUsers()
    {
        return $this->userService->getAllUser();
    }

    public function searchByName(Request $request)
    {
        return $this->userService->search($request);
    }

    public function updateUserAvatar(Request $request)
    {
        return $this->userService->changeImage($request);
    }

    public function changeUserName(Request $request)
    {
        return $this->userService->changeName($request);
    }
}
