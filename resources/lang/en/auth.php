<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'errors' => [
        'invalid_credentials' => 'Invalid Credentials',
        'invalid_password' => 'The old password your entered is Invalid.',
        'invalid_access_token' => 'Not a valid access token!',
        'token_secret' => 'Field token_secret is required for :network authentication!'
    ],
    'messages' => [
        'password_changed' => 'Password Changed Successfully',
        'logout' => 'Successfully Logged Out',
        'forgot_password' => 'We have sent you and email with password reset link. Check your inbox.'
    ]

];
