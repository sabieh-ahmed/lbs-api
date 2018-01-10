<?php

namespace App\Http\Controllers\Api;

use App\Facade\SocialAuthFacade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;

class AuthController extends Controller
{



    /**
     * @apiGroup           Authentication (Social)
     * @apiName            Facebook
     *
     * @api                {GET} /social/auth/facebook?access_token=your_token&device_token=your_one_signal_device_token Facebook
     * @apiDescription     Login with facebook using access token for generating test tokens visit: https://developers.facebook.com/tools/explorer/
     * @apiVersion         1.0.0
     *
     * @apiParam           {String}  access_token user access token
     * @apiParam           {String}  device_token (optional) one signal player id
     *
     * @apiSuccessExample  {json}       Success-Response:
     * HTTP/1.1 200 OK
     * {
     * "token_type": "Bearer",
     * "expires_in": 315360000,
     * "access_token": "eyJ0eXAiOiJKV1QiLCJhbG...",
     * "refresh_token": "Oukd61zgKzt8TBwRjnasd..."
     * }
     */

    /**
     * @apiGroup           Authentication (Social)
     * @apiName            Google
     *
     * @api                {GET} /social/auth/google?access_token=your_token&device_token=your_one_signal_device_token Google
     * @apiDescription     Login with google using access token
     * @apiVersion         1.0.0
     *
     * @apiParam           {String}  access_token user access token
     * @apiParam           {String}  device_token (optional) one signal player id
     *
     * @apiSuccessExample  {json}       Success-Response:
     * HTTP/1.1 200 OK
     * {
     * "token_type": "Bearer",
     * "expires_in": 315360000,
     * "access_token": "eyJ0eXAiOiJKV1QiLCJhbG...",
     * "refresh_token": "Oukd61zgKzt8TBwRjnasd..."
     * }
     */

    /**
     * @apiGroup           Authentication (Social)
     * @apiName            Twitter
     *
     * @api                {GET} /social/auth/twitter?access_token=your_token&token_secret=your_secret&device_token=your_one_signal_device_token Twitter
     * @apiDescription     Login with twitter using access token
     * @apiVersion         1.0.0
     *
     * @apiParam           {String}  access_token user access token
     * @apiParam           {String}  token_secret user access token secret
     * @apiParam           {String}  device_token (optional) one signal player id
     *
     * @apiSuccessExample  {json}       Success-Response:
     * HTTP/1.1 200 OK
     * {
     * "token_type": "Bearer",
     * "expires_in": 315360000,
     * "access_token": "eyJ0eXAiOiJKV1QiLCJhbG...",
     * "refresh_token": "Oukd61zgKzt8TBwRjnasd..."
     * }
     */
    public function socialAuth(Request $request, $provider)
    {

        $data = $this->validate($request, [
            'access_token' => 'required',
            'token_secret' => '',
            'device_token' => ''
        ]);
        $user = SocialAuthFacade::getUser($provider, $data);
        $token = JWTAuth::fromUser($user, ['user' => $user]);
        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $token,
            'expires_in' => config('jwt.ttl') * 60 // config value is in minutes
        ]);
    }

}
