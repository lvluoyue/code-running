<?php

namespace app\utils;

use app\annotation\Message;

enum ResultCode: int
{
    #[Message("成功")]
    case SUCCESS = 200;

    #[Message("失败")]
    case ERROR = 201;

    #[Message("不支持的语言")]
    case LANGUAGE_NOT_SUPPORTED = 300;

    #[Message("数据校验失败")]
    case VALITATE = 500;
}