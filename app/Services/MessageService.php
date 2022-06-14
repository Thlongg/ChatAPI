<?php

namespace App\Sevices;

use App\Repositories\UserRepository;

class MessageService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUser()
    {
        $a = $this->userRepository->getDataUser()->all();
        dd($a);
    }
}