<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/2/13
 * Time: 上午 09:33
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use \app\classes\Menu;
$this->title="潜在客户列表";
$this->params['homeLike']=['label'=>'客户关系管理','url'=>['/']];
$this->params['breadcrumbs'][]=['label'=>'潜在客户列表','url'=>['index']];
//dumpE(Yii::$app->request->queryParams);
?>
<style>
    .label-width{
        width:70px;
    }
    .value-width{
        width:100px;
    }
</style>
<div class="content">
<?php $form=ActiveForm::begin([
        "method"=>"get",
        "action"=>"index"
]);?>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width qlabel-align" for="">公司名称：</label>
            <input class="value-width qvalue-align" name="cust_sname"  type="text" value="<?=\Yii::$app->request->get("cust_sname")?>">
        </div>
        <div class="inline-block">
            <label class="label-width qlabel-align">客户来源：</label>
            <?=Html::dropDownList('member_source',\Yii::$app->request->get("member_source"),$downList['customer_source'],['prompt'=>'请选择','class'=>'value-width'])?>
        </div>
        <div class="inline-block">
            <label class="label-width qlabel-align">职位职能：</label>
            <?=Html::dropDownList('cust_function',\Yii::$app->request->get("cust_function"),$downList['cust_function'],['prompt'=>'请选择','class'=>'value-width'])?>
        </div>
        <div class="inline-block">
            <label class="label-width qlabel-align" for="">经营范围：</label>
            <?=Html::dropDownList('cust_businesstype',\Yii::$app->request->get("cust_businesstype"),$downList['cust_businesstype'],['prompt'=>'请选择','class'=>'value-width','encode'=>false])?>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width qlabel-align" for="">潜在需求：</label>
            <?=Html::dropDownList('member_reqflag',\Yii::$app->request->get("member_reqflag"),$downList['member_reqflag'],['prompt'=>'请选择','class'=>'value-width'])?>
        </div>
        <div class="inline-block">
            <label class="label-width qlabel-align">省份：</label>
            <?=Html::dropDownList("province",\Yii::$app->request->get("province"),$downList["province"],["prompt"=>"请选择","class"=>"value-width disName"])?>
        </div>
        <div class="inline-block">
            <label class="label-width qlabel-align" for="">是否分配：</label>
            <select class="value-width" name="is_allot" id="">
                <option value="">请选择</option>
                <option <?=\Yii::$app->request->get("is_allot")=="Y"?"selected":""?> value="Y">已分配</option>
                <option <?=\Yii::$app->request->get("is_allot")=="N"?"selected":""?>  value="N">未分配</option>
            </select>
        </div>
        <div class="inline-block">
            <label class="label-width qlabel-align" for="">被分配者：</label>
            <?=Html::dropDownList("saleman",\Yii::$app->request->get("saleman"),$downList['allotman'],["prompt"=>"请选择","class"=>"value-width"])?>
        </div>
        <div class="inline-block">
            <button type="submit" class="search-btn-blue ml-20">查询</button>
            <button type="reset" class="reset-btn-yellow  ml-40" onclick="window.location.href='<?=Url::to(['index'])?>'">重置</button>
        </div>
    </div>
<?php $form->end();?>





    <style type="text/css">
        #claimbox{
            display: none;
        }
        textarea{
            width:100%;
        }
        button{
            font-size: 12px;
        }
        button:hover {
            cursor: pointer;
            border: 1px solid #0e0e0e;
        }
    </style>



