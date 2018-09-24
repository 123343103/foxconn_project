<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/13
 * Time: 上午 11:00
 */
use yii\helpers\Url;
switch($model["commu_type"]){
    case 100855:
        $this->title="社群营销推广详情页面";
        break;
    case 100856:
        $this->title="社群营销活动详情页面";
        break;
    case 100857:
        $this->title="社群营销互动详情页面";
        break;
    case 100858:
        $this->title=" QQ邮件推广详情页面";
        break;
}
$this->params["homeLike"]=["label"=>"客户关系管理","url"=>Url::to(['/'])];
$this->params["breadcrumbs"][]=["label"=>"网络社区营销","url"=>['index']];
$this->params["breadcrumbs"][]=["label"=>$this->title];
?>
<div class="content">
    <h2 class="head-first">社群营销推广详情页面 <span class="pull-right mr-10" style="color:#fff;">档案编号：<?=$model['commu_code'];?></span> </h2>
    <div class="mb-10">
        <button id="remove" class="button-blue">删除</button>
        <button id="toList" class="button-blue">切换列表</button>
        <button id="return" class="button-blue">返回</button>
    </div>
    <?php if($model["commu_type"]==100855){ ?>
        <div class="body" class="mt-20">
            <h3 class="section-title">营销推广基本信息：</h3>
            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">发布载体：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["cmt_id"]?></td>
                    <td width="10%" class="no-border vertical-center">载体名称：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["cmt_intor"]?></td>
                </tr>
            </table>
            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">文案类型：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["commu_plantype"];?></td>
                    <td width="10%" class="no-border vertical-center">文案来源：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["commu_source"]?></td>
                </tr>
            </table>

            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">发布时间：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["commu_postime"]?></td>
                    <td width="10%" class="no-border vertical-center">负责人：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["commu_man"]?></td>
                </tr>
            </table>

            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">文章主题：</td>
                    <td width="90%" class="no-border vertical-center"><?=$model["commu_arttitle"]?></td>
                </tr>
            </table>


            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">发文链接：</td>
                    <td width="90%" class="no-border vertical-center"><?=$model["commu_link"]?></td>
                </tr>
            </table>

            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">备注：</td>
                    <td width="90%" class="no-border vertical-center"><?=$model["commu_remark"]?></td>
                </tr>
            </table>
        </div>
    <?php } ?>
    <?php if($model["commu_type"]==100856){ ?>
        <div class="body" class="mt-20">
            <h3 class="section-title">营销活动基本信息：</h3>
            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">发布载体：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["cmt_id"]?></td>
                    <td width="10%" class="no-border vertical-center">载体名称：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["cmt_intor"];?></td>
                </tr>
            </table>
            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">活动开始时间：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["act_start_time"]?></td>
                    <td width="10%" class="no-border vertical-center">活动结束时间：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["act_end_time"];?></td>
                </tr>
            </table>

            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">活动类型：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["act_type"];?></td>
                    <td width="10%" class="no-border vertical-center">负责人：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["commu_man"];?></td>
                </tr>
            </table>
            <div class="space-30"></div>

            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">活动主题：</td>
                    <td width="90%" class="no-border vertical-center"><?=$model["commu_arttitle"];?></td>
                </tr>
            </table>


            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">活动连接：</td>
                    <td width="90%" class="no-border vertical-center"><?=$model["commu_link"]?></td>
                </tr>
            </table>

            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">备注：</td>
                    <td width="90%" class="no-border vertical-center"><?=$model["commu_remark"];?></td>
                </tr>
            </table>
        </div>
    <?php } ?>
    <?php if($model["commu_type"]==100857){ ?>
        <div class="body">
            <h3 class="section-title">营销互动基本信息：</h3>
            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">私聊载体：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["cmt_id"]?></td>
                    <td width="10%" class="no-border vertical-center">载体名称：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["cmt_intor"]?></td>
                </tr>
            </table>
            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">私聊账号：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["private_commu_account"]?></td>
                    <td width="10%" class="no-border vertical-center">客户名称：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["cust_name"]?></td>
                </tr>
            </table>

            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">客户账号：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["cust_account"]?></td>
                    <td width="10%" class="no-border vertical-center">客户联系方式：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["cust_contcats"]?></td>
                </tr>
            </table>

            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">公司名称：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["cust_cmp_name"]?></td>
                    <td width="10%" class="no-border vertical-center">负责人：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["commu_man"]?></td>
                </tr>
            </table>
            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">公司地址：</td>
                    <td width="90%" class="no-border vertical-center"><?=$model['cust_cmp_district']?><?=$model["cust_cmp_address"]?></td>
                </tr>
            </table>
            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">备注：</td>
                    <td width="90%" class="no-border vertical-center"><?=$model["commu_remark"]?></td>
                </tr>
            </table>
        </div>
    <?php } ?>
    <?php if($model["commu_type"]==100858){ ?>
        <div class="body">
            <h3 class="section-title">QQ邮件推广信息：</h3>
            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">所属群分类：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["cmt_id"]?></td>
                    <td width="10%" class="no-border vertical-center">群号：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["private_commu_account"]?></td>
                </tr>
            </table>
            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">邮件主题：</td>
                    <td width="90%" class="no-border vertical-center"><?=$model["commu_arttitle"]?></td>
                </tr>
            </table>
            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">发送时间：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["email_send_time"]?></td>
                    <td width="10%" class="no-border vertical-center">发送数量：</td>
                    <td width="40%" class="no-border vertical-center"><?=$model["email_send_num"]?></td>
                </tr>
            </table>
            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">负责人：</td>
                    <td width="90%" class="no-border vertical-center"><?=$model['commu_man'];?></td>
                </tr>
            </table>
            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">备注：</td>
                    <td width="90%" class="no-border vertical-center"><?=$model['commu_remark'];?></td>
                </tr>
            </table>
        </div>
    <?php } ?>
        <div class="space-30"></div>
        <h3 class="head-first mb-20">数据统计信息</h3>
        <?php
