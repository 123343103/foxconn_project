<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/2/27
 * Time: 10:34
 */
use \yii\helpers\Url;
use app\classes\Menu;
?>
<div id="tabs" class="easyui-tabs" style="width:980px;height:auto;">
    <div title="回访记录">
        <div id="record"></div>
    </div>
    <div title="活动信息">
        <div id="active"></div>
    </div>
    <div title="提醒事项">
        <div id="reminder"></div>
    </div>
    <div title="通讯记录">
        <div id="message"></div>
    </div>
</div>
<script>
//    var result = eval(<?//= $result ?>//);
    var id = '<?= $id ?>';
    $(function(){
        $("#tabs").tabs({
            tabPosition:'top',
            height:'auto'
        });
        $("#record").datagrid({
            url: "<?= Url::to(['/crm/crm-member/load-record']) ;?>?id="+id,
            rownumbers: true,
            method: "get",
            idField: "sil_id",
//            loadFilter:pagerFilter,
            loadMsg: '加载中...',
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                <?= $record ?>
            ]],
            onSelect:function(rowIndex,rowData){
                if(rowData.sil_id == null){
                    $("#record").datagrid('unselectRow',0);
                    return false;
                }
//                if(rowData.end < getNowFormatDate()){
//                    $("#delete,#update").hide();
//                }else{
//                    $("#delete,#update").show();
//                }.datagrid('loadData', result.record)
            },
            onLoadSuccess: function (data) {
                datagridTip('#record');
                showEmpty($(this),data.total,0);
                $("#record").datagrid("loaded");
            }
        });

//        $('#record').datagrid({loadFilter:pagerFilter});.datagrid('loadData', result.reminder)
        $("#reminder").datagrid({
            url: "<?= Url::to(['load-reminder']) ;?>?id="+id,
            rownumbers: true,
//            loadFilter:pagerFilter,
            method: "get",
            idField: "imesg_id",
            loadMsg: '加载中...',
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                <?= $reminder ?>
                {
                    field: "action", title: "操作", width: 100, formatter: function (val, row) {
                    if(row.imesg_status == '1'){
                        var cur_time = '<?= time() ?>';
                        var btime = Date.parse(row.imesg_btime.replace(/-/g,'/'))/1000;
                        var etime = Date.parse(row.imesg_etime.replace(/-/g,'/'))/1000;
                        if(btime < cur_time && etime > cur_time){
                            return '<?= Menu::isAction('/crm/crm-member/update-reminders')? "<a id=\"reminder_update\" onclick=\"reminder_update('+ row.imesg_id +')\"><i class=\"icon-edit fs-14\"></i></a>":''; ?>'
                        }else if(btime > cur_time){
                            return '<?= Menu::isAction('/crm/crm-member/delete-reminders')?"<a onclick=\"reminder('+ row.imesg_id  +')\"><i class=\"icon-minus-sign fs-18\"></i></a>&nbsp;&nbsp;":'' ?>' + '<?= Menu::isAction('/crm/crm-member/update-reminders')?"<a id=\"reminder_update\" onclick=\"reminder_update('+ row.imesg_id + ')\"><i class=\"icon-edit fs-18\"></i></a>":'' ?>';
                        }
                    }
                    }
                },
            ]],
            onLoadSuccess: function (data) {
                showEmpty($(this),data.total,0);
                datagridTip('#reminder');
                $("#reminder").datagrid("loaded");
            }
        });
        $("#active").datagrid({
            url: "<?= Url::to(['load-active']) ;?>?id="+id,
//            loadFilter:pagerFilter,
            rownumbers: true,
            method: "get",
            idField: "acth_id",
            loadMsg: '加载中...',
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                <?= $active ?>
            ]],
            onLoadSuccess: function (data) {
                showEmpty($(this),data.total,0);
                datagridTip('#active');
                $("#active").datagrid("loaded");
            }
        });
//        $('#active').datagrid({loadFilter:pagerFilter}).datagrid('loadData', result.active);
        $("#message").datagrid({
            url: "<?= Url::to(['load-message']) ;?>?id="+id,
//            loadFilter:pagerFilter,
            rownumbers: true,
            method: "get",
            idField: "imesg_id",
            loadMsg: '加载中...',
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                <?= $message ?>
            ]],
            onLoadSuccess: function (data) {
                showEmpty($(this),data.total,0);
                datagridTip('#message');
                $("#message").datagrid("loaded");
            }
        });
//        $('#message').datagrid({loadFilter:pagerFilter}).datagrid('loadData', result.message);
    });

    /**
     * 关闭提醒事项
     * @param id
     */
//    function reminder_close(id){
//        var url = "<?//= Url::to(['/crm/crm-member/close-reminders']) ?>//";
//        reminderClose(id,url);
//    }

    /**
     *
     * 修改提醒事项
     * @param id
     */
    function reminder_update(id){
        $("#reminder_update").fancybox({
            padding: [],
            fitToView: false,
            width: 730,
            height: 450,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= Url::to(['update-reminders']) ?>?id=" + id
        });
    }
    /**
     *
     * 删除提醒事项
     * @param id
     */
    function reminder(id){
        var index = layer.confirm("确定要删除这条记录吗?",
            {
                btn: ['确定', '取消'],
                icon: 2
            },
            function () {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    async: false,
                    data: {"id": id},
                    url: "<?= Url::to(['delete-reminders']) ?>",
                    success: function (msg) {
                        if (msg.flag === 1) {
                            layer.alert(msg.msg, {
                                icon: 1, end: function () {
                                    location.reload();
                                }
                            });
                        } else {
                            layer.alert(msg.msg, {icon: 2})
                        }
                    },
                    error: function (msg) {
                        layer.alert(msg.msg, {icon: 2})
                    }
                })
            },
            function () {
                layer.closeAll();
            }
        )
    }

</script>
