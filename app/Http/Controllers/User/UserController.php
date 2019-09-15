<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\UserRegisterRequest;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $repository) {
        $this->userRepository = $repository;
    }

    public function store(UserRegisterRequest $request) {
        $user = $this->userRepository->create([
            'email' => $request->email,
            'password' => $request->password,
            'name' => $request->name,
        ]);

        if (!$user->id) {
            return response()->json([
                'message' => 'Some error had place, please try again',
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->sendVerificationEmail($user);

        return response()->json([
            'message' => 'User created successfully',
        ], Response::HTTP_OK);
    }

    public function verifyEmail() {
    }

    private function sendVerificationEmail(User $user) {
    }
}
