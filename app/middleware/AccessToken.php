<?php

namespace app\middleware;

use DI\Attribute\Inject;
use support\Container;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class AccessToken implements MiddlewareInterface
{

    #[Inject('ACCESS_TOKEN')]
    protected string $access_token;

    public function process(Request $request, callable $handler): Response
    {
        $access_token = $request->get('access_token', '');
        if(empty($access_token) || $access_token !== $this->access_token) {
            return error('access_token 错误')->withStatus(401);
        }
        return $handler($request);
    }
}