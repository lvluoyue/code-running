<?php

namespace app\middleware;

use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;
use yzh52521\WebmanLock\Locker;

class ActionLock implements MiddlewareInterface
{

    public function process(Request $request, callable $handler): Response
    {
        $key = $request->get('access_token', '');
        $lock = Locker::lock($key);
        if (!$lock->acquire()) {
            return error('操作太频繁，请稍后再试');
        }
        try {
            return $handler($request);
        } finally {
            $lock->release();
        }
    }
}