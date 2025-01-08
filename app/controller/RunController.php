<?php

namespace app\controller;

use app\service\CodeService;
use app\utils\ResultCode;
use app\validate\CodeValidate;
use DI\Attribute\Inject;
use LinFly\Annotation\Attributes\Route\Controller;
use LinFly\Annotation\Attributes\Route\PostMapping;
use LinFly\Annotation\Validate\Validate;
use Webman\Annotation\Middleware;

#[Controller('/api/run')]
#[Middleware(\app\middleware\AccessToken::class, \app\middleware\ActionLock::class)]
#[Validate('$post', CodeValidate::class)]
class RunController
{

    #[Inject]
    private CodeService $codeService;

    #[PostMapping('')]
    public function index(string $language, string $code, ?string $stdin = null)
    {
        if(!method_exists($this->codeService, $language)) {
            return message(ResultCode::LANGUAGE_NOT_SUPPORTED, []);
        }
        return success($this->codeService->$language($code, $stdin));
    }

}