<?php


namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateForgetPasswordLinkRequest;
use App\Services\ForgetPasswordService;
use App\Traits\ApiResponser;

class ForgetPasswordController extends Controller
{
    use ApiResponser;
    private $forgetPasswordService;

    public function __construct(ForgetPasswordService $forgetPasswordService)
    {
        $this->forgetPasswordService = $forgetPasswordService;
    }

    public function createForgetPasswordLink(CreateForgetPasswordLinkRequest $request)
    {
        $this->forgetPasswordService->createForgetPasswordLink($request->email);
        return $this->successResponse('Token created successfully');
    }
}
