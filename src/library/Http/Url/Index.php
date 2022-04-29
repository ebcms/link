<?php

declare(strict_types=1);

namespace App\Ebcms\Link\Http\Url;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Pagination\Pagination;
use DiggPHP\Request\Request;
use DiggPHP\Template\Template;

class Index extends Common
{

    public function get(
        Db $db,
        Pagination $pagination,
        Request $request,
        Template $template
    ) {
        $data = [];
        $where = [];
        $total = $db->count('ebcms_link_url', $where);

        $page = $request->get('page') ?: 1;
        $pagenum = $request->get('pagenum') ?: 100;
        $where['LIMIT'] = [($page - 1) * $pagenum, $pagenum];
        $where['ORDER'] = [
            'id' => 'DESC',
        ];

        $urls = $db->select('ebcms_link_url', '*', $where);

        foreach ($urls as &$value) {
            $value['click_total'] = $db->count('ebcms_link_log', [
                'url_id' => $value['id'],
            ]);
            $value['click_today'] = $db->count('ebcms_link_log', [
                'url_id' => $value['id'],
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d'),
            ]);
            $value['click_yesterday'] = $db->count('ebcms_link_log', [
                'url_id' => $value['id'],
                'year' => date('Y', time() - 86400),
                'month' => date('m', time() - 86400),
                'day' => date('d', time() - 86400),
            ]);
            $value['click_before_yesterday'] = $db->count('ebcms_link_log', [
                'url_id' => $value['id'],
                'year' => date('Y', time() - 86400 * 2),
                'month' => date('m', time() - 86400 * 2),
                'day' => date('d', time() - 86400 * 2),
            ]);
        }

        $data['datas'] = $urls;
        $data['total'] = $total;
        $data['pages'] = $pagination->render($page, $total, $pagenum);

        return $this->html($template->renderFromFile('url/index@ebcms/link', $data));
    }
}
