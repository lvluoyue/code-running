<?php

namespace app\controller;

use app\service\IndexService;
use DI\Attribute\Inject;
use LinFly\Annotation\Attributes\Route\Controller;
use LinFly\Annotation\Attributes\Route\GetMapping;
use LinFly\Annotation\Attributes\Route\Middleware;
use LinFly\Annotation\Attributes\Route\RequestMapping;
use support\Request;
use support\Response;

#[Controller]
class IndexController
{
    #[Inject]
    private readonly IndexService $indexService;

    #[Inject('ACCESS_TOKEN')]
    private readonly string $access_token;

    #[RequestMapping("")]
    public function index(Request $request): Response
    {
        return view('index/index', ['access_token' => $this->access_token]);
    }

}