//            echo \app\widgets\tableview\TableView::widget([
//                "options"=>["class"=>"table table-view"],
//                "indexField"=>"commu_iid",
//                "fields"=>$columns,
//                "data"=>$model['childs']
//            ]);
        ?>
    <div id="count-data"></div>
</div>


<style>
    .body{
        width: 98%;
        border: lightgrey 1px solid;
        padding:1%;
    }
    .section-title{
        color:#6666FF;
        font-size: 14px;
        font-weight: bold;
    }
</style>
<script>
    $(function(){
        $("#count-data").datagrid({
            url: "<?=Url::to(['count-data'])?>?id=<?=$model['commu_ID']?>",
            rownumbers: true,
            method: "get",
            idField: "commu_iid",
            loadMsg: "加载数据请稍候。。。",
            pagination:false,
            pageSize:3,
            pageList:[3,6,9],
            singleSelect: true,
            checkOnSelect: true,
            selectOnCheck: false,
            columns:[[
                <?=$columns?>
                {field:'action',title:'操作',width:200,formatter:function(value,row,index){
                    return "<a onclick='editRow("+row.commu_iid+")'>修改<a/><a class='ml-20' onclick='delRow("+row.commu_iid+")'>删除<a/>";
                }}
            ]],
            onLoadSuccess: function (data) {
                datagridTip($("#count-data"));
                showEmpty($(this),data.total,0);
            },
            onSelect:function(index,row){
                parent.childRow=row;
            }
        });
        $("#edit").click(function(){
            window.location.href="edit?id="+yii.getQueryParams(window.location.href).id;
        });
    });


    function delRow(id){
        layer.confirm("确定删除吗?",{icon:2},function(){
            $.ajax({
                type:"get",
                url:"<?=Url::to(['remove-child'])?>?id="+id,
                dataType:"json",
                success:function(data){
                    if(data.flag==1){
                        layer.alert(data.msg,{icon:1},function(){
                            $("#count-data").datagrid("reload");
                            layer.closeAll();
                        });
                    }else{
                        layer.alert(data.msg,{icon:2});
                    }
                }
            });
        });
    }
    function editRow(id){
        window.location.href="<?=Url::to(['edit-child'])?>?id="+id;
    }


    $("#toList").click(function(){
        window.location.href="index";
    });
    $("#remove").click(function(){
        var index=layer.confirm("确定删除吗?",{btn:["确定","取消"],icon:2},function(){
            $.ajax({
                type:"get",
                url:"remove?id=<?=\Yii::$app->request->get('id')?>",
                dataType:"json",
                success:function(data){
                    if(data.flag==1){
                        layer.alert("删除成功",{icon:1},function(){
                            window.location.href="<?=Url::to(['index'])?>";
                        });
                    }else{
                        layer.alert("删除失败",{icon:1});
                    }
                }
            });
        },function(){
            layer.close(index);
        });
    });
    $("#export").click(function(){

    });
    $("#return").click(function(){
        window.location.href="index";
    });
</script>