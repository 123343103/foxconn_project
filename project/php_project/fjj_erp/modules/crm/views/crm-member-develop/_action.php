<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/1/12
 * Time: 10:12
 */
use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;
?>
<style>
    .menu-one span{
        width:95px;
        padding: 0 2px 0 2px !important;
    }
    #m-data,#m-data2{
        width:88px !important;
    }

    #m-data a,#m-data2 a{
        width: 85px !important;
        display: block;
    }
</style>
<div class="table-head">
    <p class="head">会员开发列表</p>
    <div class="float-right">
        <?= Menu::isAction('/crm/crm-member-develop/create')?
//            Html::a("<span>新增</span>",null, ['id' => 'create'])
            "<a id='create' href='".Url::to(['create'])."'>
                    <div class='table-nav'>
                        <p class='add-item-bgc float-left'></p>
                        <p class='nav-font'>新增</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            :'' ?>
        <?= Menu::isAction('/crm/crm-member-develop/update')?
//            Html::a("<span class='text-center ml--5'>修改</span>", null,['id'=>'update','class'=>'display-none'])
            "<a id='update' class='display-none'>
                    <div class='table-nav'>
                        <p class='update-item-bgc float-left'></p>
                        <p class='nav-font'>修改</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            :'' ?>

        <?= Menu::isAction('/crm/crm-member-develop/visit-create')?
//            Html::a("<span class='text-center ml--5'>新增拜访记录</span>", null,['id'=>'addVisit','class'=>'display-none'])
            "<a id='addVisit' class='display-none'>
                    <div class='table-nav'>
                        <p class='setting1 float-left'></p>
                        <p class='nav-font'>新增拜访记录</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            :'' ?>
        <?= Menu::isAction('/crm/crm-member-develop/create-reminders')?
//            Html::a("<span class='width-70 text-center ml--5'>提醒事项</span>", null, ['id' => 'reminders'])
            "<a id='reminders' class='display-none'>
                    <div class='table-nav'>
                        <p class='setbcg2 float-left'></p>
                        <p class='nav-font'>提醒事项</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            :'' ?>
<!--        <a href="javascript:void(0)" id="m-send" class="menu-one text-center ml--5 width-70 display-none">即时通讯</a>-->
<!--        <a href="javascript:void(0)" id="m-deal" class="menu-one text-center ml--5 width-70 display-none">数据处理</a>-->
        <?= Menu::isAction('/crm/crm-member-develop/send-message')?
            '<div id="m2" class="float-left">
                <a href="javascript:void(0)" id="m-send" class="menu-one text-center ml--5 width-90">
                    <div class="table-nav">
                        <p class="setbcg6 float-left"></p>
                        <p class="nav-font">即时通讯</p>
                    </div>
                    <p class="float-left">&nbsp;|&nbsp;</p>
                </a>
            </div>'
        :''?>
        <?php if(Menu::isAction('/crm/crm-member-develop/throw-sea') || Menu::isAction('/crm/crm-member-develop/turn-member') || Menu::isAction('/crm/crm-member-develop/turn-investment') || Menu::isAction('/crm/crm-member-develop/turn-sales')){ ?>
        <div id="data1" class="float-left display-none">
            <a href="javascript:void(0)" id="m-deal" class="menu-one text-center ml--5 width-90">
                <div class='table-nav width-80'>
                    <p class='setbcg5 float-left'></p>
                    <p class='nav-font'>数据处理</p>
                </div>
                <p class="float-left">&nbsp;|&nbsp;</p>
            </a>
        </div>
        <?php } ?>
        <a href="<?= Url::to(['/index/index']) ?>">
            <div class='table-nav'>
                <p class='return-item-bgc float-left'></p>
                <p class='nav-font'>返回</p>
            </div>
        </a>
    </div>
    <div id="m-data" class="display-none">
<?= Menu::isAction('/crm/crm-member-develop/throw-sea')?'<div><a id="throw_sea"><span>抛至公海</span></a></div>':'' ?>
<?= Menu::isAction('/crm/crm-member-develop/turn-member')?'<div><a onclick="member()"><span>转会员</span></a></div>':'' ?>
<?= Menu::isAction('/crm/crm-member-develop/turn-investment')?'<div><a onclick="investment()"><span>转招商开发</span></a></div>':'' ?>
<?= Menu::isAction('/crm/crm-member-develop/turn-sales')?'<div><a onclick="sales()"><span>转销售</span></a></div>':'' ?>
    </div>
    <div id="m-data2" class="display-none">
        <div><a id="sendMessage" onclick="sendMessage()"><span>发短信</span></a></div>
        <div><a id="sendEmail" onclick="sendEmail()"><span>发邮件</span></a></div>
    </div>

