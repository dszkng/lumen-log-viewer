<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Log Viewer</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="https://www.layuicdn.com/layui-v2.5.6/css/layui.css" media="all">
    <!-- 注意：如果你直接复制所有代码到本地，上述css路径需要改成你本地的 -->
    <style>
        #files ul {
            padding: 10px;
        }

        #files ul li {
            line-height: 20px;
        }

        #files ul li.active a {
            color: #1E9FFF;
        }
    </style>
</head>
<body>
<div style="padding: 100px">
    <div class="layui-row">
        <div class="layui-col-xs2">
            <h2>Log Viewer</h2>
            <div id="files">
                <ul>
                    @foreach ($files as $index=> $fileName)
                        <li @if(!$index)class="active"@endif>
                            <a onclick="reloadLogTbl(this,{{$index}})">{{ $fileName }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="layui-col-xs10">
            <table class="layui-hide" id="test"></table>
        </div>
    </div>
</div>

<script src="https://www.layuicdn.com/layui-v2.5.6/layui.js" charset="utf-8"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->

<script>
    var $;
    var logTbl;

    layui.use('layer', function () {
        $ = layui.$ //由于layer弹层依赖jQuery，所以可以直接得到
    });

    layui.config({
        base: '/layui/opTable'
    }).extend({
        opTable: '/opTable'
    }).use(['opTable'], function () {

        // });
        //
        // layui.use('table', function () {
        //     var table = layui.table;
        logTbl = layui.opTable;

        logTbl.render({
            elem: '#test'
            , id: 'tbl'
            , url: '/log/getList'
            , cols: [[
                {
                    field: 'level', width: 100, align: 'center', title: 'Level', templet: function (d) {
                        if (d.level == 'error') {
                            return '<span class="layui-badge">Error</span>'
                        } else if (d.level == 'info') {
                            return '<span class="layui-badge layui-bg-blue">Info</span>'
                        } else {

                        }
                    }, sort: true
                }
                , {field: 'context', width: 100, align: 'center', title: 'Context', sort: true}
                , {field: 'date', width: 180, align: 'center', title: 'Date', sort: true}
                , {field: 'text', title: 'Content'}
                // , {field: 'Action', title: 'Action', width: 100}
            ]]
            , openCols: [
                {field: 'text', title: 'Content'}
                , {field: 'in_file', title: 'File'}
                , {field: 'stack', title: 'Stack'}
            ]
            , page: true
        });
    });

    function reloadLogTbl(el, index) {
        $('#files li').removeClass('active');
        $(el).parent('li').addClass('active');
        layui.table.reload('tbl', {
            page: {
                curr: 1 //重新从第 1 页开始
            }
            , where: {
                index: index
                // key: {
                //     index: index
                // }
            }
        }, 'data')
    }
</script>

</body>
</html>
