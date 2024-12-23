<?php

namespace app\service;

use support\Response;
use Workerman\Protocols\Http\Chunk;

interface CodeService {
    function php(string $code, ?string $input): Response;

    function python(string $code, ?string $input): Response;

    function golang(string $code, ?string $input): Response;

    function java(string $code, ?string $input): Response;

    function javascript(string $code, ?string $input): Response;

    function typescript(string $code, ?string $input): Response;

}