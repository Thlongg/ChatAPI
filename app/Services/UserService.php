<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Exception;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUser()
    {
        try {
            $users = $this->userRepository->getDataUser()->all();
            return response()->json([
                'success' => true,
                'list_user' => $users
            ]);
        } catch (Exception $e) {
            return response()->json([
                'msg' => $e->getMessage(),
            ]);
        }
    }
}