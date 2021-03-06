<?php


namespace App\Http\Requests\User;


use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|unique:users',
            'password' => 'required_with:passwordConfirmation|same:passwordConfirmation|min:8',
            'name' => 'required|min:3|max:50',
            'passwordConfirmation' => 'required',
        ];
    }
}