<div class="space-10"></div>
<div class="space-10"></div>
<div class="space-10"></div>

    <?=\app\widgets\toolbar\Toolbar::widget([
        'title'=>'潜在客户列表',
        'menus'=>[
            [
                'label'=>'新增',
                'icon'=>'add-item-bgc',
                'url'=>'/crm/crm-potential-customer/create',
                'options'=>['id'=>'create','when'=>'0,1,2']
            ],
            [
                'label'=>'修改',
                'hide'=>true,
                'icon'=>'update-item-bgc',
                'url'=>'/crm/crm-potential-customer/edit',
                'options'=>['id'=>'edit','when'=>1]
            ],
            [
                'label'=>'删除',
                'icon'=>'delete-item-bgc',
                'hide'=>true,
                'url'=>'/crm/crm-potential-customer/delete',
                'options'=>['id'=>'delete','when'=>'1,2']
            ],
            [
                'label'=>'提醒事项',
                'icon'=>'setbcg2',
                'url'=>'/crm/crm-member/create-reminders',
                'options'=>['id'=>'remind','when'=>1]
            ],
            [
                'label'=>'新增拜访记录',
                'icon'=>'setting1',
                'url'=>Url::to(['visit-create']),
                'options'=>['id'=>'add-visit-record','when'=>1],
            ],
            [
                'label'=>'即时通讯',
                'icon'=>'setbcg6',
                'options'=>['when'=>'0,1,2'],
                'child'=>[
                    [
                        'label'=>'发信息',
                        'url'=>'/crm/crm-potential-customer/send-message',
                        'options'=>['id'=>'sendMsg']
                    ],
                    [
                        'label'=>'发邮件',
                        'url'=>'/crm/crm-potential-customer/send-message',
                        'options'=>['id'=>'sendEmail']
                    ]
                ]
            ],
            [
                'label'=>'数据处理',
                'icon'=>'setbcg5',
                'child'=>[
                    [
                        'label'=>'分配',
                        'hide'=>true,
                        'url'=>'/crm/crm-potential-customer/allot',
                        'options'=>['id'=>'allot','when'=>'1,2']
                    ],
                    /*[
                        'label'=>'转会员',
                        'hide'=>true,
                        'url'=>'/crm/crm-potential-customer/switch-status',
                        'options'=>['id'=>'switch_member','when'=>'1,2']
                    ],*/
                    [
                        'label'=>'转招商开发',
                        'hide'=>true,
                        'url'=>'/crm/crm-potential-customer/to-investment',
                        'options'=>['id'=>'switch_investment','when'=>'1,2']
                    ],
                    [
                        'label'=>'转销售',
                        'hide'=>true,
                        'url'=>'/crm/crm-potential-customer/to-sale',
                        'options'=>['id'=>'switch_sale','when'=>'1,2']
                    ],
                    [
                        'label'=>'批量导入',
                        'url'=>'/crm/crm-potential-customer/import',
                        'options'=>['id'=>'import','when'=>'0,1,2']
                    ],
                    [
                        'label'=>'批量导出',

                        'options'=>['id'=>'export','when'=>'0,1,2']
                    ]
                ]
            ],
            [
                'label'=>'返回',
                'icon'=>'return-item-bgc',
                'url'=>Url::home(),
                'dispose'=>'default',
                'except'=>true,
                'options'=>['id'=>'return']
            ]
        ]
    ])?>
<div class="space-10"></div>

<div id="data" class="main-table" style="width:100%;"></div>

    <div class="space-10"></div>

<div class="related-data" style="visibility: hidden;">
    <div class="easyui-tabs mt-20">
        <div title="拜访记录">
            <div id="visit-data"></div>
        </div>
        <div title="活动信息">
            <div id="act-data"></div>
        </div>
        <div title="提醒事项">
            <div id="remind-data"></div>
        </div>
        <div title="通讯记录">
            <div id="message-data"></div>
        </div>
    </div>
</div>

</div>




<div id="claimbox">
    <div class="ml-10 mt-20">
        <label class="width-100" for="">部门</label>
        <select class="width-200" name="" id="">
            <option value=""></option>
        </select>
    </div>
    <div class="ml-10 mt-10">
        <label class="width-100" for="">认领客户经理</label>
        <input class="width-200" type="text">
    </div>
    <div class="mt-20 text-center">
        <button class="button-white exit">取消认领</button>
        <button class="button-blue ensure">确定</button>
        <button class="button-white cancel">取消</button>

    </div>
</div>



