<?php
namespace app\validate;

use think\Validate;

class CodeValidate extends Validate
{
    protected $rule =   [
        'input'  => 'string',
        'code'   => 'require|string'
    ];

    protected $message  =   [
        'name.require' => '名称必须',
        'name.max'     => '名称最多不能超过25个字符',
        'age.number'   => '年龄必须是数字',
        'code.between'  => '年龄只能在1-120之间',
    ];

}