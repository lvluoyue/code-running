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

#[Controller("/code")]
#[Validate(params: '$post', validate: CodeValidate::class)]
class CodeController
{

    #[Inject]
    private readonly CodeService $codeService;

    #[PostMapping]
    public function php(Request $request, string $code, ?string $input = null): Response
    {
        return $this->codeService->php($code, $input);
    }

    #[PostMapping]
    public function golang(Request $request, string $code, ?string $input = null): Response
    {
        return $this->codeService->golang($code, $input);
    }

    #[PostMapping]
    public function python(Request $request, string $code, ?string $input = null): Response
    {
        return $this->codeService->python($code, $input);
    }

    #[PostMapping]
    public function java(Request $request, string $code, ?string $input = null): Response
    {
        return $this->codeService->java($code, $input);
    }

    #[PostMapping]
    public function javascript(Request $request, string $code, ?string $input = null): Response
    {
        return $this->codeService->javascript($code, $input);
    }

    #[PostMapping]
    public function typescript(Request $request, string $code, ?string $input = null): Response
    {
        return $this->codeService->typescript($code, $input);
    }
}