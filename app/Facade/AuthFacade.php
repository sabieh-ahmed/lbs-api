<?php

namespace App\Facade;

use App\Helpers\ResponseCodes;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use JWTAuth;
use Laravel\Socialite\Facades\Socialite;

class AuthFacade
{

    /**
     * Registers User with Basic Email and Password
     * @param $data
     * @return array
     */
    public static function registerUser($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        $token = JWTAuth::fromUser($user, ['user' => $user]);
        return self::makeToken($token);
    }

    /**
     * Login
     * @param $data
     * @return mixed
     */
    public static function loginUser($data)
    {
        if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            abort(ResponseCodes::HTTP_UNAUTHORIZED, trans('auth.errors.invalid_credentials'));
        }
        $user = Auth::user();
        $token = JWTAuth::fromUser($user, ['user' => $user]);
        return self::makeToken($token);
    }


    /**
     * Forgot user password
     * @param $data
     * @return array
     */
    public static function forgotPassword($data)
    {
        Password::sendResetLink(['email' => $data['email']]);
        return ['message' => trans('auth.messages.forgot_password')];
    }

    /**
     * Logout current user
     */
    public static function logout()
    {
        Auth::logout();
        JWTAuth::invalidate(JWTAuth::getToken());
        return ['message' => trans('auth.messages.logout')];
    }

    /**
     * Changes User Password
     * @param $data
     * @return mixed
     */
    public static function changeUserPassword($data)
    {
        if (auth()->user()->getAuthPassword() !== null) {
            if (!Hash::check($data['old_password'], auth()->user()->getAuthPassword())) {
                abort(ResponseCodes::HTTP_NOT_ACCEPTABLE, trans('auth.errors.invalid_password'));
            }
        }
        $user = auth()->user();
        $user->password = Hash::make($data['password']);
        $user->save();
        return ['message' => trans('auth.messages.password_changed')];
    }

    /**
     * Returns User with respect to token
     * @param $provider
     * @param $data
     * @return null
     */
    public static function socialAuth($provider, $data)
    {

        $user = null;
        switch ($provider) {
            case 'facebook':
                $user = Socialite::driver($provider)->userFromToken($data['access_token']);
                break;
            case 'twitter':
                if (!isset($data['token_secret'])) {
                    abort(ResponseCodes::HTTP_BAD_REQUEST, trans('auth.errors.token_secret', ['network' => 'twitter']));
                }
                $user = Socialite::driver($provider)
                    ->userFromTokenAndSecret($data['access_token'], $data['token_secret']);
                break;
            case 'google':
                $user = Socialite::driver($provider)->userFromToken($data['access_token']);
                break;
        }
        if (!$user) {
            abort(ResponseCodes::HTTP_BAD_REQUEST, trans('auth.errors.invalid_access_token'));
        }
        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser) {
            $token = JWTAuth::fromUser($authUser, ['user' => $authUser]);
            return self::makeToken($token);
        }
        $avatar = isset($user->avatar_original) ? $user->avatar_original : $user->avatar;
        if ($provider === 'twitter' && isset($avatar)) {
            $avatar = str_replace('_normal', '', $avatar);
        }
        $newUser = User::updateOrCreate([
            'provider' => $provider,
            'provider_id' => $user->id,
        ], [
            'name' => $user->name,
            'email' => $user->email,
            'profile_picture' => $avatar ?? null
        ]);
        $token = JWTAuth::fromUser($newUser, ['user' => $newUser]);
        return self::makeToken($token);
    }

    /**
     * Generates token data
     * @param $token
     * @return array
     */
    private static function makeToken($token)
    {
        return [
            'token_type' => 'Bearer',
            'access_token' => $token,
            'expires_in' => config('jwt.ttl') * 60
        ];
    }
}

