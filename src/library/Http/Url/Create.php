<?php

declare(strict_types=1);

namespace App\Ebcms\Link\Http\Url;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Form\Builder;
use DiggPHP\Form\Component\Col;
use DiggPHP\Form\Component\Row;
use DiggPHP\Form\Field\Input;
use DiggPHP\Form\Field\Radio;
use DiggPHP\Request\Request;

class Create extends Common
{
    public function get()
    {
        $form = new Builder('添加链接');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    new Input('链接ID', 'key', $this->getRandStr(8)),
                    new Input('链接地址', 'url'),
                    new Input('备注', 'tips'),
                    new Radio('是否发布', 'state', 1, [
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
        $db->insert('ebcms_link_url', [
            'key' => $request->post('key'),
            'url' => $request->post('url'),
            'tips' => $request->post('tips'),
            'state' => $request->post('state'),
        ]);
        return $this->success('操作成功！');
    }

    private function getRandStr($length)
    {
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = strlen($str) - 1;
        $randstr = '';
        for ($i = 0; $i < $length; $i++) {
            $num = mt_rand(0, $len);
            $randstr .= $str[$num];
        }
        return $randstr;
    }
}
