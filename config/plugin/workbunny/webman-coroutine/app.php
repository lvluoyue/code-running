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

return [
    // coroutine-web-server 开关
    'enable'         => false,
    // coroutine-web-server 监听端口
    'port'           => 8717,
    /*
     * @deprecated
     */
    'channel_size'   => 1,
    // request consumer 数量 0:无限
    'consumer_count' => 0,
    // 等待连接关闭, 0:不开启 -1:无限等待 >0:等待N秒
    'wait_for_close' => 0,
];