<script>
    $(function(){
        $("#fileForm").ajaxForm(function(res){
            res=JSON.parse(res);
            $.fancybox.close();
            parent.layer.alert(res.msg,{icon:1});
            $("#data").datagrid("reload");
        });

        var flag=true;
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "cust_id",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            singleSelect: true,
            checkOnSelect: false,
            selectOnCheck: false,
            columns:[[
                {field:"",checkbox:true,width:200},
                <?=$columns["index"];?>
                {field:'action',title:'操作',width:'200',formatter:function(value,row,index){
                    return "<a onclick='rowRemove("+row.cust_id+")'><i class='icon-minus-sign fs-18'></i></a>&nbsp;&nbsp;&nbsp;<a onclick='rowEdit("+row.cust_id+")'><i class='icon-edit fs-18'></i></a>";
                }}
            ]],
            onLoadSuccess: function (data) {
                visible('#data');
                $("#data").datagrid("unselectAll");
                $("#data").datagrid("uncheckAll");
                datagridTip($("#data"));
                showEmpty($(this),data.total,1);
                $(".datagrid-body a").click(function(){
                    flag=false;
                });
            },
            "onSelect":function(index,row){
                $("#data").datagrid("clearChecked");
                $("#data").datagrid("checkRow",index);
                $(".related-data").css("visibility","visible");
                visible("#data");

                if(flag==false){
                    return false;
                }

                var row=$("#data").datagrid("getSelected");
                var custId=row.cust_id;
                var now = new Date();
                $("#visit-data").datagrid({
                    url: "<?=Url::to(['visit-log']);?>?id="+row.cust_id,
                    rownumbers: true,
                    method: "get",
                    idField: "sil_id",
                    loadMsg: "加载数据请稍候。。。",
                    pagination:true,
                    pageSize:5,
                    pageList:[5,10,15],
                    singleSelect: true,
                    checkOnSelect: false,
                    selectOnCheck: false,
                    columns:[[
                        <?=$columns["visit-log"];?>
                        {field:'action',title:'操作',width:'200',formatter:function(value,row,index){
                            return (index==0)?"<a onclick='visitRowRemove("+row.sih_id+","+row.sil_id+")'><i class='icon-minus-sign fs-18'></i></a>&nbsp;&nbsp;&nbsp;<a onclick='visitRowEdit("+row.sil_id+")'><i class='icon-edit fs-18'></i></a>":"";
                        }}
                    ]],
                    onLoadSuccess: function (data) {
                        datagridTip($("#visit-data"));
                        showEmpty($(this),data.total,0);
                    }
                });
                $("#act-data").datagrid({
                    url: "<?=Url::to(['act-info']);?>?id="+row.cust_id,
                    rownumbers: true,
                    method: "get",
                    idField: "acth_code",
                    loadMsg: "加载数据请稍候。。。",
                    pagination:true,
                    pageSize:5,
                    pageList:[5,10,15],
                    singleSelect: true,
                    checkOnSelect: false,
                    selectOnCheck: false,
                    columns:[[
                        <?=$columns["act-info"];?>
                    ]],
                    onLoadSuccess: function (data) {
                        datagridTip($("#act-data"));
                        showEmpty($(this),data.total,0);
                    }
                });
                $("#remind-data").datagrid({
                    url: "<?=Url::to(['remind-item']);?>?id="+row.cust_id,
                    rownumbers: true,
                    method: "get",
                    idField: "imesg_id",
                    loadMsg: "加载数据请稍候。。。",
                    pagination:true,
                    pageSize:5,
                    pageList:[5,10,15],
                    singleSelect: true,
                    checkOnSelect: false,
                    selectOnCheck: false,
                    columns:[[
                        <?=$columns["remind-item"];?>{field:"imesg_status",title:"操作",width:150,formatter:function(value,row,index){
                            if(row.imesg_status == '1'){
                                var cur_time = '<?= date("Y-m-d H:i:s",time()) ?>';
                                if(row.imesg_btime < cur_time && row.imesg_etime > cur_time){ // 正在进行中
                                    return '<a id="reminder_update" onclick="reminder_update('+ row.imesg_id +')"><i class="icon-edit fs-18"></i></a>';
                                } else if (row.imesg_btime >= cur_time) { // 未开始
                                    return '<a onclick="reminder('+ row.imesg_id +')"><i class="icon-minus-sign fs-18"></i></a> &nbsp;&nbsp; <a id="reminder_update" onclick="reminder_update('+ row.imesg_id +')"><i class="icon-edit fs-18"></i></a>';
                                } else { // 结束
                                    return '';
                                }
                            }else{
                                return '<a onclick="reminder('+ row.imesg_id +')"><i class="icon-minus-sign fs-18"></i></a>';
                            }
                        }}
                    ]],
                    onLoadSuccess: function (data) {
                        datagridTip($("#remind-data"));
                        showEmpty($(this),data.total,0);
                    }
                });
                $("#message-data").datagrid({
                    url: "<?=Url::to(['message-log']);?>?id="+row.cust_id,
                    rownumbers: true,
                    method: "get",
                    idField: "part_no",
                    loadMsg: "加载数据请稍候。。。",
                    pagination:true,
                    pageSize:5,
                    pageList:[5,10,15],
                    singleSelect: true,
                    checkOnSelect: false,
                    selectOnCheck: false,
                    columns:[[
                        <?=$columns["message-log"];?>
                    ]],
                    onLoadSuccess: function (data) {
                        datagridTip($("#message-data"));
                        showEmpty($(this),data.total,0);
                    }
                });
            },
            "onCheck":function(index,row){
                var event=arguments.callee.caller.caller.arguments[0];
                var rows=$("#data").datagrid("getChecked");
                rows.length==1 && event && $("#data").datagrid("selectRow",index);
                rows.length>1 && $("#data").datagrid("clearSelections") && $(".related-data").css("visibility","hidden");
                visible('#data');
            },
            "onUnselect":function(index,row){
                visible('#data');
            },
            "onUncheck":function(index,row){
                var event=arguments.callee.caller.caller.arguments[0];
                var rows=$("#data").datagrid("getChecked");
                event &&  $("#data").datagrid("unselectRow",index);
                if(rows.length>0){
                    var rowIndex=$("#data").datagrid("getRowIndex",rows[0]);
                    rows.length==1 && event && $("#data").datagrid("selectRow",rowIndex);
                }
                visible('#data');
            },
            "onCheckAll":function(rows){
                visible('#data');
            },
            "onUncheckAll":function(rows){
                visible('#data');
            }
        });


        //发信息
        $("#sendMsg").click(function(){
            $.fancybox({
                width:800,
                height:600,
                autoSize:false,
                padding:0,
                type:"iframe",
                href:"<?=Url::to(['send-message','type'=>1])?>"
            });
        });


        //发邮件
        $("#sendEmail").click(function(){
            $.fancybox({
                width:800,
                height:600,
                padding:0,
                autoSize:false,
                type:"iframe",
                href:"<?=Url::to(['send-message','type'=>2])?>"
            });
        });

        /*提醒事项*/
        $("#remind").on("click",function(){
            var a = $("#data").datagrid("getSelected");

            $.fancybox({
                padding: [],
                fitToView: false,
                width: 730,
                height: 450,
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe',
                href: "<?= Url::to(['crm-member/create-reminders']) ?>?id=" +a.cust_id
            });
        });

        //客户分配
        $("#allot").click(function(){
            var rows=$("#data").datagrid("getChecked");
            if(rows.length==0){
                layer.alert("请选择需分配的记录",{icon:2});
                return true;
            }
            var flag=true;
            <?php if(!\Yii::$app->user->identity->is_supper){ ?>
                for(var x=0;x<rows.length;x++){
                    if(!(rows[x].allotman_staff_id=="<?=\Yii::$app->user->identity->staff_id?>" || !rows[x].allotman_staff_id)){
                        layer.alert("您选择的客户存在被分配人，请重新选择！",{icon:2});
                        flag=false;
                    }
                    if(flag==false){
                        break;
                    }
                }
            <?php }else{ ?>
            for(var x=0;x<rows.length;x++){
                if(!(!rows[x].allotman_staff_id || rows[x].allotman_staff_id=="<?=\Yii::$app->user->identity->staff_id?>")){
                    flag=false;
                }

                if(flag==false){
                    var index=layer.confirm("确定重新分配他人的客户吗 ？",
                        {icon:2,time:5000}
                        ,function(){
                            layer.close(index);
                            $.fancybox({
                                width: 600,
                                height: 400,
                                scrolling: "no",
                                padding: 0,
                                autoSize: false,
                                type: "iframe",
                                href: "<?=Url::to(['allot'])?>"
                            });
                        });
                    break;
                }
            }
            <?php } ?>

            if(flag==true){
                $.fancybox({
                    width: 600,
                    height: 400,
                    scrolling: "no",
                    padding: 0,
                    autoSize: false,
                    type: "iframe",
                    href: "<?=Url::to(['allot'])?>"
                });
            }
        });

        //新增认领
        $("#claim").click(function(){
            var curr_user_code="<?=\Yii::$app->user->identity->staff->staff_code;?>";
            var row=$("#data").datagrid("getSelected");
            if(!row){
                layer.alert("请选择一条记录",{icon:2,time:5000});
            }else{
                var flag=true;
//                    if(row.allotman){
//                        if(row.personinch.code!=curr_user_code){
//                            layer.alert("请选择未认领的客户",{icon:2,time:5000});
//                            flag=false;
//                        }
//                    }

                if(flag==true){
                    $.fancybox({
                        width:440,
                        height:400,
                        padding:0,
                        autoSize:false,
                        type:"iframe",
                        href:"<?=Url::to(['claim'])?>?cust_id="+row.cust_id
                    });
                }
            }
        });

        //转销售
        $("#switch_sale").click(function(){
            var rows=$("#data").datagrid("getChecked");
            var customersArr=new Array();
            $.each(rows,function(i){
                customersArr.push(rows[i].cust_id);
            });
            var customers=customersArr.join(",");
            if(!customers){
                layer.alert("请选择一条记录",{icon:2,time:5000});
            }else{
                layer.confirm("确定转销售?",
                    {
                        btn:['确定', '取消'],
                        icon:2
                    },function() {
                        $.get("<?=Url::to(["to-sale"])?>?type=sale_status&customers=" + customers, function (res) {
                            var obj = JSON.parse(res);
                            if (obj.flag == 1) {
                                $("#data").datagrid("reload");
                                $("#data").datagrid("uncheckAll");
                                $("#data").datagrid("unselectAll");
                                layer.alert(obj.msg, {icon: 1, time: 1000});
                            } else {
                                layer.alert(obj.msg, {icon: 2, time: 1000});
                            }
                        });
                    }
                )
            }
        });

        //转招商
        $("#switch_investment").click(function(){
            var rows=$("#data").datagrid("getChecked");
            var customersArr=new Array();
            $.each(rows,function(i){
                customersArr.push(rows[i].cust_id);
            });
            var customers=customersArr.join(",");
            if(!customers){
                layer.alert("请选择需要的记录",{icon:2,time:5000});
            }else{
                layer.confirm("确定转招商?",
                    {
                        btn:['确定', '取消'],
                        icon:2
                    },function() {
                        $.get("<?=Url::to(["to-investment"])?>?type=investment_status&customers=" + customers, function (res) {
                            var obj = JSON.parse(res);
                            if (obj.flag == 1) {
                                $("#data").datagrid("reload");
                                $("#data").datagrid("uncheckAll");
                                $("#data").datagrid("unselectAll");
                                layer.alert(obj.msg, {icon: 1, time: 1000});
                            } else {
                                layer.alert(obj.msg, {icon: 2, time: 1000});
                            }
                        });
                    }
                )
            }
        });
