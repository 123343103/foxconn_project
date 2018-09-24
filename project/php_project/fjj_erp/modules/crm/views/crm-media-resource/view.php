<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/13
 * Time: 上午 11:00
 */
use yii\helpers\Url;
$this->title="媒体资源详情";
$this->params["homeLike"]=["label"=>"客户关系管理","url"=>Url::to(['/'])];
$this->params["breadcrumbs"][]=["label"=>"媒体资源管理列表","url"=>['index']];
$this->params["breadcrumbs"][]=["label"=>$this->title];
?>
<div class="content">
    <h2 class="head-first">媒体资源详情 <span class="pull-right mr-10" style="color:#fff;">档案编号：<?=$model['medic_code'];?></span> </h2>
    <div class="mb-10">
        <button id="edit" class="button-blue">修改</button>
        <button id="remove" class="button-blue">删除</button>
        <button id="toList" class="button-blue">切换列表</button>
        <button id="add" class="button-blue">新增</button>
        <button id="export" class="button-blue">导出</button>
    </div>
        <div class="body" class="mt-20">
            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">媒体类型：</td>
                    <td width="30%" class="no-border vertical-center"><?=$model["cmt_type"]?></td>
                    <td width="10%" class="no-border vertical-center">公司名称：</td>
                    <td width="20%" class="no-border vertical-center"><?=$model["medic_compname"]?></td>
                    <td width="10%" class="no-border vertical-center">合作联系人：</td>
                    <td width="20%" class="no-border vertical-center"><?=$model["medic_parner"]?></td>
                </tr>
            </table>
            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">职务：</td>
                    <td width="30%" class="no-border vertical-center"><?=$model["medic_position"];?></td>
                    <td width="10%" class="no-border vertical-center">手机号码：</td>
                    <td width="20%" class="no-border vertical-center"><?=$model["medic_phone"]?></td>
                    <td width="10%" class="no-border vertical-center">电话号码：</td>
                    <td width="20%" class="no-border vertical-center"><?=$model["medic_tel"]?></td>
                </tr>
            </table>
            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">邮箱：</td>
                    <td width="30%" class="no-border vertical-center"><?=$model["medic_emails"];?></td>
                    <td width="10%" class="no-border vertical-center">是否供应商：</td>
                    <td width="20%" class="no-border vertical-center"><?=$model["medic_issupilse"]?></td>
                    <td width="10%" class="no-border vertical-center">合作次数：</td>
                    <td width="20%" class="no-border vertical-center"><?=$model["medic_times"]?></td>
                </tr>
            </table>
            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">服务评级：</td>
                    <td width="30%" class="no-border vertical-center"><?=$model["medic_level"];?></td>
                    <td width="10%" class="no-border vertical-center">联系地址：</td>
                    <td width="50%" class="no-border vertical-center"><?=$model['district_info']?><?=$model["medic_adds"]?></td>
                </tr>
            </table>
            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">媒体简介：</td>
                    <td width="90%" class="no-border vertical-center"><?=$model["medic_profile"]?></td>
                </tr>
            </table>
            <div class="space-30"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="10%" class="no-border vertical-center">行业资质：</td>
                    <td width="90%" class="no-border vertical-center"><?=$model["medic_qual"]?></td>
                </tr>
            </table>
            <div class="space-30"></div>
        </div>
</div>
<script>
    $(function(){
        $("#add").click(function(){
            window.location.href="create";
        });
        $("#edit").click(function(){
            window.location.href="edit?id="+yii.getQueryParams(window.location.href).id;
        });
    });
    $("#toList").click(function(){
        window.location.href="index";
    });
    $("#remove").click(function(){
        layer.confirm("确定删除吗?",{"btn":["确定","取消"],icon:2},function(){
            $.ajax({
                type:"get",
                url:"<?=Url::to(['remove'])?>?id=<?=\Yii::$app->request->get('id')?>",
                dataType:"json",
                success:function(){
                    layer.alert("删除成功",{icon:1},function(){
                        window.location.href="index";
                    });
                }
            });
        },function(){
            layer.closeAll();
        });
    });
    $("#export").click(function(){

    });
    $("#return").click(function(){
        window.location.href="index";
    });
</script>