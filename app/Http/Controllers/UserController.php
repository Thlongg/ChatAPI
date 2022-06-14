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

    public function get_users()
    {
        return $this->userService->getAllUser();
    }

    public function search_by_name(Request $request)
    {
        return $this->userService->search($request);
    }

    public function update_user_avatar(Request $request)
    {
        return $this->userService->change_image($request);
    }

    public function change_user_name(Request $request)
    {
        return $this->userService->change_name($request);
    }
}
