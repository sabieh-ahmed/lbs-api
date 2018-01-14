<?php

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;

class AuthTokenTransformer extends TransformerAbstract
{

    /**
     * Turn this item object into a generic array.
     * @return array
     */
    public function transform($token)
    {
        return [
            'token' => $token['access_token'],
            'token_type' => $token['token_type'],
            'expires_in' => $token['expires_in'],
        ];
    }

}
