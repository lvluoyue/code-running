<?php

namespace app\annotation;

#[\Attribute(\Attribute::TARGET_ALL)]
class Message
{

    public function __construct(public string $message)
    {
    }
}