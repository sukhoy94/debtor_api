<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\CreateActivationLinkRequest;
use App\Http\Requests\User\UserRegisterRequest;
use App\Models\JsonWebToken;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\EmailService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\ApiResponser;

class UserController extends Controller
{
    use ApiResponser;

    private $userRepository;
    private $emailService;

    /**
     * UserController constructor.
     * @param UserRepositoryInterface $repository
     * @param EmailService $emailService
     */
    public function __construct(UserRepositoryInterface $repository, EmailService $emailService)
    {
        $this->userRepository = $repository;
        $this->emailService = $emailService;
    }

    /**
     * @param UserRegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRegisterRequest $request)
    {
        $this->userRepository->create([
            'email' => $request->email,
            'password' => $request->password,
            'name' => $request->name,
        ]);

        try {
            $user = $this->userRepository->getByEmail($request->email);
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse(Lang::get('info.default_error'), Response::HTTP_NOT_FOUND);
        }
        $this->emailService->sendVerificationEmail($user);
        $tokens = JsonWebToken::generateJWTTokensForUser($user);

        return $this->successResponseWithData(
            $tokens,
            Response::HTTP_CREATED
        );
    }

    /**
     * @param string $verificationToken
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function verifyUser($token)
    {
        $user = $this->userRepository->getByVerificationToken($token);
        $user->markEmailAsVerified();

        return view('users.verify', ['success' => (bool)$user->email_verified_at]);
    }

    /**
     * @param CreateActivationLinkRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createActivationLink(CreateActivationLinkRequest $request)
    {
        $user = $this->userRepository->getByEmail($request->email);

        if ($user->hasVerifiedEmail()) {
            return $this->errorResponse(Lang::get('info.email_already_verified'), Response::HTTP_CONFLICT);
        }

        $user->email_verification_token = User::generateEmailToken($user->email);
        $user->save();

        $this->emailService->sendVerificationEmail($user);
        return $this->successResponse(Lang::get('info.verification_link_sended'));
    }

    public function getAuthenticatedUser(Request $request)
    {
        return 'Hello authenticated user!';
    }
}
