<?php

declare(strict_types=1);

namespace App\Ebcms\Link\Http;

use App\Ebcms\Admin\Traits\ResponseTrait;
use App\Ebcms\Admin\Traits\RestfulTrait;
use DiggPHP\Database\Db;
use DiggPHP\Request\Request;

class Jump
{
    use RestfulTrait;
    use ResponseTrait;

    public function get(
        Db $db,
        Request $request
    ) {
        if (!$url = $db->get('ebcms_link_url', '*', [
            'key' => $request->get('key'),
        ])) {
            return '地址不存在~';
        }

        $db->insert('ebcms_link_log', [
            'url_id' => $url['id'],
            'url' => $url['url'],
            'time' => time(),
            'year' => date('Y'),
            'month' => date('m'),
            'day' => date('d'),
            'remote_addr' => $_SERVER['REMOTE_ADDR'] ?? '',
            'http_referer' => $_SERVER['HTTP_REFERER'] ?? '',
            'http_user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'state' => $url['state'],
        ]);

        if (!$url['state']) {
            return '链接已经失效~';
        }

        return $this->redirect($url['url']);
    }
}
