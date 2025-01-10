<?php

namespace app\annotation\parser;

use LinFly\Annotation\Contracts\IAnnotationParser;

class LanguageParser implements iAnnotationParser
{

    private static array $languages = [];

    public static function process(array $item): void
    {
        self::$languages[] = [
            'lang' => $item['parameters']['lang'],
            'value' => $item['method'],
            'demoCode' => base64_encode($item['parameters']['demoCode']),
        ];
    }

    public static function getLanguages(): array
    {
        return self::$languages;
    }
}