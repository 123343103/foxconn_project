<?php
/**
 * F1678086
 * 2017/03/03
 */
$this->title = "待办事项消息提醒";
?>
    <h2 class="head-first"><?= $this->title ?></h2>
    <div class="width-460 ml-20">
        <div id="data" style="width:460px;"></div>
    </div>
    <div class="space-30"></div>
    <div class="text-center mt-10">
        <button class="button-white-big ml-20" onclick="close_refresh()">退出</button>
    </div>
    <a id="aaa" class="display-none">click here</a>
    <input type="hidden" id="ccc">
    <input type="hidden" id="ddd">
<script>
    $(function(){
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "imesg_id",
            loadMsg: "加载中...",
            pagination: true,
            pageSize: 6,
            pageList: [6, 12, 18],
            singleSelect: true,
            columns: [[
//                {field: "cust_sname", title: "厂商", width: 150},
//                {field: "imesg_btime", title: "开始时间", width: 150},
//                {field: "imesg_etime", title: "结束时间", width: 150},
//                {field: "imesg_notes", title: "内容", width: 135},
                {field: "content_all", title: "内容", width: 420, formatter: function (value, row, index) {
                    if (row.cust_sname || row.imesg_btime || row.imesg_notes) {
                        return (row.cust_sname||'')+' '+(row.imesg_btime.substr(0,10)||'')+' '+(row.imesg_notes||'');
                    } else {
                        return '<span class="red">暂无工作安排!</span>'
                    }
                }},
                {field: "cust_id", title: "客户ID", width: 0,hidden:true}
            ]],
            onClickRow:function(rowIndex,rowData){
                $("#ccc").val(rowData['cust_id']);
                $("#ddd").val(rowData['imesg_id']).subst;
                $("#aaa").click();
            },
            onLoadSuccess:function(data){
                showEmpty($(this),data.total,0);
            }
        })
//        $('.datagrid-header').css({"visibility":"hidden","position":"absolute"});
    })
    $("#aaa").on('click',function(){
        var id = $("#ccc").val();
        var mid = $("#ddd").val();
        $("#aaa").attr("href", "<?= \yii\helpers\Url::to(['view']) ?>?id="+id+"&mid="+mid);
        $("#aaa").fancybox({
            padding: 0,
            margin:0,
            fitToView: true,
            width: 500,
            height: 385,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe'
        });
    })
    function close_refresh() {
//        parent.$.fancybox.close();
        parent.window.location.reload();
    }

</script>
