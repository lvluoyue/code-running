<?php

namespace app\service\impl;

use app\annotation\Component;
use app\service\IndexService;
use DI\Attribute\Inject;
use support\Db;
use support\Response;
use Webman\Route;
use Workbunny\WebmanCoroutine\Utils\Coroutine\Coroutine;
use Workbunny\WebmanCoroutine\Utils\WaitGroup\WaitGroup;
use Workerman\Protocols\Http\Chunk;
use Workerman\Protocols\Http\ServerSentEvents;
use Workerman\Timer;
use Workerman\Worker;
use function \Workbunny\WebmanCoroutine\sleep;
use function Workbunny\WebmanCoroutine\is_coroutine_env;

#[Component]
class IndexServiceImpl implements IndexService
{
    #[Inject("TEST_ABC")]
    private string $abc;

    public function index(string $v): Response
    {
        $appName =  env('SERVER_APP_NAME', 'webman');
        return success([
            'app名称：' . $appName,
            '操作系统：' . php_uname('s') . ' ' . php_uname('r'),
            'PHP版本：' . PHP_VERSION,
            'workerClass：' . config("process.$appName.workerClass"),
            'workerman版本：' . Worker::VERSION,
            'event库：' . Worker::getEventLoop()::class
        ]);
    }

    public function sse(): Response
    {
        $connection = request()->connection;
        $waitGroup = new WaitGroup();
        $waitGroup->add(30);
        for ($i = 0; $i < $waitGroup->count(); $i++) {
            /** @var Coroutine[] $coroutine */
            $coroutine[$i] = new Coroutine(function () use ($waitGroup, $connection, $i, &$coroutine) {
                sleep(0.1 * $i);
                $connection->send(new ServerSentEvents([
                    'event' => 'message',
                    'data' => 'hello' . $i,
                    'id' => $i
                ]));
                $waitGroup->done();
            });
        }

        $timeOne = microtime(true);
        //设置定时器
        $timer_id = Timer::add(1, function () use ($connection, $waitGroup, &$timer_id, $timeOne, &$coroutine) {
            // 发送完毕，断开客户端的tcp连接
            if ($waitGroup->count() == 0) {
                Timer::del($timer_id);
                $connection->close(new ServerSentEvents([
                    'event' => 'close',
                    'data' => 'close',
                    'id' => 0
                ]));
                $timeTwo = microtime(true);
                echo '[协程] [运行时间] ' . ($timeTwo - $timeOne) . PHP_EOL;
            }
        });

        //tcp关闭连接后立刻停止协程
        $connection->onClose = function () use($timer_id, &$coroutine) {
            Timer::del($timer_id);
            foreach ($coroutine as $weakMap) {
                print_r($weakMap->origin());
                $weakMap->kill($weakMap);
            }
//            foreach ($weakMap->getCoroutinesWeakMap()->getIterator() as $value) {
//                [$seconds, $microseconds] = explode('.', $value['startTime']);
//                echo '[协程] [' . $value['id'] . '] ' . date('Y-m-d H:i:s', $seconds) . ' ' . $microseconds . PHP_EOL;
//            }
        };

        return response("\r\n")->withHeaders([
            "Content-Type" => "text/event-stream",
        ]);
    }

    function chunked(): Response
    {
        $connection = request()->connection;
        $waitGroup = new WaitGroup();
        $waitGroup->add(30);
        for ($i = 0; $i < $waitGroup->count(); $i++) {
            $coroutine = new Coroutine(function () use ($waitGroup, $connection, $i) {
                sleep(0.1 * $i);
                $connection->send(new Chunk($i . " "));
                $waitGroup->done();
            });
        }

        $timeOne = microtime(true);
        //设置定时器
        $timer_id = Timer::add(1, function () use ($connection, $waitGroup, &$timer_id, $timeOne) {
            // 发送完毕，断开客户端的tcp连接
            if ($waitGroup->count() == 0) {
                Timer::del($timer_id);
                $connection->close(new Chunk(''));
                $timeTwo = microtime(true);
                echo '[协程] [运行时间] ' . ($timeTwo - $timeOne) . PHP_EOL;
            }
        });


        //tcp关闭连接后立刻停止协程
        $connection->onClose = function () use($timer_id, &$coroutine) {
            Timer::del($timer_id);
            foreach ($coroutine as $weakMap) {
                $weakMap->kill($weakMap);
            }
        };

        return response()->withHeaders([
            "Transfer-Encoding" => "chunked",
            "Content-Type" => "application/octet-stream" //二进制流
        ]);
    }

    public function mysql(): Response
    {
        return json(Db::table("api_call")->get());
    }

}