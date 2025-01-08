<?php

namespace app\service\impl;

use app\annotation\Service;
use app\service\CodeService;
use support\Response;

#[Service]
class CodeServiceImpl implements CodeService
{

    private $code_cache_path = '/cache/code';

    function php(string $code, ?string $stdin): array
    {
        $image = 'php';
        $bindPath = $this->getOrCreateDirectory(DIRECTORY_SEPARATOR . __FUNCTION__);
        $mainFile = 'Main.php';
        $code = base64_decode($code);
        file_put_contents($bindPath . DIRECTORY_SEPARATOR . $mainFile, $code);
        return $this->dockerRun($image, $bindPath, 'php ' . $mainFile, $stdin);
    }

    function python(string $code, ?string $stdin): array
    {
        $image = 'python';
        $bindPath = $this->getOrCreateDirectory(DIRECTORY_SEPARATOR . __FUNCTION__);
        $mainFile = 'Main.py';
        $code = base64_decode($code);
        file_put_contents($bindPath . DIRECTORY_SEPARATOR . $mainFile, $code);
        return $this->dockerRun($image, $bindPath, 'python ' . $mainFile, $stdin);
    }

    function golang(string $code, ?string $stdin): array
    {
        $image = 'golang';
        $bindPath = $this->getOrCreateDirectory(DIRECTORY_SEPARATOR . __FUNCTION__);
        $mainFile = 'Main.go';
        $code = base64_decode($code);
        file_put_contents($bindPath . DIRECTORY_SEPARATOR . $mainFile, $code);
        return $this->dockerRun($image, $bindPath, 'bash -c "GOCACHE=/tmp/go-build-cache go run ' . $mainFile .'"', $stdin);
    }

    function java(string $code, ?string $stdin): array
    {
        $image = 'openjdk';
        $bindPath = $this->getOrCreateDirectory(DIRECTORY_SEPARATOR . __FUNCTION__);
        $mainFile = 'Main.java';
        $code = base64_decode($code);
        file_put_contents($bindPath . DIRECTORY_SEPARATOR . $mainFile, $code);
        return $this->dockerRun($image, $bindPath, 'java ' . $mainFile, $stdin);
    }

    function javascript(string $code, ?string $stdin): array
    {
        $image = 'node';
        $bindPath = $this->getOrCreateDirectory(DIRECTORY_SEPARATOR . __FUNCTION__);
        $mainFile = 'Main.js';
        $code = base64_decode($code);
        file_put_contents($bindPath . DIRECTORY_SEPARATOR . $mainFile, $code);
        return $this->dockerRun($image, $bindPath, 'node ' . $mainFile, $stdin);
    }

    function typescript(string $code, ?string $stdin): array
    {
        $image = 'useparagon/ts-node';
        $bindPath = $this->getOrCreateDirectory(DIRECTORY_SEPARATOR . __FUNCTION__);
        $mainFile = 'Main.ts';
        $code = base64_decode($code);
        file_put_contents($bindPath . DIRECTORY_SEPARATOR . $mainFile, $code);
        return $this->dockerRun($image, $bindPath, 'ts-node ' . $mainFile, $stdin);
    }

    function gcc(string $code, ?string $stdin): array
    {
        $image = 'gcc';
        $bindPath = $this->getOrCreateDirectory(DIRECTORY_SEPARATOR . __FUNCTION__);
        $mainFile = 'Main.c';
        $code = base64_decode($code);
        file_put_contents($bindPath . DIRECTORY_SEPARATOR . $mainFile, $code);
        return $this->dockerRun($image, $bindPath, '/bin/bash -c "gcc ' . $mainFile . ' -o Main && ./Main"', $stdin);
    }

    function gcc_cpp(string $code, ?string $stdin): array
    {
        $image = 'gcc';
        $bindPath = $this->getOrCreateDirectory(DIRECTORY_SEPARATOR . __FUNCTION__);
        $mainFile = 'Main.cpp';
        $code = base64_decode($code);
        file_put_contents($bindPath . DIRECTORY_SEPARATOR . $mainFile, $code);
        return $this->dockerRun($image, $bindPath, '/bin/bash -c "gcc ' . $mainFile . ' -lstdc++ -o Main && ./Main"', $stdin);
    }

    function rust(string $code, ?string $stdin): array
    {
        $image = 'rust';
        $bindPath = $this->getOrCreateDirectory(DIRECTORY_SEPARATOR . __FUNCTION__);
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
        return $this->dockerRun($image, $bindPath, '/bin/bash -c "cargo run --quiet"', $stdin);
    }

    protected function dockerRun(string $image, string $bindPath, string $cmd, ?string $stdin): array
    {
        $result = [
            'stdout' => '',
            'stderr' => '',
            'runningTime' => 0,
        ];
        ///opt:ro
        $cmd = "docker run --rm -iu nobody -v $bindPath:/opt -w /opt $image $cmd";
        docker_it($cmd, $stdin, $result['stdout'], $result['stderr'], $result['runningTime']);
        deleteDirectory($bindPath);
        return $result;
    }

    protected function getOrCreateDirectory($path): string
    {
        $codeDir = runtime_path($this->code_cache_path . $path . '/' . \Jisheng100\Snowflake\Snowflake::instance()->generateId());
        if (!is_dir($codeDir)) {
            mkdir($codeDir, 0777, true);
        }
        return $codeDir;
    }

}