<?php

namespace app\service\impl;

use app\service\IndexService;
use DI\Attribute\Inject;
use Luoyue\WebmanMvcCore\annotation\core\Service;
use support\Response;
use Workerman\Worker;

#[Service]
class IndexServiceImpl implements IndexService
{
    #[Inject("TEST_ABC")]
    private string $abc;

    public function index(string $v): Response
    {
        $appName = env('SERVER_APP_NAME', 'webman');
        return success([
            'app名称：' . $appName,
            '操作系统：' . php_uname('s') . ' ' . php_uname('r'),
            'PHP版本：' . PHP_VERSION,
            'workerClass：' . config("process.$appName.workerClass"),
            'workerman版本：' . Worker::VERSION,
            'event库：' . (Worker::$eventLoopClass ?: event_loop())
        ]);
    }

}