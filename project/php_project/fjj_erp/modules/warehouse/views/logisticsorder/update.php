<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/12/8
 * Time: 上午 11:27
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;

\app\widgets\upload\UploadAsset::register($this);
\app\assets\JeDateAsset::register($this);

$this->title = '修改物流订单';
$this->params['homeLike'] = ['label' => '物流订单查询'];
$this->params['breadcrumbs'][] = ['label' => '物流订单查询', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '修改物流订单'];
?>
<style>
    .span-width {
        width: 300px;
    }

    .label-width {
        width: 100px;
    }

    .div-margin {
        margin-left: 50px;
    }
</style>

<?php echo $this->render('_form',['model'=>$model]);?>