//        转会员
        $("#switch_member").click(function(){
//            var rows=$("#data").datagrid("getChecked");
//            var customersArr=new Array();
//            $.each(rows,function(i){
//                customersArr.push(rows[i].cust_id);
//            });
//            var customers=customersArr.join(",");
//            if(!customers){
//                layer.alert("请选择需要的记录",{icon:2,time:5000});
//            }else{
//                layer.confirm("确定转会员?",
//                    {
//                        btn:['确定', '取消'],
//                        icon:2
//                    },function(){
//                        $.get("<?//=Url::to(["switch-status"])?>//?type=member_status&customers="+customers,function(res){
//                            var obj=JSON.parse(res);
//                            if(obj.flag==1){
//                                $("#data").datagrid("reload");
//                                $("#data").datagrid("uncheckAll");
//                                $("#data").datagrid("unselectAll");
//                                layer.alert("操作成功",{icon:1,time:1000});
//                            }else{
//                                layer.alert("操作失败",{icon:2,time:1000});
//                            }
//                        });
//                    }
//                )
//            }







                var index = layer.confirm("确定要转会员吗?",
                    {
                        btn:['确定', '取消'],
                        icon:2
                    },function(){
                        layer.closeAll('dialog');
                        var a = $("#data").datagrid("getSelected");
                        $.fancybox({
                            width:700,
                            height:450,
                            autoSize:false,
                            padding:0,
                            type:"iframe",
                            href:"<?=Url::to(['/crm/crm-member-develop/turn-member'])?>?id="+a.cust_id+"&from=/crm/crm-potential-customer/index"
                        });
                    }
                )
        });

        //添加拜访记录
        $("#add-visit-record").click(function(){
            var a = $("#data").datagrid("getSelected");
            var b = $("#data").datagrid("getChecked");
            var url = "<?= Url::to(['visit-create']) ?>";
            if(b.length == 0 && a == null){
                layer.alert("请点击选择一条数据!",{icon:2,time:5000});
                return false;
            }else if(a != null){
                window.location.href = url+"?id=" + a.cust_id + "&ctype=4";
            }else if(b.length == 1){
                $.each(b, function (index, val) {
                    id = val.cust_id;
                });
                window.location.href = url+"?id=" + id + "&ctype=4";
            }else if(b.length != 1){
                layer.alert("请点击选择一条会员信息!",{icon:2,time:5000});
                return false;
            }
        });

        //修改拜访记录
