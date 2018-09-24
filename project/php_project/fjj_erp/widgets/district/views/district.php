<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/24
 * Time: 下午 04:58
 */
use yii\helpers\Html;
?>
<?=$level>=1?Html::dropDownList($level==1?$name:"",$data["path"][0],$data["tree"][0],$options['country']):""?>

<?=$level>=2?Html::dropDownList($level==2?$name:"",$data["path"][1],$data["tree"][1],$options['province']):""?>

<?=$level>=3?Html::dropDownList($level==3?$name:"",$data["path"][2],$data["tree"][2],$options['city']):""?>

<?=$level>=4?Html::dropDownList($level==4?$name:"",$data["path"][3],$data["tree"][3],$options['district']):""?>

<?php
$js=<<<JS
    $(".district-level").change(function(){
        var _this=$(this);
        var next=$(this).next();
        var parent=$(this).parents("div");
        var index=$(this).index();
        if($(this).val()==""){
            $(this).nextAll(".district-level").html("<option>请选择</option>");   
        }else{
            $.ajax({
                type:"get",
                url:"district?district_id="+_this.val()+"&type=1",
                success:function(data){
                    next.html(data);
                }
            });   
        }
    });
JS;
$this->registerJs($js);
?>
