<?php

return [
    "@" => [
        config('plugin.luoyue.webman-mvc-core.app.permission.enable') ? \Luoyue\WebmanMvcCore\middleware\PermissionMiddleware::class : ''
    ]
];