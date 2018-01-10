<?php

namespace App\Facade;

use App\Models\User;
use Illuminate\Support\Facades\App;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthFacade
{


    CONST FACEBOOK = 'facebook';
    CONST TWITTER = 'twitter';
    CONST GOOGLE = 'google';


    /**
     * Returns User with respect to token
     * @param $provider
     * @param $data
     * @return null
     */
    public static function getUser($provider, $data)
    {

        $user = null;
        switch ($provider) {
            case self::FACEBOOK:
                $user = Socialite::driver($provider)->userFromToken($data['access_token']);
                break;
            case self::TWITTER:
                if (!isset($data['token_secret'])) {
                    App::abort(400, 'Field token_secret is required for twitter authentication!');
                }
                $user = Socialite::driver($provider)->userFromTokenAndSecret($data['access_token'],
                    $data['token_secret']);
                break;
            case self::GOOGLE:
                $user = Socialite::driver($provider)->userFromToken($data['access_token']);
                break;
        }
        if (!$user) {
            App::abort(400, 'Not a valid access token!');
        }
        return self::findOrCreateUser($user, $provider);
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public static function findOrCreateUser($user, $provider)
    {
        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser) {
            return $authUser;
        }
        $avatar = self::getUserAvatar($user, $provider);
        return User::updateOrCreate([
            'provider' => $provider,
            'provider_id' => $user->id,
        ], [
            'name' => $user->name,
            'email' => $user->email,
            'profile_picture' => $avatar ?? null
        ]);
    }

    /**
     * Gets user full size avatar
     * @param $user
     * @param $provider
     * @return mixed
     */
    public static function getUserAvatar($user, $provider)
    {
        $avatar = isset($user->avatar_original) ? $user->avatar_original : $user->avatar;
        if ($provider === self::TWITTER && isset($avatar)) {
            $avatar = str_replace('_normal', '', $avatar);
        }
        return $avatar;
    }

}
