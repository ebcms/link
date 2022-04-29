{include common/header@ebcms/admin}
<div class="container">
    <div class="my-4">
        <div class="h1">链接管理</div>
    </div>
    <div class="my-4">
        <form id="form_filter" class="row gy-2 gx-3 align-items-center" action="{echo $router->build('/ebcms/link/url/index')}" method="GET">
            <input type="hidden" name="page" value="1">
            <div class="col-auto">
                <a class="btn btn-primary" href="{echo $router->build('/ebcms/link/url/create')}">新增</a>
            </div>
            <div class="col-auto">
                <label class="visually-hidden">搜索</label>
                <input type="search" class="form-control" name="q" value="{:$request->get('q')}" placeholder="请输入搜索词" onchange="document.getElementById('form_filter').submit();">
            </div>
        </form>
    </div>
    <div class="table-responsive my-4">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="text-nowrap">KEY</th>
                    <th scope="col">今日点击</th>
                    <th scope="col">昨日点击</th>
                    <th scope="col">前日点击</th>
                    <th scope="col">总点击</th>
                    <th class="text-nowrap">管理</th>
                </tr>
            </thead>
            <tbody>
                {foreach $datas as $vo}
                <tr>
                    <td>
                        <a href="{echo $router->build('/ebcms/link/jump', ['key'=>$vo['key']])}">{$vo.key}</a>
                    </td>
                    <td>{$vo.click_today}</td>
                    <td>{$vo.click_yesterday}</td>
                    <td>{$vo.click_before_yesterday}</td>
                    <td>{$vo.click_total}</td>
                    <td>
                        <a href="{echo $router->build('/ebcms/link/url/update', ['id'=>$vo['id']])}">编辑</a>
                        <a href="{echo $router->build('/ebcms/link/log/index', ['url_id'=>$vo['id']])}">记录</a>
                        <a href="{echo $router->build('/ebcms/link/url/delete', ['id'=>$vo['id']])}" onclick="return confirm('确定删除吗？删除后不可恢复！');">删除</a>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
    <nav>
        <ul class="pagination">
            {foreach $pages as $v}
            {if $v=='...'}
            <li class="page-item disabled"><a class="page-link" href="javascript:void(0);">{$v}</a></li>
            {elseif isset($v['current'])}
            <li class="page-item active"><a class="page-link" href="javascript:void(0);">{$v.page}</a></li>
            {else}
            <li class="page-item"><a class="page-link" href="{echo $router->build('/ebcms/link/url/index', array_merge($request->get(), ['page'=>$v['page']]))}">{$v.page}</a></li>
            {/if}
            {/foreach}
        </ul>
    </nav>
</div>
{include common/footer@ebcms/admin}