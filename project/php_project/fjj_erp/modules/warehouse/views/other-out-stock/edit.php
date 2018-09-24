<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/7/13
 * Time: 上午 11:31
 */
use yii\helpers\Url;
$this->title='其他出库单修改';
$this->params['homeLike']=['label'=>'仓储物流管理','url'=>Url::home()];
$this->params['breadcrumbs'][]=$this->title;
echo $this->render("_form",["model"=>$model,"childs"=>$childs,"options"=>$options,'businessType' => $businessType,'staff'=>$staff]);
?>
<script>
    $(function(){
        $("#organization").change();
        $("#o_wh_name").change();
        $("#i_wh_name").change();
        $("#staff_id").change();
    });
</script>
