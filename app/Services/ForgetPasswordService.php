<?php


namespace App\Services;


use App\Models\ResetPassword;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

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
        $previousResetPasswordIdForUser = $user->resetPasswords ? $user->resetPasswords->id : null;
        $resetPassword = new ResetPassword();
        $resetPassword->user_id = $user->id;
        $resetPassword->token = ResetPassword::generateToken($user->id);
        $result = $resetPassword->save();

        if ($result && $previousResetPasswordIdForUser) {
            $this->moveResetPasswordRequestToArchive($previousResetPasswordIdForUser);
        }
    }

    public function moveResetPasswordRequestToArchive($previousResetPasswordIdForUser)
    {
        $resetPassword = ResetPassword::find($previousResetPasswordIdForUser);

        DB::table('reset_passwords_arhive')->insert([
            [
                'id' => $resetPassword->id,
                'user_id' => $resetPassword->user_id,
                'token' => $resetPassword->token,
                'valid_until' => $resetPassword->valid_until,
                'created_at' => $resetPassword->created_at,
                'updated_at' => $resetPassword->updated_at,
            ],
        ]);

        $resetPassword->delete();
    }
}
