<?php

namespace App\Http\Validators;

use Illuminate\Support\Facades\Validator;

class AuthValidator
{

    /**
     * Registration Validator
     * @param $data
     */
    public static function register($data)
    {
        Validator::make($data, [
            'name' => 'required|string|max:70',
            'email' => 'required|string|email|max:70|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ])->validate();
    }


    /**
     * Login Validator
     * @param $data
     */
    public static function login($data)
    {
        Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required|string'
        ])->validate();
    }

    /**
     * Forgot password validator
     * @param $data
     */
    public static function forgotPassword($data)
    {
        Validator::make($data, [
            'email' => 'required|exists:users,email'
        ])->validate();
    }


    /**
     * Change Password Request Validator
     * @param $data
     */
    public static function changePassword($data)
    {
        Validator::make($data, [
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ])->validate();
    }


    /**
     * Social Auth Validator
     * @param $data
     */
    public static function socialAuth($data)
    {
        Validator::make($data, [
            'access_token' => 'required',
        ])->validate();
    }
}
