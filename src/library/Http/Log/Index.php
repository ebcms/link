<?php

declare(strict_types=1);

namespace App\Ebcms\Link\Http\Log;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Pagination\Pagination;
use DiggPHP\Request\Request;
use DiggPHP\Template\Template;
use Ip2Region;

class Index extends Common
{

    public function get(
        Db $db,
        Pagination $pagination,
        Request $request,
        Template $template,
        Ip2Region $ip2region
    ) {
        $where = [];
        $where['ORDER'] = [
            'id' => 'DESC',
        ];

        if ($url_id = $request->get('url_id')) {
            $where['url_id'] = $url_id;
        }
        if ($remote_addr = $request->get('remote_addr')) {
            $where['remote_addr'] = $remote_addr;
        }

        $total = $db->count('ebcms_link_log', $where);

        $page = $request->get('page', 1, ['intval']) ?: 1;
        $page_num = 100;
        $where['LIMIT'] = [($page - 1) * $page_num, $page_num];
        $logs = $db->select('ebcms_link_log', '*', $where);

        foreach ($logs as &$value) {
            $value['region'] = $ip2region->btreeSearch($value['remote_addr'])['region'];
            $value['urlx'] = $db->get('ebcms_link_url', '*', [
                'id' => $value['url_id']
            ]);
        }

        return $this->html($template->renderFromFile('log/index@ebcms/link', [
            'logs' => $logs,
            'pagination' => $pagination->render($page, $total, $page_num, 2),
            'total' => $total,
        ]));
    }
}
