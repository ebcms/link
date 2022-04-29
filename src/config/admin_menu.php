<?php

use DiggPHP\Router\Router;
use DiggPHP\Framework\Framework;

return Framework::execute(function (
    Router $router
): array {
    $res = [];
    $res[] = [
        'title' => '链接管理',
        'url' => $router->build('/ebcms/link/url/index'),
        'tags' => ['primary'],
        'priority' => 40,
    ];
    return $res;
});
