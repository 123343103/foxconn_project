<?php
/**
 * User: F1677929
 * Date: 2017/4/1
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\classes\Menu;
use yii\helpers\Html;

$this->title = '拜访记录详情';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '拜访记录管理', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
    <h1 class="head-first"><?= $this->title ?><span style="font-size:12px;color:white;float:right;margin-right:15px;"><?= '档案编号：' . $data['recordChild']['sil_code'] ?></span>
    </h1>
    <div class="mb-10">
        <?php if ($data['flag']) { ?>
            <?= Menu::isAction('/crm/crm-visit-record/edit') ? Html::button('修改', ['class' => 'button-blue', 'onclick' => "window.location.href='" . Url::to(['edit', 'childId' => $data['recordChild']['sil_id']]) . "'"]) : '' ?>
        <?php } ?>
        <?= Menu::isAction('/crm/crm-visit-record/index') ? Html::button('切换列表', ['class' => 'button-blue', 'style' => 'width:80px;', 'onclick' => "window.location.href='" . Url::to(['index']) . "'"]) : '' ?>
    </div>
    <div class="mb-10" style="height:2px;background-color:#9acfea;"></div>
    <h2 class="head-second">客户基本信息</h2>
    <div class="mb-10">
        <table width="64%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">客户名称<label>：</label></td>
                <td class="no-border vertical-center" width="22%"><?=$data['customerInfo']['cust_sname']?></td>
                <td class="no-border vertical-center label-align" width="10%">客户类型<label>：</label></td>
                <td class="no-border vertical-center" width="22%"><?=$data['customerInfo']['customerType']?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">客户经理<label>：</label></td>
                <td class="no-border vertical-center" width="22%"><?=$data['customerInfo']['customerManager']?></td>
                <td class="no-border vertical-center label-align" width="10%">营销区域<label>：</label></td>
                <td class="no-border vertical-center" width="22%"><?=$data['customerInfo']['csarea_name']?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">联系人<label>：</label></td>
                <td class="no-border vertical-center" width="22%"><?=$data['customerInfo']['cust_contacts']?></td>
                <td class="no-border vertical-center label-align" width="10%">联系电话<label>：</label></td>
                <td class="no-border vertical-center" width="22%"><?=$data['customerInfo']['cust_tel2']?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">详细地址<label>：</label></td>
                <td class="no-border vertical-center qvalue-align" colspan="3"
                    width="54%"><?=$data['customerInfo']['customerAddress']?></td>
            </tr>
        </table>
    </div>
    <h2 class="head-second">拜访记录</h2>
    <div class="mb-10">
        <table width="96%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">拜访人<label>：</label></td>
                <td class="no-border vertical-center" width="22%"><?=$data['recordChild']['visitPersonCode'].'&nbsp;'.$data['recordChild']['visitPersonName']?></td>
                <td class="no-border vertical-center label-align" width="10%">拜访类型<label>：</label></td>
                <td class="no-border vertical-center" width="22%"><?=$data['recordChild']['visitType']?></td>
                <td class="no-border vertical-center label-align" width="10%">拜访方式<label>：</label></td>
                <td class="no-border vertical-center" width="22%"><?php
                    if($data['recordChild']['type']==='20'){
                        echo '计划拜访';
                    }elseif($data['recordChild']['type']==='30'){
                        echo '临时拜访';
                    }elseif($data['recordChild']['type']==='40'){
                        echo '会员回访';
                    }elseif($data['recordChild']['type']==='50'){
                        echo '潜在拜访';
                    }elseif($data['recordChild']['type']==='60'){
                        echo '招商拜访';
                    }else{
                        echo "<span style='color: red;'>错误</span>";
                    }
                    ?></td>
            </tr>
            <?php if($data['recordChild']['type']==='20'){?>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">关联拜访计划<label>：</label></td>
                <td class="no-border vertical-center" width="22%"><?php
                    if(Menu::isAction('/crm/crm-visit-plan/view')){
                        echo "<a href='".Url::to(['/crm/crm-visit-plan/view','id'=>$data['recordChild']['svp_id']])."'>".$data['recordChild']['svp_code']."</a>";
                    }else{
                        echo $data['recordChild']['svp_code'];
                    }
                    ?></td>
                <td class="no-border vertical-center label-align" width="10%">计划开始时间<label>：</label></td>
                <td class="no-border vertical-center" width="22%"><?=substr($data['recordChild']['plan_start_time'],0,16)?></td>
                <td class="no-border vertical-center label-align" width="10%">计划结束时间<label>：</label></td>
                <td class="no-border vertical-center" width="22%"><?=substr($data['recordChild']['plan_end_time'],0,16)?></td>
            </tr>
            <?php }?>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">记录开始时间<label>：</label></td>
                <td class="no-border vertical-center" width="22%"><?=substr($data['recordChild']['start'],0,16)?></td>
                <td class="no-border vertical-center label-align" width="10%">记录结束时间<label>：</label></td>
                <td class="no-border vertical-center" width="22%"><?=substr($data['recordChild']['end'],0,16)?></td>
            </tr>
        </table>
    </div>
    <div class="mb-10" style="margin-left:30px;margin-right:30px;">
        <p style="margin-bottom:2px;font-size:13px;font-weight:900;">陪同人员信息：</p>
        <table class="table">
            <thead>
            <tr>
                <th style="width:4%;">序号</th>
                <th style="width:24%;">工号</th>
                <th style="width:24%;">姓名</th>
                <th style="width:24%;">职位</th>
                <th style="width:24%;">联系电话</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($data['accompanyData'])){?>
                <?php foreach($data['accompanyData'] as $key=>$val){?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td><?=$val['staff_code']?></td>
                        <td><?=$val['staff_name']?></td>
                        <td><?=$val['title_name']?></td>
                        <td><?=$val['acc_mobile']?></td>
                    </tr>
                <?php }?>
            <?php }?>
            </tbody>
        </table>
    </div>
    <div class="mb-10" style="margin-left:30px;margin-right:30px;">
        <p style="margin-bottom:2px;font-size:13px;font-weight:900;">接待人员信息：</p>
        <table class="table">
            <thead>
            <tr>
                <th style="width:4%;">序号</th>
                <th style="width:32%;">接待人员姓名</th>
                <th style="width:32%;">职位</th>
                <th style="width:32%;">联系电话</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($data['receptionData'])){?>
                <?php foreach($data['receptionData'] as $key=>$val){?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td><?=$val['rece_sname']?></td>
                        <td><?=$val['rece_position']?></td>
                        <td><?=$val['rece_mobile']?></td>
                    </tr>
                <?php }?>
            <?php }?>
            </tbody>
        </table>
    </div>
    <div class="mb-10">
        <table width="96%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">过程描述<label>：</label></td>
                <td class="no-border vertical-center qvalue-align" colspan="5"
                    width="86%"><?=$data['recordChild']['sil_process_descript']?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">追踪事项<label>：</label></td>
                <td class="no-border vertical-center qvalue-align" colspan="5"
                    width="86%"><?=$data['recordChild']['sil_track_item']?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">谈判注意事项<label>：</label></td>
                <td class="no-border vertical-center qvalue-align" colspan="5"
                    width="86%"><?=$data['recordChild']['sil_next_interview_notice']?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">拜访总结<label>：</label></td>
                <td class="no-border vertical-center qvalue-align" colspan="5"
                    width="86%"><?=$data['recordChild']['sil_interview_conclus']?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">备注<label>：</label></td>
                <td class="no-border vertical-center qvalue-align" colspan="5"
                    width="86%"><?=$data['recordChild']['remark']?></td>
            </tr>
        </table>
    </div>
</div>
<script>
    $(function () {
        //删除
        $("#delete_btn").click(function () {
            layer.confirm("确定删除吗?", {icon: 2},
                function () {
                    $.ajax({
                        url: "<?=Url::to(['delete-child'])?>",
                        data: {"childId":<?=$data['recordChild']['sil_id']?>},
                        dataType: "json",
                        success: function (data) {
                            if (data.flag == 1) {
                                layer.alert(data.msg, {icon: 1}, function () {
                                    window.location.href = "<?=Url::to(['index'])?>";
                                });
                            } else {
                                layer.alert(data.msg, {icon: 2});
                            }
                        }
                    })
                },
                layer.closeAll()
            );
        });

        //导出
        $('#export_btn').click(function(){
            html2canvas($(".content"), {
                onrendered: function(canvas) {

                    //通过html2canvas将html渲染成canvas，然后获取图片数据
                    var imgData = canvas.toDataURL('image/jpeg');

                    //初始化pdf，设置相应格式
                    var doc = new jsPDF("p", "mm", "a4");

                    //这里设置的是a4纸张尺寸
                    doc.addImage(imgData, 'JPEG', 0, 0,210,297);

                    //输出保存命名为content的pdf
                    doc.save('客户拜访记录.pdf');
                },
                background:"white",
            });
        });
    })
</script>