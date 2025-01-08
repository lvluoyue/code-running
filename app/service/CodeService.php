<?php

namespace app\service;

use support\Response;
use Workerman\Protocols\Http\Chunk;

interface CodeService {
    function php(string $code, ?string $stdin): array;

    function python(string $code, ?string $stdin): array;

    function golang(string $code, ?string $stdin): array;

    function java(string $code, ?string $stdin): array;

    function javascript(string $code, ?string $stdin): array;

    function typescript(string $code, ?string $stdin): array;

    function gcc(string $code, ?string $stdin): array;

    function gcc_cpp(string $code, ?string $stdin): array;

    function rust(string $code, ?string $stdin): array;

}