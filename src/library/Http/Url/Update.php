<?php

declare(strict_types=1);

namespace App\Ebcms\Link\Http\Url;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Form\Builder;
use DiggPHP\Form\Component\Col;
use DiggPHP\Form\Component\Row;
use DiggPHP\Form\Field\Hidden;
use DiggPHP\Form\Field\Input;
use DiggPHP\Form\Field\Radio;
use DiggPHP\Request\Request;

class Update extends Common
{
    public function get(
        Request $request,
        Db $db
    ) {
        $url = $db->get('ebcms_link_url', '*', [
            'id' => $request->get('id'),
        ]);
        $form = new Builder('编辑链接');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-8'))->addItem(
                    new Hidden('id', $url['id']),
                    new Input('链接KEY', 'key', $url['key'], [
                        'readonly' => true,
                    ]),
                    new Input('链接地址', 'url', $url['url']),
                    new Input('备注', 'tips', $url['tips']),
                    new Radio('是否发布', 'state', $url['state'], [
                        '0' => '否',
                        '1' => '是',
                    ])
                )
            )
        );
        return $form;
    }

    public function post(
        Request $request,
        Db $db
    ) {
        $url = $db->get('ebcms_link_url', '*', [
            'id' => $request->post('id'),
        ]);

        $update = array_intersect_key($request->post(), [
            'url' => '',
            'tips' => '',
            'state' => '',
        ]);

        $db->update('ebcms_link_url', $update, [
            'id' => $url['id'],
        ]);

        return $this->success('操作成功！');
    }
}
