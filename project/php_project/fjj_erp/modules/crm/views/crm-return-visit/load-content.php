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
    <div title="拜访记录">
        <div id="record"></div>
    </div>
    <div title="提醒事项">
        <div id="reminder"></div>
    </div>
</div>
<script>
//    var result = eval(<?//= $result ?>//);
    var id = '<?= $id ?>';
    $(function () {
        $("#tabs").tabs({
            tabPosition: 'top',
            height: 'auto'
        });
        $("#record").datagrid({
            url: "<?= Url::to(['/crm/crm-return-visit/load-record']) ;?>?id="+id,
            rownumbers: true,
            method: "get",
            idField: "sil_id",
//            loadFilter: pagerFilter,
            loadMsg: '加载中...',
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                <?= $record ?>
                {field: "action", title: "操作", width: 100, formatter: function (val, row,index) {
                    if(index == 0){
//                        return '<a href="<?//= Url::to(['update']) ?>//?id='+ row.sih_id + '&childId='+row.sil_id +'"><i class="icon-edit fs-18"></i></a>&nbsp;&nbsp;<a onclick="cancleVisit('+ row.sih_id + ',' +row.sil_id +')"><i class="icon-minus-sign fs-18"></i></a>';
                        return '<?= Menu::isAction('/crm/crm-return-visit/delete')?"<a onclick=\"cancleVisit('+ row.sih_id + ',' +row.sil_id +')\"><i class=\"icon-minus-sign fs-18\"></i></a>&nbsp;&nbsp;":'' ?>' + '<?= Menu::isAction('/crm/crm-return-visit/update')?"<a onclick=\"visitUpdate('+ row.sih_id + ',' +row.sil_id  +')\"><i class=\"icon-edit fs-18\"></i></a>":'' ?>';
                    }
                }
                },
            ]],
            onSelect:function(rowIndex,rowData){

            },
            onLoadSuccess: function (data) {
                datagridTip('#record');
                showEmpty($(this),data.total,0);
                setMenuHeight();
                $("#record").datagrid("loaded");
            }
        });
//        $('#record').datagrid({loadFilter: pagerFilter});.datagrid('loadData', result.record).datagrid('loadData', result.reminder)
        $("#reminder").datagrid({
            url: "<?= Url::to(['load-reminder']) ;?>?id="+id,
            rownumbers: true,
            method: "get",
            idField: "imesg_id",
//            loadFilter: pagerFilter,
            loadMsg: '加载中...',
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                <?= $reminder ?>
                {
                    field: "action", title: "操作", width: 100, formatter: function (val, row) {
//                    return '<a onclick="reminder('+ row.imesg_id +')">删除</a>';
                    if(row.imesg_status == '1'){
//                        return '<a onclick="reminder_close('+ row.imesg_id +')">关闭</a>';
                        var cur_time = '<?= time() ?>';
                        var btime = Date.parse(row.imesg_btime.replace(/-/g,'/'))/1000;
                        var etime = Date.parse(row.imesg_etime.replace(/-/g,'/'))/1000;
                        if(btime < cur_time && etime > cur_time){
                            return '<?= Menu::isAction('/crm/crm-member/update-reminders')? "<a id=\"reminder_update\" onclick=\"reminder_update('+ row.imesg_id +')\"><i class=\"icon-edit fs-14\"></i></a>":''; ?>'
                        }else if(btime > cur_time){
                            return '<?= Menu::isAction('/crm/crm-member/delete-reminders')?"<a onclick=\"reminder('+ row.imesg_id  +')\"><i class=\"icon-minus-sign fs-18\"></i></a>&nbsp;&nbsp;":'' ?>' + '<?= Menu::isAction('/crm/crm-member/update-reminders')?"<a id=\"reminder_update\" onclick=\"reminder_update('+ row.imesg_id + ')\"><i class=\"icon-edit fs-18\"></i></a>":'' ?>';
                        }
                    }else{
//                        return '<a onclick="reminder('+ row.imesg_id +')">删除</a>';
                    }
                }
                },
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#reminder');
                showEmpty($(this),data.total,0);
                setMenuHeight();
                $("#reminder").datagrid("loaded");
            }
        });
        var p = $('#reminder').datagrid('getPager');    //分页
        $(p).pagination({
            pageSize: 5,

            beforePageText: '第',
            afterPageText: '页   共 {pages} 页',
            displayMsg: '当前显示 {from} - {to} 条记录   共 {total} 条记录',
        });
//        $('#reminder').datagrid({loadFilter: pagerFilter});
    })
    function visitUpdate($id,$silId){
        window.location.href = "<?=Url::to(['update'])?>?id=" + $id +'&childId=' + $silId;
    }

    function reminder_close(id){
        var url = "<?= Url::to(['/crm/crm-member/close-reminders']) ?>";
        reminderClose(id,url);
    }
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
                    url: "<?=\yii\helpers\Url::to(['/crm/crm-return-visit/delete-reminders']) ?>",
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

    function cancleVisit(id,cid){
        layer.confirm("确定要删除这条记录吗?",
            {
                btn: ['确定', '取消'],
                icon: 2
            },
            function () {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    async: false,
                    data: {"id": id,'childId':cid},
                    url: "<?=\yii\helpers\Url::to(['delete']) ?>",
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

function reminder_update(id) {
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
        href: "<?= Url::to(['/crm/crm-member/update-reminders']) ?>?id=" + id
    });
}
</script>
