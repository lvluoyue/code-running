<?php
/**
 * This file is part of workbunny.
 *
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    chaz6chez<chaz6chez1993@outlook.com>
 * @copyright chaz6chez<chaz6chez1993@outlook.com>
 * @link      https://github.com/workbunny/webman-push-server
 * @license   https://github.com/workbunny/webman-push-server/blob/main/LICENSE
 */
declare(strict_types=1);

use support\Log;
use Webman\Http\Request;
use Workbunny\WebmanCoroutine\CoroutineWebServer;
use function Workbunny\WebmanCoroutine\event_loop;

//return config('plugin.workbunny.webman-coroutine.app.enable', false) ? [
//    'coroutine-web-server' => [
//        'handler'     => CoroutineWebServer::class,
//        'listen'      => 'http://0.0.0.0:' . config('plugin.workbunny.webman-coroutine.app.port', 8717),
//        'count'       => cpu_count(),
//        'user'        => '',
//        'group'       => '',
//        'reusePort'   => true,
//        'context' => [],
//        'constructor' => [
//            'requestClass' => Request::class,
//            'logger'       => Log::channel(), // 日志实例
//            'appPath'      => app_path(), // app目录位置
//            'publicPath'   => public_path(), // public目录位置
//        ],
//    ],
//] : [];
return [];