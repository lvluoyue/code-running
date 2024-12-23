<?php

namespace app\controller;

use app\service\IndexService;
use DI\Attribute\Inject;
use LinFly\Annotation\Attributes\Route\GetMapping;
use LinFly\Annotation\Attributes\Route\RequestMapping;
use support\Request;
use support\Response;

//#[Middleware(\app\middleware\testMiddleware::class)]
class Controller
{
    #[Inject]
    private readonly IndexService $indexService;

    #[RequestMapping("")]
    public function index(Request $request,#[Inject("TEST_ABCD")] $abc): Response
    {
        return $this->indexService->index($abc);
    }

    #[GetMapping]
    public function sse(Request $request): Response
    {
        return $this->indexService->sse();
    }

    #[GetMapping]
    public function chunked(Request $request): Response
    {
        return $this->indexService->chunked();
    }

    #[GetMapping]
    public function mysql(Request $request): Response
    {
        return $this->indexService->mysql();
    }

    #[GetMapping("{id:\d+}")]
    public function hello(int $id): string
    {
        return 'hello' . $id;
    }

    #[RequestMapping]
    public function view(Request $request): Response
    {
        return view('index/view', ['name' => 'webman']);
    }

    #[RequestMapping]
    public function json(Request $request): Response
    {
        return json(['code' => 0, 'msg' => 'ok']);
    }

}