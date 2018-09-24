<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = '修改账号: ' . $model->user_account;
$this->params['homeLike'] = ['label'=>'系统平臺设置'];
$this->params['breadcrumbs'][] = ['label'=>'用户管理','url'=>'index'];
$this->params['breadcrumbs'][] = '修改用户';
$this->title = '修改用户';
?>
<div class="content">
    <h1 class="head-first">
        <?= Html::encode($this->title) ?>
    </h1>
    <?= $this->render('_form', [
        'model' => $model,
        'roles'=>$roles,
        'permission'=>$permission,
        'company'=>$company,
        'staff'=>$staff
    ]) ?>

</div>

<script>
    $(function(){
        var staffId=$('#user-staff_id option:selected').val();
        if(staffId == ''){
            $("#staff_code").val('');
            $("#staff_org").val('');
            return false;
        }else{
            $.ajax({
                url: "<?= Url::to(['/hr/staff/get-info'])?>?id=" + staffId,
                type: 'get',
                processData:false,
                contentType:false,
                success: function (data) {
                    var obj = eval('(' + data + ')');
                    $("#staff_code").val(obj.staff_code)
                    $("#staff_org").val(obj.organization_code)
                },
            });
        }
    })

</script>

