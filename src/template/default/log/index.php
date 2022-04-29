{include common/header@ebcms/admin}
<div class="container">
    <div class="my-4">
        <div class="h1">跳转记录</div>
    </div>
    <div class="my-3"></div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">KEY</th>
                    <th scope="col">时间</th>
                    <th scope="col">IP</th>
                    <th scope="col">地区</th>
                    <th scope="col">UA</th>
                    <th scope="col">跳转地址</th>
                </tr>
            </thead>
            <tbody>
                {foreach $logs as $vo}
                <tr>
                    <td>{$vo['urlx']['key']}</td>
                    <td>{:date('Y-m-d H:i:s', $vo['time'])}</td>
                    <td>
                        <a href="{echo $router->build('/ebcms/link/log/index', ['remote_addr'=>$vo['remote_addr']])}">{$vo.remote_addr}</a>
                    </td>
                    <td>
                        <span>{$vo.region}</span>
                    </td>
                    <td>
                        <span class="ua text-muted" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-html="true" data-bs-content="{$vo.http_user_agent}">[UA]</span>
                    </td>
                    <td>
                        {if !$vo['state']}
                        <span class="text-warning" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-html="true" data-bs-content="链接停用，已阻止跳转">[!]</span>
                        {/if}
                        {if $vo['http_referer']}
                        <span class="text-danger" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-html="true" data-bs-content="<span class='text-muted mr-1'>来源地址:</span><br>{$vo.http_referer}">[REF]</span>
                        {/if}
                        <a href="{echo $router->build('/ebcms/link/log/index', ['url_id'=>$vo['url_id']])}" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-html="true" data-bs-content="{$vo.url}">{$vo['urlx']['tips']}</a>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/mobile-detect@1.4.5/mobile-detect.min.js"></script>
    <script>
        $(function() {
            $.each($('.ua'), function(index, value) {
                var md = new MobileDetect($(value).data('bs-content'));
                var res = [];
                if (md.phone()) {
                    res.push("<span class=\"ms-1 text-danger\">" + md.phone() + "</span>");
                }
                if (md.os()) {
                    res.push("<span class=\"ms-1 text-success\">" + md.os() + "</span>");
                }
                if (md.userAgent()) {
                    res.push("<span class=\"ms-1 text-info\">" + md.userAgent() + "</span>");
                }
                $(value).after(res.join('<span class="text-muted">,</span>'));
            });
        });
    </script>
    <div class="my-3"></div>
    <nav>
        <ul class="pagination">
            {foreach $pagination as $v}
            {if $v=='...'}
            <li class="page-item disabled"><a class="page-link" href="javascript:void(0);">{$v}</a></li>
            {elseif isset($v['current'])}
            <li class="page-item active"><a class="page-link" href="javascript:void(0);">{$v.page}</a></li>
            {else}
            <li class="page-item"><a class="page-link" href="{echo $router->build('/ebcms/link/log/index', array_merge($_GET, ['page'=>$v['page']]))}">{$v.page}</a></li>
            {/if}
            {/foreach}
        </ul>
    </nav>
</div>
{include common/footer@ebcms/admin}