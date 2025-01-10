<?php

namespace app\controller;

use app\annotation\Language;
use app\annotation\parser\LanguageParser;
use LinFly\Annotation\Attributes\Route\Controller;
use LinFly\Annotation\Attributes\Route\GetMapping;

#[Controller('/api/list')]
class ListController
{

    #[GetMapping('')]
    public function index()
    {
        return success(LanguageParser::getLanguages());
    }

}