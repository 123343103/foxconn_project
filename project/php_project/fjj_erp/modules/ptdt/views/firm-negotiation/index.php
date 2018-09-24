<?php
use yii\helpers\Url;
use yii\grid\GridView;
use app\modules\ptdt\models\PdNegotiation;
$this->title = '厂商谈判';
$this->params['homeLike'] = ['label'=>'商品开发管理','url'=>''];
$this->params['breadcrumbs'][] = ['label' => '商品开发进程', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '谈判履历列表', 'url' => ""];
?>
<div class="content">
    <?php echo $this->render('_search', [
        'downList' =>$downList,
        'queryParam'=>$queryParam
    ]); ?>

    <div class="table-content">
        <?php  echo $this->render('_action'); ?>
        <div id="data"></div>
        <div class="space-30"></div>
         <div id="load-title"></div>
        <div class="space-10"></div>
        <div id="load-content" class="overflow-auto">
        </div>
    </div>
</div>
<script>
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers :true,
            method: "get",
            idField: "pdn_id",
            loadMsg: "加载中...",
            pagination: true,
            singleSelect: true,
            selectOnCheck:false,
            checkOnSelect:false,
            pageSize: 5,
            pageList: [5,10,15],
            columns: [[
                {field: 'ck',checkbox:true},
//                {field: "pdn_code", title: "编号", width: 200,formatter:function(val,row){
//                    return '<a href="<?//= Url::to(['view']) ?>//?id='+ row.pdn_id +'">'+ val +'</a>';
//                }},
//                {field: "firm_sname", title: "公司全称", width: 100},
//                {field: "firm_shortname", title: "简称", width: 100},
//                {field: "firm_salarea", title: "分级分类", width: 100},
//                {field: "firm_brand", title: "品牌", width: 100},
//                {field: "firm_issupplier", title: "是否为集团供应商", width: 70},
//                {field: "firm_source", title: "公司来源", width: 100},
//                {field: "firm_type", title: "类型", width: 100},
//                {field: "status", title: "谈判状态", width: 100}
                <?= $columns ?>
            ]],
            onSelect: function (rowIndex, rowData) {    //选择触发事件
                $("#view,#analysis").show();
                var status = rowData['pdn_status'];
                if(status == 30){
                    $("#report").show();
                    $("#nego,#update,#delete").hide();
                }else{
                    $("#nego,#update,#delete").show();
                    $("#report").hide();
                }
                $("#load-title").html("<div class='table-head'><p class='head'>厂商谈判履历列表</p></div>");
                $("#load-content").datagrid({
                    url: "<?= Url::to(['/ptdt/firm-negotiation/load-info']) ?>?id=" + rowData['pdn_id'],
                    rownumbers :true,
                    method: "get",
                    idField: "pdnc_id",
                    loadMsg: false,
                    pagination: true,
                    singleSelect: true,
                    pageSize: 10,
                    pageList: [10,20,30],
                    columns: [[

                        {field: "pdnc_code", title: "编号", width: 205,formatter:function(val,row){
                            return '<a href="<?= Url::to(['view']) ?>?cid='+ row.pdnc_id +'">'+ val +'</a>';
                        }},
                        {field: "pdnc_date", title: "日期", width: 220, formatter: function (value, row) {
                    return row.pdnc_date+' '+row.pdnc_time
                }},
                        {field: "negotiate_concluse", title: "谈判结果", width: 250,formatter: function (value, row) {
                            switch (row.negotiate_concluse){
                                case '100018':
                                    return '谈判失败';
                                    break;
                                case '100019':
                                    return '取得代理授权';
                                    break;
                                default:
                                    return '其他';
                                    break;
                            }
                        }},
                        {field: "process_descript", title: "过程描述", width: 280}
                    ]],
                    onSelect: function (rowIndex, rowData) {    //选择触发事件
//                      var cid  = rowData['pdnc_id'];
                    },
                    onLoadSuccess: function () {
                        setMenuHeight();
                    }
                });
                $("#data").datagrid("uncheckAll");
            },
            onCheck: function(){
                $("#data").datagrid("unselectAll");
            },
            onCheckAll: function(){
                $("#data").datagrid("unselectAll");
            },
            onLoadSuccess:function(data){
                showEmpty($(this),data.total,1);
            },
        });

        //新增
        $("#create").on("click", function () {
            if ($("#data").datagrid("getSelected")==null) {
                window.location.href = "<?=Url::to(['create'])?>";
            }else{
                var pid=$("#data").datagrid("getSelected")['pdn_id'];
                window.location.href = "<?=Url::to(['create'])?>?pdnId=" + pid;
            }
        });

        //删除
        $("#delete").on("click", function () {
            var selectId = $("#data").datagrid("getSelected");
            if (selectId == null) {
                layer.alert("请点击选择一条信息",{icon:2,time:5000});
            } else {
                layer.confirm("确定要删除吗?",
                    {
                        btn:['确定', '取消'],
                        icon:2
                    },
                    function () {
                        $.ajax({
                            type: "get",
                            dataType: "json",
                            data: {"id": selectId.pdn_id},
                            url: "<?=Url::to(['delete']) ?>",
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
        });
        //查看
        $("#view").on("click", function () {
            var pid  = $("#data").datagrid("getSelected")['pdn_id'];
            var load = $("#load-content").datagrid("getSelected");
            if (load == null) {
              return  window.location.href = "<?=Url::to(['view'])?>?pid="+pid
            }
                window.location.href = "<?=Url::to(['view'])?>?cid=" +load['pdnc_id']
        });
        //谈判分析
        $("#analysis").on("click", function () {
            var rows = $('#data').datagrid('getChecked');
            var selectsId='';
            $.each(rows,function(n,value) {
                selectsId+=value.pdn_id+',';
            });
            selectsId=selectsId.substring(0,selectsId.length-1);
            if (rows.length <= 1) {
                return layer.alert("至少选中一条以上信息",{icon:2,time:5000});
            }else{
                window.location.href = "<?=Url::to(['analysis'])?>?selects=" + selectsId;
            }
        });

        $("#update").on("click", function () {
            var load = $("#load-content").datagrid("getSelected");
            var data = $("#data").datagrid("getSelected");
            if (data == null || load == null) {
                layer.alert("请点击选择一条谈判信息", {icon: 2, time: 5000});
            }else{
                if(data.pdn_status == 20){
                    layer.alert("谈判中,无法修改!", {icon: 2, time: 5000});
                }else if(data.pdn_status == 30){
                    layer.alert("谈判完成,无法修改!", {icon: 2, time: 5000});
                }else{
                    var cid = $("#load-content").datagrid("getSelected")['pdnc_id'];
                    window.location.href = "<?=Url::to(['update'])?>?cid=" + cid;
                }
            }
        })
        /*谈判完成*/
        $("#nego").on("click",function(){
            var a = $("#data").datagrid("getSelected");
            if(a == null){
                layer.alert("请选择一条谈判履历!",{icon:2,time:5000});
            } else {
                var id = $("#data").datagrid("getSelected")['pdn_id'];
                var index = layer.confirm("确定完成谈判?",
                    {
                        btn:['确定', '取消'],
                        icon:2
                    },
                    function () {
                        $.ajax({
                            type: "get",
                            dataType: "json",
                            async: false,
                            data: {"pdnId": id},
                            url: "<?=Url::to(['/ptdt/firm-negotiation/negotiate']) ?>",
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
        });

        /*新增呈报*/
        $("#report").on("click", function () {
            if ($("#data").datagrid("getSelected") == null) {
                layer.alert("请点击选择一条谈判信息", {icon: 2, time: 5000});
            }else{
                var cid = $("#data").datagrid("getSelected")['firm_id'];
                window.location.href = "<?=Url::to(['/ptdt/firm-report/add'])?>?firmId=" + cid;
            }
        });
    })

//    var childId;
//    $(function () {
//        /**
//         * 选中事件
//         * 1.开啓多选
//         * 2.加载子表url
//         * 3.加载子表的位置
//         */
//        selectable(true,"<?//= Url::to(['firm-negotiation/load-resume']) ?>//",$("#load-resume"));
//        /**
//         * 增加
//         * 1.选择器
//         * 2.url
//         * 3.{parameters:'传参参名',cid:是否需要选中子表 }
//         */
//        createButton($("#create"),"<?//=Url::to(['create'])?>//",{parameters: "pdnId"});
//        //删除
//        deleteById($("#delete"),"<?//=Url::to(['delete']) ?>//");
//        //更新
//        updateButton($("#update"),"<?//=Url::to(['update'])?>//",{parameters: "cid",child:true});
//        //查看
//        viewButton($("#view"),"<?//=Url::to(['view'])?>//",{parameters: "cid",child:true});
//
//
//        $("#analysis").on("click", function () {
//            if(selectIdArr !=null && selectIdArr.length >1){
//                window.location.href = "<?//=Url::to(['analysis'])?>//&selects=" + selectIdArr
//            }else{
//                return layer.alert("至少选中一条以上信息",{icon:2,time:5000});
//            }
//        });
//    });
</script>

