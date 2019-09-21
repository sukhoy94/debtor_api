<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\UserRegisterRequest;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\EmailService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private $userRepository;
    private $emailService;

    public function __construct(UserRepositoryInterface $repository, EmailService $emailService) {
        $this->userRepository = $repository;
        $this->emailService = $emailService;
    }

    public function store(UserRegisterRequest $request) {

        $this->userRepository->create([
            'email' => $request->email,
            'password' => $request->password,
            'name' => $request->name,
        ]);

        try {
            $user = $this->userRepository->getUserByEmail($request->email);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'message' => 'Some error had place, please try again',
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->emailService->sendVerificationEmail($user);

        return response()->json([
            'message' => 'User created successfully',
        ], Response::HTTP_OK);
    }
}
