<?php

namespace app\annotation;

use app\annotation\parser\LanguageParser;
use LinFly\Annotation\AbstractAnnotationAttribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Language extends AbstractAnnotationAttribute
{
    public function __construct(string $lang, string $demoCode = '')
    {
        $this->setArguments(func_get_args());
    }

    public static function getParser(): string|array
    {
        return LanguageParser::class;
    }

}