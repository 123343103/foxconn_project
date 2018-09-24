<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/7/13
 * Time: 上午 11:31
 */
use yii\helpers\Url;
$this->title='其他出库单新增';
$this->params['homeLike']=['label'=>'仓储物流管理','url'=>Url::home()];
$this->params['breadcrumbs'][]=$this->title;
echo $this->render("_form",["options"=>$options,'businessType' => $businessType,'staff'=>$staff,"staff_is_supper"=>$staff_is_supper]);
?>
<script>
    $(function(){
        $("#staff_id").change();
    })
</script>
