<?php

use DiggPHP\Database\Db;
use DiggPHP\Framework\Framework;

return Framework::execute(function (
    Db $db
): array {
    $res = [];
    $res[] = [
        'title' => '链接总数',
        'body' => $db->count('ebcms_link_url'),
        'tags' => ['info'],
        'priority' => 5,
    ];
    $res[] = [
        'title' => '今日点击',
        'body' => $db->count('ebcms_link_log', [
            'year' => date('Y'),
            'month' => date('m'),
            'day' => date('d'),
        ]),
        'tags' => ['info'],
        'priority' => 4,
    ];
    $res[] = [
        'title' => '昨日点击',
        'body' => $db->count('ebcms_link_log', [
            'year' => date('Y', time() - 86400),
            'month' => date('m', time() - 86400),
            'day' => date('d', time() - 86400),
        ]),
        'tags' => ['info'],
        'priority' => 3,
    ];
    $res[] = [
        'title' => '前日点击',
        'body' => $db->count('ebcms_link_log', [
            'year' => date('Y', time() - 86400 * 2),
            'month' => date('m', time() - 86400 * 2),
            'day' => date('d', time() - 86400 * 2),
        ]),
        'tags' => ['info'],
        'priority' => 2,
    ];
    return $res;
});