//        $("#edit-visit-record").on('click',function(){
//            var a = $("#data").datagrid("getSelected");
//            var b = $("#data").datagrid("getChecked");
//            var c = $("#visit-data").datagrid('getSelected');
//            if(b.length == 0 && a == null){
//                layer.alert("请点击选择一条数据!",{icon:2,time:5000});
//            }else if(a != null){
//                if(c == null){
//                    layer.alert("请点击选择一条拜访计划信息!", {icon: 2, time: 5000});
//                }else{
//                    if($("#visit-data").datagrid('getRowIndex',c)>0){
//                        layer.alert("只能修改最新一条！", {icon:2,time:5000});
//                        return true;
//                    }
//                    window.location.href = "<?//=Url::to(['/crm/crm-member-develop/visit-update'])?>//?id=" + a.cust_id + "&childId=" + c.sil_id + "&ctype=4";
//                }
//            }else if(b.length == 1){
//                $.each(b, function (index, val) {
//                    id = val.sih_id;
//                });
//                if(c == null){
//                    layer.alert("请点击选择一条拜访记录信息!", {icon: 2, time: 5000});
//                }else{
//                    window.location.href = "<?//=Url::to(['/crm/crm-member-develop/visit-update'])?>//?id=" + id + "&childId=" + c.sil_id + "&ctype=4";
//                }
//            }else if(b.length != 1){
//                layer.alert("请点击选择一条会员信息!",{icon:2,time:5000});
//            }
//        });
//
//        //删除拜访记录
//        $("#delete-visit-record").click(function(){
//            var a = $("#data").datagrid("getSelected");
//            var b = $("#data").datagrid("getChecked");
//            var c = $("#visit-data").datagrid('getSelected');
//            if(a==null && b.length == 0){
//                layer.alert("请先选择一条数据!",{icon:2,time:5000});
//            }else if(a != null){
//                if (c == null) {
//                    layer.alert("请先选择一条拜访记录信息！", {icon:2,time:5000});
//                } else {
//                    if($("#visit-data").datagrid('getRowIndex',c)>0){
//                        layer.alert("只能删除最新一条！", {icon:2,time:5000});
//                        return true;
//                    }
//                    layer.confirm("确定删除这条拜访记录吗?",
//                        {
//                            btn:['确定', '取消'],
//                            icon:2
//                        },
//                        function () {
//                            $.ajax({
//                                type: "get",
//                                dataType: "json",
//                                data: {"id": c.sih_id,"childId": c.sil_id},
//                                url: "<?//= Url::to(['/crm/crm-return-visit/delete']) ?>//",
//                                success: function (msg) {
//                                    if( msg.flag === 1){
//                                        layer.alert(msg.msg,{icon:1,end:function(){location.reload();}});
//                                    }else{
//                                        layer.alert(msg.msg,{icon:2})
//                                    }
//                                },
//                                error :function(msg){
//                                    layer.alert(msg.msg,{icon:2})
//                                }
//                            })
//                        },
//                        function () {
//                            layer.closeAll();
//                        }
//                    )
//                }
//            }else if(b.length == 1){
//                $.each(c, function (index, val) {
//                    id = val.sih_id;
//                });
//                if (c == null) {
//                    layer.alert("请先选择一条拜访记录信息！", {icon:2,time:5000});
//                } else {
//                    layer.confirm("确定删除这条拜访记录吗?",
//                        {
//                            btn:['确定', '取消'],
//                            icon:2
//                        },
//                        function () {
//                            $.ajax({
//                                type: "get",
//                                dataType: "json",
//                                data: {"id": id,"childId": c.sil_id},
//                                url: "<?//= Url::to(['/crm/crm-return-visit/delete']) ?>//",
//                                success: function (msg) {
//                                    if( msg.flag === 1){
//                                        layer.alert(msg.msg,{icon:1,end:function(){location.reload();}});
//                                    }else{
//                                        layer.alert(msg.msg,{icon:2})
//                                    }
//                                },
//                                error :function(msg){
//                                    layer.alert(msg.msg,{icon:2})
//                                }
//                            })
//                        },
//                        function () {
//                            layer.closeAll();
//                        }
//                    )
//                }
//            }else if(b.length != 1){
//                layer.alert("请点击选择一条会员信息!",{icon:2,time:5000});
//            }
//        });

        //数据导出
        $("#export").click(function(){
            window.location.href="<?=Url::to(['export','queryParams'=>http_build_query(Yii::$app->request->queryParams),'export'=>1])?>";
        });

        //数据导入
        $("#import").click(function(){
            $.fancybox({
                type:"iframe",
                href:"<?=Url::to(['import'])?>",
                padding:0,
                autoSize:false,
                width:500,
                height:200
            });
        });

        //新增潜在客户
        $("#create").click(function(){
            window.location.href="<?=Url::to(['create']);?>";
        });

        //修改潜在客户
        $("#edit").click(function(){
            var row=$("#data").datagrid("getSelected");
            if(!row){
                layer.alert("请选中一条纪录",{icon:2,time:5000});
            }
            window.location.href="<?=Url::to(['edit'])?>?id="+row.cust_id;
        });

        //潜在客户详情
        $("#view").click(function(){
            var row=$("#data").datagrid("getSelected");
            if(!row){
                layer.alert("请选中一条纪录",{icon:2,time:5000});
            }else{
                window.location.href="<?=Url::to(['view']);?>?id="+row.cust_id;
            }
        });

        //删除潜在客户
        $("#delete").click(function(){
            var row=$("#data").datagrid("getSelected");
            if(!row){
                layer.alert("请选中一条纪录",{icon:2,time:5000});
            }else{
                layer.confirm("确定要删除？",{
                    btn:['确定', '取消'],
                    icon:2
                },function(){
                    $.get("<?=Url::to(['delete'])?>?id="+row.cust_id,function(res){
                        obj=JSON.parse(res);
                        if(obj.flag==1){
                            $("#data").datagrid("reload");
//                            $("#data").datagrid("deleteRow",$("#data").datagrid("getRowIndex",row));
                            layer.alert(obj.msg,{icon:1,time:5000});
                        }else{
                            layer.alert(obj.msg,{icon:2,time:5000});
                        }
                    });
                });
            }
        });

        //关闭弹窗
        $(".cancel,.exit").click(function(){
            $.fancybox.close();
        });
    });
