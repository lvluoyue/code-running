<?php

namespace app\service\impl;

use app\annotation\Service;
use app\service\CodeService;
use support\Response;

#[Service]
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

    function gcc(string $code, ?string $input): Response
    {
        $image = 'gcc';
        $bindPath = $this->getOrCreateDirectory(DIRECTORY_SEPARATOR . __FUNCTION__ . '/id');
        $mainFile = 'Main.c';
        $code = base64_decode($code);
        file_put_contents($bindPath . DIRECTORY_SEPARATOR . $mainFile, $code);
        $result = $this->dockerRun($image, $bindPath, '/bin/bash -c "gcc ' . $mainFile . ' -o Main && ./Main"', $input);
        return success($result);
    }

    function gcc_cpp(string $code, ?string $input): Response
    {
        $image = 'gcc';
        $bindPath = $this->getOrCreateDirectory(DIRECTORY_SEPARATOR . __FUNCTION__ . '/id');
        $mainFile = 'Main.cpp';
        $code = base64_decode($code);
        file_put_contents($bindPath . DIRECTORY_SEPARATOR . $mainFile, $code);
        $result = $this->dockerRun($image, $bindPath, '/bin/bash -c "gcc ' . $mainFile . ' -lstdc++ -o Main && ./Main"', $input);
        return success($result);
    }

    function rust(string $code, ?string $input): Response
    {
        $image = 'rust';
        $bindPath = $this->getOrCreateDirectory(DIRECTORY_SEPARATOR . __FUNCTION__ . '/id');
        $mainFile = 'Main.rs';
        $txt = <<<Cargo
[package]
name = "hello_world" # the name of the package
version = "0.1.0"    # the current version, obeying semver

[[bin]]
name = "hello_world"
path = "Main.rs"
Cargo;
        file_put_contents($bindPath . DIRECTORY_SEPARATOR . 'Cargo.toml', $txt);
        $code = base64_decode($code);
        file_put_contents($bindPath . DIRECTORY_SEPARATOR . $mainFile, $code);
        $result = $this->dockerRun($image, $bindPath, '/bin/bash -c "cargo run --quiet"', $input);
        return success($result);
    }

    protected function dockerRun(string $image, string $bindPath, string $cmd, ?string $input): array
    {
        $result = [
            'output' => '',
            'error' => '',
            'runningTime' => 0,
        ];
        ///opt:ro
        $cmd = "docker run --rm -iu nobody -v $bindPath:/opt -w /opt $image $cmd";
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