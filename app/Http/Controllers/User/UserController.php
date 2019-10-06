<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\CreateActivationLinkRequest;
use App\Http\Requests\User\UserRegisterRequest;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\EmailService;
use App\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private $userRepository;
    private $emailService;
    private $userService;

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
                'message' => Lang::get('info.default_error'),
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->emailService->sendVerificationEmail($user);

        return response()->json([
            'message' => Lang::get('info.user_created_successfully'),
        ], Response::HTTP_OK);
    }

    /**
     * @param string $verificationToken
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function verifyUser($token) {
        $user = $this->userRepository->getByVerificationToken($token);
        $user->markEmailAsVerified();

        return view('users.verify', ['success' => (bool)$user->email_verified_at]);
    }

    public function createActivationLink(CreateActivationLinkRequest $request) {
        $user = $this->userRepository->getUserByEmail($request->email);

        if ($user->hasVerifiedEmail()) {
            return \response()->json(['message' => Lang::get('info.email_already_verified'), 'code' => Response::HTTP_CONFLICT],  Response::HTTP_CONFLICT);
        }

        $user->email_verification_token = User::generateEmailToken();
        $user->save();

        $this->emailService->sendVerificationEmail($user);
        return response()->json([
            'message' => Lang::get('info.verification_link_sended'),
        ], Response::HTTP_OK);
    }
}
