<?php

namespace app\service;

use app\annotation\Language;
use support\Response;
use Workerman\Protocols\Http\Chunk;

interface CodeService {

    #[Language('php', <<<LANGUAGE
<?php
echo 'Hello World!';
LANGUAGE
    )]
    function php(string $code, ?string $stdin): array;

    #[Language('python', <<<LANGUAGE
print('hello, world')
LANGUAGE
    )]
    function python(string $code, ?string $stdin): array;

    #[Language('golang', <<<LANGUAGE
package main

import "fmt"

func main() {
    fmt.Println("Hello, World!")
}
LANGUAGE
    )]
    function golang(string $code, ?string $stdin): array;

    #[Language('java', <<<LANGUAGE
import java.util.*;
import java.io.*;

public class Main {
    public static void main(String[] args) {
        System.out.println("Hello World");
    }
}
LANGUAGE
    )]
    function java(string $code, ?string $stdin): array;

    #[Language('javascript(Nodejs)', <<<LANGUAGE
console.log('hello, world');
LANGUAGE
    )]
    function javascript(string $code, ?string $stdin): array;

    #[Language('typescript(Nodejs)', <<<LANGUAGE
console.log('hello, world');
LANGUAGE
    )]
    function typescript(string $code, ?string $stdin): array;

    #[Language('c', <<<LANGUAGE
#include <stdio.h>

int main() {
  printf("Hello, World\\n");
  return 0;
}
LANGUAGE
    )]
    function gcc(string $code, ?string $stdin): array;

    #[Language('c++', <<<LANGUAGE
#include <iostream>

int main() {
  std::cout << "hello world" << std::endl;
  return 0;
}
LANGUAGE
    )]
    function gcc_cpp(string $code, ?string $stdin): array;

    #[Language('rust', <<<LANGUAGE
fn main(){
    println!("Hello, world!");
}
LANGUAGE
    )]
    function rust(string $code, ?string $stdin): array;

}