<?php
/**
 * User: F1677929
 * Date: 2017/4/29
 */
use yii\helpers\Url;
use yii\helpers\Html;
use \app\classes\Menu;
$this->title='活动相关设置';
$this->params['homeLike']=['label'=>'客户关系管理','url'=>Url::to(['/index/index'])];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <h1 class="head-first">活动相关设置列表</h1>
    <div style="margin:0 20px;">
        <table class="table">
            <thead>
            <tr>
                <th>序号</th>
                <th>活动相关设置列表</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(Menu::isAction('/crm/crm-active-type/index')){?>
                <tr>
                    <td>3</td>
                    <td>活动类型</td>
                    <td><a href="<?=Url::to(['/crm/crm-active-type/index'])?>">查看</a></td>
                </tr>
            <?php }?>
            <?php if(Menu::isAction('/crm/crm-media-type/index')){?>
                <tr>
                    <td>4</td>
                    <td>媒体类型</td>
                    <td><a href="<?=Url::to(['/crm/crm-media-type/index'])?>">查看</a></td>
                </tr>
            <?php }?>
            <?php if(Menu::isAction('/crm/crm-carrier/index')){?>
                <tr>
                    <td>1</td>
                    <td>载体名称</td>
                    <td><a href="<?=Url::to(['/crm/crm-carrier/index'])?>">查看</a></td>
                </tr>
            <?php }?>
            <?php if(Menu::isAction('/crm/crm-active-name/index')){?>
                <tr>
                    <td>2</td>
                    <td>活动名称</td>
                    <td><a href="<?=Url::to(['/crm/crm-active-name/index'])?>">查看</a></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(function(){
        $(".content tbody > tr").each(function(i){
            $(this).find("td:first").text(i+1);
        });
    })
</script>