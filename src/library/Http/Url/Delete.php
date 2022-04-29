<?php

declare(strict_types=1);

namespace App\Ebcms\Link\Http\Url;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Request\Request;

class Delete extends Common
{
    public function get(
        Request $request,
        Db $db
    ) {
        $db->delete('ebcms_link_url', [
            'id' => $request->get('id'),
        ]);
        $db->delete('ebcms_link_log', [
            'url_id' => $request->get('id'),
        ]);
        return $this->success('操作成功！');
    }
}
