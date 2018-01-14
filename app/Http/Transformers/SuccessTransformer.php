<?php

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;

class SuccessTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     * @return array
     */
    public function transform($success)
    {
        return $success;
    }
}
