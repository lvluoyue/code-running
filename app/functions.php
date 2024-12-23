<?php
/**
 * Here is your custom functions.
 */

use \app\annotation\Message;
use \app\utils\ResultCode;

if(!isset($MessageType)) {
    static $MessageType = [];
}

if (!function_exists('message')) {
    function message(\app\utils\ResultCode $code, mixed $data, ?string $message = null): \support\Response
    {
        if(!isset($message)) {
            $reflection = new ReflectionEnum($code);
            $reflectionConstant = $reflection->getReflectionConstant($code->name);
            $attributes = current($reflectionConstant->getAttributes(Message::class));
            $message = $attributes->newInstance()->message . '，' . $message;
        }
        return json(['code' => $code->value, 'message' => $message, 'data' => $data]);
    }
}

if (!function_exists('success')) {
    function success(mixed $data): \support\Response
    {
        return message(ResultCode::SUCCESS, $data);
    }
}

if (!function_exists('error')) {
    function error(string $data): \support\Response
    {
        return message(ResultCode::ERROR, $data);
    }
}

if (!function_exists('deleteDirectory')) {
    function deleteDirectory($dir)
    {
        if (!is_dir($dir)) {
            return false;
        }

        $dirIterator = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($dirIterator, \RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                rmdir($file->getPathname());
            } else {
                unlink($file->getPathname());
            }
        }

        rmdir($dir);
        return true;
    }
}

if (!function_exists('docker_it')) {
    function docker_it(string $cmd, ?string $input, string &$output, string &$error, float &$runningTime = 0): void
    {
        $descriptorspec = [
            0 => ['pipe', 'r'], // 标准输入
            1 => ['pipe', 'w'], // 标准输出
            2 => ['pipe', 'w'], // 标准错误
        ];
        $startTime = microtime(true);
        $process = proc_open($cmd, $descriptorspec, $pipes);
        if (is_resource($process)) {
            if($input) {
                fwrite($pipes[0], $input);
            }
            fclose($pipes[0]);
            $output = mb_convert_encoding(stream_get_contents($pipes[1]), 'UTF-8', 'auto');
            $error = mb_convert_encoding(stream_get_contents($pipes[2]), 'UTF-8', 'auto');
            fclose($pipes[1]);
            fclose($pipes[2]);
        }
        $endTime = microtime(true);
        $runningTime = $endTime - $startTime;
    }
}
