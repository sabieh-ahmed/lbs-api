<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseCodes;
use Closure;
use Tymon\JWTAuth\Middleware\BaseMiddleware;

class TokenEntrustAbility extends BaseMiddleware
{
    public function handle(
        $request,
        Closure $next,
        $roles,
        $permissions,
        $validateAll = false
    ) {
        $validateAll = ($validateAll === 'false') ? false : true;
        if (!$token = $this->auth->setRequest($request)->getToken()) {
            abort(ResponseCodes::HTTP_UNAUTHORIZED, trans('auth.token.absent'));
        }

        $user = $this->auth->authenticate($token);
        $request->user()->setPayload($this->auth->getPayload($token));

        if (!$user) {
            abort(ResponseCodes::HTTP_NOT_FOUND, trans('auth.token.not_found'));
        }

        if ((!empty($roles) || !empty($permissions)) &&
            !$request->user()->ability(
                explode('|', $roles),
                explode('|', $permissions),
                ['validate_all' => $validateAll]
            )
        ) {
            abort(ResponseCodes::HTTP_FORBIDDEN, trans('auth.token.not_permitted'));
        }
        $this->events->fire('tymon.jwt.valid', $user);
        return $next($request);
    }
}
