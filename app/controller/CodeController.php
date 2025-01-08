<?php

namespace app\controller;

use app\service\CodeService;
use app\validate\CodeValidate;
use DI\Attribute\Inject;
use LinFly\Annotation\Attributes\Route\Controller;
use LinFly\Annotation\Attributes\Route\PostMapping;
use LinFly\Annotation\Validate\Validate;
use support\Request;
use support\Response;
use Webman\Annotation\Middleware;

#[Controller("/code")]
#[Middleware(\app\middleware\AccessToken::class, \app\middleware\ActionLock::class)]
#[Validate('$post', CodeValidate::class, 'language')]
class CodeController
{

    #[Inject]
    private readonly CodeService $codeService;

    #[PostMapping]
    public function php(Request $request, string $code, ?string $stdin = null): Response
    {
        return success($this->codeService->php($code, $stdin));
    }

    #[PostMapping]
    public function golang(Request $request, string $code, ?string $stdin = null): Response
    {
        return success($this->codeService->golang($code, $stdin));
    }

    #[PostMapping]
    public function python(Request $request, string $code, ?string $stdin = null): Response
    {
        return success($this->codeService->python($code, $stdin));
    }

    #[PostMapping]
    public function java(Request $request, string $code, ?string $stdin = null): Response
    {
        return success($this->codeService->java($code, $stdin));
    }

    #[PostMapping]
    public function javascript(Request $request, string $code, ?string $stdin = null): Response
    {
        return success($this->codeService->javascript($code, $stdin));
    }

    #[PostMapping]
    public function typescript(Request $request, string $code, ?string $stdin = null): Response
    {
        return success($this->codeService->typescript($code, $stdin));
    }

    #[PostMapping]
    public function c(Request $request, string $code, ?string $stdin = null): Response
    {
        return success($this->codeService->gcc($code, $stdin));
    }

    #[PostMapping]
    public function c2(Request $request, string $code, ?string $stdin = null): Response
    {
        return success($this->codeService->gcc_cpp($code, $stdin));
    }

    #[PostMapping]
    public function rust(Request $request, string $code, ?string $stdin = null): Response
    {
        return success($this->codeService->rust($code, $stdin));
    }
}