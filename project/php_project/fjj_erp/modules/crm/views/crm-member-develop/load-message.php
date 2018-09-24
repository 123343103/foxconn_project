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
<div id="tabs" class="easyui-tabs" style="width:100%;height:auto;">
    <div title="拜访记录">
        <div id="record"></div>
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
            url: "<?= Url::to(['/crm/crm-member-develop/load-record']) ;?>?id="+id,
            rownumbers: true,
            method: "get",
            idField: "sil_id",
            loadMsg: '加载中...',
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                <?= $record ?>
                {field: "action", title: "操作", width: 100, formatter: function (val, row,index) {
                    if(index == 0){
                        return '<?= Menu::isAction('/crm/crm-member-develop/delete')?"<a onclick=\"cancleVisit('+ row.sih_id + ',' +row.sil_id +')\"><i class=\"icon-minus-sign fs-18\"></i></a>&nbsp;&nbsp;":'' ?>' + '<?= Menu::isAction('/crm/crm-member-develop/visit-update')?"<a onclick=\"visitUpdate('+ row.custId + ',' +row.sil_id  +')\"><i class=\"icon-edit fs-18\"></i></a>":'' ?>';
                    }
                }
                },
            ]],
            onSelect:function(rowIndex,rowData){
//                if(rowData.end < getNowFormatDate()){
//                    $("#delete,#updateVisit").hide();
//                }else{
//                    $("#delete,#updateVisit").show();
//                }
            },
            onLoadSuccess: function (data) {
                datagridTip('#record');
                showEmpty($(this),data.total,0);
            }
        });
//        $('#record').datagrid({loadFilter:pagerFilter}).datagrid('loadData', result.record);
        $("#reminder").datagrid({
            url: "<?= Url::to(['load-reminder']) ;?>?id="+id,
            rownumbers: true,
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
//                    return '<a onclick="reminder('+ row.imesg_id +')">删除</a>';
                    if(row.imesg_status == '1'){
                        var cur_time = '<?= time() ?>';
                        var btime = Date.parse(row.imesg_btime.replace(/-/g,'/'))/1000;
                        var etime = Date.parse(row.imesg_etime.replace(/-/g,'/'))/1000;
                        if(btime < cur_time && etime > cur_time){
                            return '<a id="reminder_update" onclick="reminder_update('+ row.imesg_id +')"><i class="icon-edit fs-18"></i></a>';
                        }else if(btime > cur_time){
                            return '<a onclick="reminder('+ row.imesg_id +')"><i class="icon-minus-sign fs-18"></i></a> &nbsp;&nbsp; <a id="reminder_update" onclick="reminder_update('+ row.imesg_id +')"><i class="icon-edit fs-18"></i></a>';
                        }
                    }
                }
                },
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#reminder');
                showEmpty($(this),data.total,0);
            }
        });
//        $('#reminder').datagrid({loadFilter:pagerFilter}).datagrid('loadData', result.reminder);
        $("#message").datagrid({
            url: "<?= Url::to(['load-message']) ;?>?id="+id,
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
                datagridTip('#message');
                showEmpty($(this),data.total,0);
            }
        });
//        $('#message').datagrid({loadFilter:pagerFilter}).datagrid('loadData', result.message);
    })
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
                    url: "<?=Url::to(['delete-reminders']) ?>",
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
    var myview = $.extend({},$.fn.datagrid.defaults.view,{
        onAfterRender:function(target){
            $.fn.datagrid.defaults.view.onAfterRender.call(this,target);
            var opts = $(target).datagrid('options');
            var vc = $(target).datagrid('getPanel').children('div.datagrid-view');
            vc.children('div.datagrid-empty').remove();
            if (!$(target).datagrid('getRows').length){
                var d = $('<div class="datagrid-empty"></div>').html(opts.emptyMsg || 'no records').appendTo(vc);
                d.css({
                    position:'absolute',
                    left:0,
                    top:50,
//                    width:'100%',
//                    textAlign:'center',
                    height:'35px'
                });
            }
        }
    });

    function visitUpdate($id,$silId){
        window.location.href = "<?=Url::to(['visit-update'])?>?id=" + $id +'&childId=' + $silId + '&ctype=1';
    }

    /*删除拜访记录*/
    function cancleVisit($sihId,$silId){
        layer.confirm("确定删除这条拜访记录吗?",
            {
                btn:['确定', '取消'],
                icon:2
            },
            function () {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    data: {"id": $sihId,"childId": $silId},
                    url: "<?= Url::to(['delete']) ?>",
                    success: function (msg) {
                        if( msg.flag === 1){
                            layer.alert(msg.msg,{icon:1,end:function(){location.reload();}});
                        }else{
                            layer.alert(msg.msg,{icon:2})
                        }
                    },
                    error :function(msg){
                        layer.alert(msg.msg,{icon:2})
                    }
                })
            },
            function () {
                layer.closeAll();
            }
        )
    }
</script>
