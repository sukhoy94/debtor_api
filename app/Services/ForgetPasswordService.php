<?php


namespace App\Services;


use App\Models\ResetPassword;
use App\Repositories\User\UserRepositoryInterface;
use Carbon\Carbon;

class ForgetPasswordService
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createForgetPasswordLink($userEmail)
    {
        $user = $this->userRepository->getByEmail($userEmail);

        $resetPassword = new ResetPassword();
        $resetPassword->user_id = $user->id;
        $resetPassword->token = ResetPassword::generateToken($user->id);
        $resetPassword->save();
    }
}
