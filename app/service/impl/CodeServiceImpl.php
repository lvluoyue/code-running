<?php

namespace app\service\impl;

use app\annotation\Component;
use app\service\CodeService;
use support\Response;

#[Component]
class CodeServiceImpl implements CodeService
{

    private $code_cache_path = '/cache/code';

    function php(string $code, ?string $input): Response
    {
        $image = 'php';
        $bindPath = $this->getOrCreateDirectory(DIRECTORY_SEPARATOR . __FUNCTION__ . '/id');
        $mainFile = 'Main.php';
        $code = base64_decode($code);
        file_put_contents($bindPath . DIRECTORY_SEPARATOR . $mainFile, $code);
        $result = $this->dockerRun($image, $bindPath, 'php ' . $mainFile, $input);
        return success($result);
    }

    function python(string $code, ?string $input): Response
    {
        $image = 'python';
        $bindPath = $this->getOrCreateDirectory(DIRECTORY_SEPARATOR . __FUNCTION__ . '/id');
        $mainFile = 'Main.py';
        $code = base64_decode($code);
        file_put_contents($bindPath . DIRECTORY_SEPARATOR . $mainFile, $code);
        $result = $this->dockerRun($image, $bindPath, 'python ' . $mainFile, $input);
        return success($result);
    }

    function golang(string $code, ?string $input): Response
    {
        $image = 'golang';
        $bindPath = $this->getOrCreateDirectory(DIRECTORY_SEPARATOR . __FUNCTION__ . '/id');
        $mainFile = 'Main.go';
        $code = base64_decode($code);
        file_put_contents($bindPath . DIRECTORY_SEPARATOR . $mainFile, $code);
        $result = $this->dockerRun($image, $bindPath, 'bash -c "GOCACHE=/tmp/go-build-cache go run ' . $mainFile .'"', $input);
        return success($result);
    }

    function java(string $code, ?string $input): Response
    {
        $image = 'openjdk';
        $bindPath = $this->getOrCreateDirectory(DIRECTORY_SEPARATOR . __FUNCTION__ . '/id');
        $mainFile = 'Main.java';
        $code = base64_decode($code);
        file_put_contents($bindPath . DIRECTORY_SEPARATOR . $mainFile, $code);
        $result = $this->dockerRun($image, $bindPath, 'java ' . $mainFile, $input);
        return success($result);
    }

    function javascript(string $code, ?string $input): Response
    {
        $image = 'node';
        $bindPath = $this->getOrCreateDirectory(DIRECTORY_SEPARATOR . __FUNCTION__ . '/id');
        $mainFile = 'Main.js';
        $code = base64_decode($code);
        file_put_contents($bindPath . DIRECTORY_SEPARATOR . $mainFile, $code);
        $result = $this->dockerRun($image, $bindPath, 'node ' . $mainFile, $input);
        return success($result);
    }

    function typescript(string $code, ?string $input): Response
    {
        $image = 'useparagon/ts-node';
        $bindPath = $this->getOrCreateDirectory(DIRECTORY_SEPARATOR . __FUNCTION__ . '/id');
        $mainFile = 'Main.ts';
        $code = base64_decode($code);
        file_put_contents($bindPath . DIRECTORY_SEPARATOR . $mainFile, $code);
        $result = $this->dockerRun($image, $bindPath, 'ts-node ' . $mainFile, $input);
        return success($result);
    }

    protected function dockerRun(string $image, string $bindPath, string $cmd, ?string $input): array
    {
        $result = [
            'output' => '',
            'error' => '',
            'runningTime' => 0,
        ];
        $cmd = "docker run --rm -iu nobody -v $bindPath:/opt:ro -w /opt $image $cmd";
        docker_it($cmd, $input, $result['output'], $result['error'], $result['runningTime']);
        deleteDirectory($bindPath);
        return $result;
    }

    protected function getOrCreateDirectory($path): string
    {
        $codeDir = runtime_path($this->code_cache_path . $path);
        if (!is_dir($codeDir)) {
            mkdir($codeDir, 0777, true);
        }
        return $codeDir;
    }

}