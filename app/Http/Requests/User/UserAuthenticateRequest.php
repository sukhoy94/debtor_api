<?php


namespace App\Http\Requests\User;


use Illuminate\Foundation\Http\FormRequest;

class UserAuthenticateRequest extends FormRequest
{
    /**
     * @var mixed
     */
    private $email;
    /**
     * @var mixed
     */
    private $password;
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }
}