</div>
<script>
    $(function () {
        $('#m-deal').menubutton({
            menu: '#m-data',
            hasDownArrow:false
        });
        $('#m-send').menubutton({
            menu: '#m-data2',
            hasDownArrow:false
        });

        $('.menu-one').removeClass("l-btn l-btn-small l-btn-plain");
        $('.menu-one').find("span").removeClass("l-btn-left l-btn-text");

        //抛至公海
        $("#throw_sea").click(function(){
            var obj=$("#data").datagrid('getChecked');
            if(obj.length==0){
                layer.alert('请选择客户！',{icon:2,time:5000});
                return false;
            }
            var arrId='';
            $.each(obj,function(i,n){
                arrId+=n.cust_id+'-';
            });
            arrId=arrId.substr(0,arrId.length-1);
            layer.confirm('确定抛至公海吗？',{icon:2},
                function(){
                    $.ajax({
                        url:"<?=Url::to(['throw-sea']);?>",
                        data:{"arrId":arrId},
                        dataType:"json",
                        success:function(data){
                            if(data.status==1){
                                layer.alert("抛至成功！",{icon:1},function(){
//                                    layer.closeAll();
//                                    $("#data").datagrid('reload').datagrid('clearSelections');
//                                    $(".tabs-panels > .panel").hide();
                                    location.reload();
                                });
                            }else{
                                layer.alert('抛至失败！',{icon:2});
                            }
                        }
                    })
                },
                layer.closeAll()
            )
        });
        /*提醒事项*/
        $("#reminders").on("click",function(){
            var a = $("#data").datagrid("getSelected");
            $("#reminders").fancybox({
                padding: [],
                fitToView: false,
                width: 730,
                height: 450,
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe',
                href: "<?= Url::to(['create-reminders']) ?>?id=" +a.cust_id
            });
        });
    });
    /*转会员*/
    function member(){
        layer.confirm("确定要转会员吗?",
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
                    href:"<?=Url::to(['turn-member'])?>?id="+a.cust_id+'&from=/crm/crm-member-develop/index',
                });
            }
        )
    }
    /*转招商*/
    function investment(){
        var a = $("#data").datagrid("getSelected");
        var b = $("#data").datagrid("getChecked");
        var url = "<?=Url::to(['turn-investment']) ?>";
        var c = "转招商";
        data_process(a,b,url,c);
    }
    /*转销售*/
    function sales(){
        var a = $("#data").datagrid("getSelected");
        var b = $("#data").datagrid("getChecked");
        var url = "<?=Url::to(['turn-sales']) ?>";
        var c = "转销售";
        data_process(a,b,url,c);
    }
    /*发送邮件*/
    function sendEmail(){
        $.fancybox({
            width:800,
            height:600,
            padding:0,
            autoSize:false,
            type:"iframe",
            href:"<?=Url::to(['send-message','type'=>2])?>"
        });
    }
    /*发送短信*/
    function sendMessage(){
        $.fancybox({
            width:800,
            height:600,
            autoSize:false,
            padding:0,
            type:"iframe",
            href:"<?=Url::to(['send-message','type'=>1])?>"
        });
    }
    /*增加拜访记录*/
    $('#addVisit').click(function(){
        var a = $("#data").datagrid("getSelected");
        var b = $("#data").datagrid("getChecked");
        var url = "<?= Url::to(['visit-create']) ?>";
        if(b.length == 0 && a == null){
            layer.alert("请点击选择一条数据!",{icon:2,time:5000});
            return false;
        }else if(a != null){
            window.location.href = url+"?id=" + a.cust_id + "&ctype=1";
        }else if(b.length == 1){
            $.each(b, function (index, val) {
                id = val.cust_id;
            });
            window.location.href = url+"?id=" + id + "&ctype=1";
        }else if(b.length != 1){
            layer.alert("请点击选择一条会员信息!",{icon:2,time:5000});
            return false;
        }
    })
    /*修改拜访记录*/


</script>