//    function remindDo(index,act){
//        $("#remind-data").datagrid("selectRow",index);
//        var row=$("#remind-data").datagrid("getSelected");
//            $.ajax({
//                type:"get",
//                url:"<?//=Url::to(['remind-do'])?>//",
//                data:{id:row.imesg_id,act:act},
//                dataType:"json",
//                success:function(res){
//                    if(res.flag==1){
//                        $("#remind-data").datagrid("reload");
//                    }
////                    layer.alert(res.msg,{icon:1,time:5000});
//                }
//            });
//    }


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
            href: "<?= Url::to(['/crm/crm-member/update-reminders']) ?>?id=" + id+'&from=<?=Url::to(['index'])?>'
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
                    url: "<?= Url::to(['/crm/crm-return-visit/delete-reminders']) ?>",
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


    function  rowEdit(id) {
        window.location.href="<?=Url::to(['edit'])?>?id="+id;
    }
    function  rowRemove(id) {
        layer.confirm("确定要删除？",{
            btn:['确定', '取消'],
            icon:2
        },function(){
            $.get("<?=Url::to(['delete'])?>?id="+id,function(res){
                obj=JSON.parse(res);
                if(obj.flag==1){
                    $("#data").datagrid("deleteRow",$("#data").datagrid("getRowIndex",row));
                    layer.alert(obj.msg,{icon:1,time:5000});
                }else{
                    layer.alert(obj.msg,{icon:2,time:5000});
                }
            });
        });
    }
    function visitRowEdit(id) {
        var row =$("#data").datagrid("getSelected");
        window.location.href="<?=Url::to(['visit-update'])?>?id="+row.cust_id+"&childId="+id+"&ctype=4";
    }
    function visitRowRemove(sih_id,sil_id) {
        layer.confirm("确定删除这条拜访记录吗?",
            {
                btn:['确定', '取消'],
                icon:2
            },
            function () {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    data: {"id": sih_id,"childId": sil_id},
                    url: "<?= Url::to(['delete-visit']) ?>",
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
