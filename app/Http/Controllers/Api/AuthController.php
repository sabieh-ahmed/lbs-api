<?php

namespace App\Http\Controllers\Api;

use App\Facade\AuthFacade;
use App\Helpers\FractalResponseHelper;
use App\Helpers\ResponseCodes;
use App\Http\Controllers\Controller;
use App\Http\Transformers\AuthTokenTransformer;
use App\Http\Transformers\SuccessTransformer;
use App\Http\Validators\AuthValidator;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    /**
     * @apiGroup           Authentication
     * @apiName            Register
     *
     * @api                {POST} /register Register
     * @apiDescription    Register a user in app
     *
     * @apiVersion         1.0.0
     *
     * @apiParam           {String}  email user email
     * @apiParam           {String}  password user password
     * @apiParam           {String}  password_confirmation user password
     * @apiParam           {String}  name user name
     *
     * @apiSuccessExample  {json}       Success-Response:
     * HTTP/1.1 200 OK
     * {
     * "token_type": "Bearer",
     * "expires_in": 315360000,
     * "access_token": "eyJ0eXAiOiJKV1QiLCJhbG...",
     * }
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        AuthValidator::register($request->all());
        $token = AuthFacade::registerUser($request->all());
        $responseHandler = new FractalResponseHelper(new AuthTokenTransformer(), 'Item', 'Tokens');
        return $responseHandler->response($token, ResponseCodes::HTTP_OK);
    }

    /**
     * @apiGroup           Authentication
     * @apiName            Login
     *
     * @api                {POST} /login Login
     * @apiDescription     Login a user in app
     * @apiVersion         1.0.0
     *
     * @apiParam           {String}  email user email
     * @apiParam           {String}  password user password
     * @apiParam           {String}  device_token (optional) one signal player id
     *
     * @apiSuccessExample  {json}       Success-Response:
     * HTTP/1.1 200 OK
     * {
     * "token_type": "Bearer",
     * "expires_in": 315360000,
     * "access_token": "eyJ0eXAiOiJKV1QiLCJhbG..."
     * }
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        AuthValidator::login($request->all());
        $token = AuthFacade::loginUser($request->all());
        $responseHandler = new FractalResponseHelper(new AuthTokenTransformer(), 'Item', 'Tokens');
        return $responseHandler->response($token, ResponseCodes::HTTP_OK);
    }


    /**
     * @apiGroup           Authentication
     * @apiName            Forgot Password
     *
     * @api                {POST} /forgot-password Forgot Password
     * @apiDescription      Sends Reset Password Link to user
     * @apiVersion         1.0.0
     *
     * @apiParam           {String}  email user email
     *
     * @apiVersion         1.0.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * {
     * "message" : "We have sent you and email with password reset link"
     * }
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        AuthValidator::forgotPassword($request->all());
        $data = AuthFacade::forgotPassword($request->all());
        $responseHandler = new FractalResponseHelper(new SuccessTransformer(), 'Item', 'Message');
        return $responseHandler->response($data, ResponseCodes::HTTP_OK);
    }


    /**
     * @apiGroup           Authentication
     * @apiName            Change Password
     *
     * @api                {POST} /change-password Change Password
     * @apiDescription     Changes user's Password
     * @apiVersion         1.0.0
     *
     * @apiParam           {String}  old_password user old password
     * @apiParam           {String}  new_password user new password
     * @apiParam           {String}  confirm_password user confirm new password
     *
     * @apiSuccessExample  {json}       Success-Response:
     * HTTP/1.1 200 OK
     *   {
     *   "message": "Password Changed Successfully"
     *   }
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        AuthValidator::changePassword($request->all());
        $data = AuthFacade::changeUserPassword($request->all());
        $responseHandler = new FractalResponseHelper(new SuccessTransformer(), 'Item', 'Message');
        return $responseHandler->response($data, ResponseCodes::HTTP_OK);
    }


    /**
     * @apiGroup           Authentication
     * @apiName            Logout
     *
     * @api                {POST} /logout Logout User.
     * @apiDescription     Logout user
     * @apiVersion         1.0.0
     *
     *
     * @apiVersion         1.0.0
     * @apiPermission      Authenticated
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbG..."
     *     }
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * {
     * "message": 'Successfully Logged Out',
     * }
     */
    public function logout()
    {
        $data = AuthFacade::logout();
        $responseHandler = new FractalResponseHelper(new SuccessTransformer(), 'Item', 'Message');
        return $responseHandler->response($data, ResponseCodes::HTTP_OK);
    }



    /**
     * @apiGroup           Authentication (Social)
     * @apiName            Facebook
     *
     * @api                {GET} /social/auth/facebook?access_token=your_token
     * @apiDescription     Login with facebook using access token
     *                     For generating test tokens visit: https://developers.facebook.com/tools/explorer/
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
     * "access_token": "eyJ0eXAiOiJKV1QiLCJhbG..."
     * }
     */

    /**
     * @apiGroup           Authentication (Social)
     * @apiName            Google
     *
     * @api                {GET} /social/auth/google?access_token=your_token
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
     * "access_token": "eyJ0eXAiOiJKV1QiLCJhbG..."
     * }
     */

    /**
     * @apiGroup           Authentication (Social)
     * @apiName            Twitter
     *
     * @api                {GET} /social/auth/twitter?access_token=your_token&token_secret=your_secret
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
     * "access_token": "eyJ0eXAiOiJKV1QiLCJhbG..."
     * }
     * @param Request $request
     * @param $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function socialAuth(Request $request, $provider)
    {
        AuthValidator::socialAuth($request->all());
        $data = AuthFacade::socialAuth($provider, $request->all());
        $responseHandler = new FractalResponseHelper(new AuthTokenTransformer(), 'Item', 'Tokens');
        return $responseHandler->response($data, ResponseCodes::HTTP_OK);
    }
}
