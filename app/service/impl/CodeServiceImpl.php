<?php

namespace app\service\impl;

use app\annotation\Component;
use app\service\CodeService;
use support\Response;

#[Component]
class CodeServiceImpl implements CodeService
{

    function php(string $code, ?string $input): Response
    {
        $result = [
            'output' => '',
            'error' => '',
            'runningTime' => 0,
        ];
        $code = base64_decode($code);
        $codeDir = runtime_path('/cache/code/pthon/id/');
        if (!is_dir($codeDir)) {
            mkdir($codeDir, 0777, true);
        }
        file_put_contents($codeDir . 'Main.php', $code);
        $cmd = "docker run --rm -iu nobody -v $codeDir:/opt:ro -w /opt php bash -c \"php Main.php\"";
        docker_it($cmd, $input, $result['output'], $result['error'], $result['runningTime']);
        unlink($codeDir . 'Main.php');
        return success($result);
    }

//    function php(string $code, ?string $input): Response
//    {
//        $result = [
//            'output' => '',
//            'error' => '',
//            'runningTime' => 0,
//        ];
//        $str = base64_decode($str);
//        $str = str_replace("\"", "\\\"", $str);
//        $cmd = "docker run --rm -iu nobody -w /opt:ro php php -r \"$str\"";
//        docker_it($cmd, '', $result['output'], $result['error'], $result['runningTime']);
//        return success($result);
//    }

    function python(string $code, ?string $input): Response
    {
        $result = [
            'output' => '',
            'error' => '',
            'runningTime' => 0,
        ];
        $code = base64_decode($code);
        $codeDir = runtime_path('/cache/code/pthon/id/');
        if (!is_dir($codeDir)) {
            mkdir($codeDir, 0777, true);
        }
        file_put_contents($codeDir . 'Main.py', $code);
        $cmd = "docker run --rm -iu nobody -v $codeDir:/opt:ro -w /opt python bash -c \"python Main.py\"";
        docker_it($cmd, $input, $result['output'], $result['error'], $result['runningTime']);
        unlink($codeDir . 'Main.py');
        return success($result);
    }

    function golang(string $code, ?string $input): Response
    {
        $result = [
            'output' => '',
            'error' => '',
            'runningTime' => 0,
        ];
        $code = base64_decode($code);
        $codeDir = runtime_path('/cache/code/golang/id/');
        if (!is_dir($codeDir)) {
            mkdir($codeDir, 0777, true);
        }
        file_put_contents($codeDir . 'Main.go', $code);
        $cmd = "docker run --rm -iu nobody -v $codeDir:/opt:ro -w /opt golang bash -c \"GOCACHE=/tmp/go-build-cache go run Main.go\"";
        docker_it($cmd, $input, $result['output'], $result['error'], $result['runningTime']);
        unlink($codeDir . 'Main.go');
        return success($result);
    }

    function java(string $code, ?string $input): Response
    {
        $result = [
            'output' => '',
            'error' => '',
            'runningTime' => 0,
        ];
        $code = base64_decode($code);
        $codeDir = runtime_path('/cache/code/java/id/');
        if (!is_dir($codeDir)) {
            mkdir($codeDir, 0777, true);
        }
        file_put_contents($codeDir . 'Main.java', $code);
        $cmd = "docker run --rm -iu nobody -v $codeDir:/opt:ro -w /opt openjdk bash -c \"java Main.java\"";
        docker_it($cmd, $input, $result['output'], $result['error'], $result['runningTime']);
        unlink($codeDir . 'Main.java');
        return success($result);
    }

    function javascript(string $code, ?string $input): Response
    {
        $result = [
            'output' => '',
            'error' => '',
            'runningTime' => 0,
        ];
        $code = base64_decode($code);
        $codeDir = runtime_path('/cache/code/javascript/id/');
        if (!is_dir($codeDir)) {
            mkdir($codeDir, 0777, true);
        }
        file_put_contents($codeDir . 'Main.js', $code);
        $cmd = "docker run --rm -iu nobody -v $codeDir:/opt:ro -w /opt node bash -c \"node Main.js\"";
        docker_it($cmd, $input, $result['output'], $result['error'], $result['runningTime']);
        unlink($codeDir . 'Main.js');
        return success($result);
    }

    function typescript(string $code, ?string $input): Response
    {
        $result = [
            'output' => '',
            'error' => '',
            'runningTime' => 0,
        ];
        $code = base64_decode($code);
        $codeDir = runtime_path('/cache/code/typescript/id/');
        if (!is_dir($codeDir)) {
            mkdir($codeDir, 0777, true);
        }
        file_put_contents($codeDir . 'Main.ts', $code);
        $cmd = "docker run --rm -iu nobody -v $codeDir:/opt:ro -w /opt useparagon/ts-node ts-node Main.ts";
        docker_it($cmd, $input, $result['output'], $result['error'], $result['runningTime']);
        unlink($codeDir . 'Main.ts');
        return success($result);
    }
}