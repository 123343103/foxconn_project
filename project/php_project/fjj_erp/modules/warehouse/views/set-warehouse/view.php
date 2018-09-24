<?php
/**
 * Created by PhpStorm.
 * User: F3860959
 * Date: 2017/6/14
 * Time: 上午 10:41
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use app\assets\MultiSelectAsset;
MultiSelectAsset::register($this);
$this->title = '仓库详情';
$this->params['homeLike'] = ['label' => '仓储物流管理'];
$this->params['breadcrumbs'][] = ['label' => '仓库设置', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .fancybox-wrap {
        top: 0px !important;
        left: 0px !important;
    }
    .width-800{
        width: 800px;
    }
    .mt10-ml30{
        margin-left: 30px;
        margin-top: 10px;
    }
    .width-70{
        width: 70px;
    }
    .width-180{
        width: 180px;
    }
    .width-155{
        width: 155px;
    }
    .width-500{
        width: 500px;
    }
    .ml35-mt20{
        margin-top: 20px;
        margin-left: 35px;
    }
    .width-700{
        width: 700px;
    }
    .width-20{
        width: 20px;
    }
    .width-30{
        width: 30px;
    }
    .width-60{
        width: 60px;
    }
    .height-30{
        height: 30px;
    }
    .width-150{
        width: 150px;
    }
    .up-re{
        width: 100%;
        height: 20px;
    }
    .space-30{
        width: 100%;
        height: 30px;
    }
    .width-600{
        width: 70%;
    }
</style>
<div class="no-padding width-800 content">
    <div class="view-plan">
        <h2 class="head-first">仓库详情</h2>
    </div>
    <input type="hidden" class="_id" value="<?php echo $id?>">
    <div >
            <div class="_update" style="background-color: #1f7ed0; width: 80px;height: 25px;float: left;text-align: center;margin-bottom: 3px"><a style="color: #fff;font-size: 14px;line-height: 25px;">修改</a></div>
             <div style="background-color: #1f7ed0; margin-left: 7px;width: 80px;height: 25px;float: left;text-align: center;"><a href="<?=Url::to('index')?>" style="color: #fff;font-size: 14px;line-height: 25px;">切换列表</a>
            </div>
    </div>
<!--    <div style="width: 100%;height: 3px;background-color: #2aabd2;float: left"></div>-->
    <div style="height: 30px;" class="border-bottom mb-20"></div>
    <h1 class="head-second color-1f7ed0" >仓库基本信息</h1>
    <div class="mt10-ml30">
            <label class="width-70 label-align">仓库代码：</label>
            <span class=" width-180"><?= $model[0]['wh_code'] ?></span>
            <label class="no-border vertical-center width-155 label-align">仓库名称：</label>
            <span  class="no-border vertical-center width-180 value_align"><?= $model[0]['wh_name'] ?></span>
    </div>

        <div class="mt10-ml30">
            <label class="width-70 label-align">仓库性质：</label>
            <span class="  width-180"><?= $model[0]['wh_natures']?></span>
            <label class="width-155 label-align">仓库属性：</label>
            <span class="  width-180"><?= $model[0]['wh_attrs']?></span>
        </div>
        <div class="mt10-ml30">
            <label class="width-70 label-align">仓库类别：</label>
            <span class=" width-180"><?= $model[0]['wh_types'] ?></span>
            <label class="width-155 label-align">仓库级别：</label>
            <span class="  width-180"><?= $model[0]['wh_levs'] ?></span>
            </div>
            <div class="mt10-ml30">
                    <label style="width: 80px" class="label-align">是否报废仓：</label>
                    <span class="  width-180"><?php if($model[0]['wh_yn']=="N"){
                        echo "否";}else{
                            echo "是";
                        } ?></span>
                    <label class="width-155 label-align">是否自提点：</label>
                    <span class="  width-180"><?php if($model[0]['yn_deliv']=='0'){
                       echo '否';}else{
                        echo '是';
                        } ?></span>
            </div>

        <div class="mt10-ml30">
            <label class="width-70 label-align">法人：</label>
            <span class="  width-180"><?= $model[0]['people'] ?></span>
            <label class="width-155 label-align">创业公司：</label>
            <span class="  width-180"><?php if($model[0]['company']=="0"){
                echo "";}else{
                    echo $model[0]['company'];
                } ?></span>
        </div>

        <div class="mt10-ml30">
            <label class="no-border vertical-center width-70 label-align">仓库地址：</label>
            <span class="no-border vertical-center width-500 value_align"><?= $model[0]['wh_addrss'] ?></span>
        </div>
        <div class="mt10-ml30">
            <label class="no-border vertical-center width-70 label-align">备注：</label>
            <span class="no-border vertical-center width-600"><?= $model[0]['remarks'] ?></span>
        </div>
        <div class="mt10-ml30">
            <label class="width-70">状态：</label>
            <span class=" width-180"><?php if( $model[0]['wh_state']=="Y"){
                echo "启用";}else{
                echo "禁用";
                } ?></span>
        </div>

    <div style="height: 20px;"></div>
        <h1 class="head-second color-1f7ed0" >仓库管理员列表</h1>
        <div class="ml35-mt20">
            <table id="view-user" class="table width-700">
                <thead>
                <tr class="height-30">
                    <th class="width-20">序号</th>
                    <th class="width-60">工号</th>
                    <th class="width-60">姓名</th>
                    <th class="width-70">电话</th>
                    <th class="width-150">邮箱</th>
                </tr>
                </thead>
                <tbody id="user">
                <?php $i=1?>
                <?php foreach ($model as $key=>$val){?>
                <tr class="height-30">
                    <td><?=($key+1)?></td>
                    <td><?=$val['emp_no']?></td>
                    <td><?=$val['staff_name'] ?></td>
                    <td> <?=$val['adm_phone']?></td>
                    <td><?=$val['adm_email']?></td>
                </tr>
                    <?php $i++?>
                <?php };?>
                </tbody>
            </table>
        </div>


        <div class="up-re"></div>
    </div>

<script>
    /*
     *取消按钮（关闭弹窗）事件
     */
    $(".close").click(function () {
        location.href="<?=Url::to('index')?>";
    });

    $("._update").click(function () {
       // var row = $("#data").datagrid("getSelected");
        var id=$("._id").val();
        //alert(id);
        location.href='<?=Url::to(['update-warehouse'])?>?id='+id;
    });
    $("#edit").on("click", function () {
        var row = $("#data").datagrid("getSelected");
        if (row == null) {
            layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
        } else {
            var id = row['wh_code'];
            $('#edit').fancybox({
                autoSize: true,
                fitToView: false,
                height: 500,
                width: 800,
                closeClick: true,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe',
                href: "<?= Url::to(['update-warehouse'])?>?id=" + id
            });
        }
    });
</script>