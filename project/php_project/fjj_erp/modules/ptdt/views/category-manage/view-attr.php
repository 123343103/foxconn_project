<?php
/**
 * User: F1677929
 * Date: 2017/9/6
 */
use yii\helpers\Url;
\app\assets\JqueryUIAsset::register($this);
?>
<style>
    .div_tab_display {
        display: table;
        margin-bottom: 5px;
    }
    .div_tab_display > label {
        display: table-cell;
        vertical-align: middle;
        width: 100px;
    }
    .div_tab_display > span {
        display: table-cell;
        vertical-align: middle;
        width: 300px;
        word-break: break-all;
    }
</style>
<h1 class="head-first">查看属性</h1>
<div class="div_tab_display">
    <label>属性名称：</label>
    <span><?=$data['attr_name']?></span>
</div>
<div class="div_tab_display">
    <label>资料格式：</label>
    <span>
        <?php
            if($data['attr_type'] == '0'){
                echo "多项选择";
            }
            if($data['attr_type'] == '1'){
                echo "平铺选择";
            }
            if($data['attr_type'] == '2'){
                echo "下拉选择";
            }
            if($data['attr_type'] == '3'){
                echo "文字录入";
            }
        ?>
    </span>
</div>
<?php if(!empty($data['values'])){?>
    <div style="margin:0 15px 10px;">
        <table class="table" style="font-size:12px;">
            <thead>
            <tr>
                <th style="width:10%;">序号</th>
                <th style="width:70%;">属性值</th>
                <th style="width:20%;">状态</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($data['values'] as $key=>$val){?>
                <tr>
                    <td><?=$key+1?></td>
                    <td style="word-break:break-all;"><?=$val['attr_value']?></td>
                    <td>
                        <?php if($val['yn'] === '1'){?>
                            启用
                        <?php }?>
                        <?php if($val['yn'] === '0'){?>
                            禁用
                        <?php }?>
                    </td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
<?php }?>
<div class="div_tab_display">
    <label>是否必填：</label>
    <span>
        <?php
            if($data['isrequired'] == '0'){
                echo "否";
            }
            if($data['isrequired'] == '1'){
                echo "是";
            }
        ?>
    </span>
</div>
<div class="div_tab_display">
    <label>备注：</label>
    <span><?=$data['attr_remark']?></span>
</div>
<div style="text-align:center;margin-bottom:20px;">
    <button type="button" class="button-blue" onclick="editAttr(<?=$data['catg_attr_id']?>)">修改</button>
    <button type="button" class="button-white" onclick="parent.$.fancybox.close()">关闭</button>
</div>
<script>
    //修改属性
    function editAttr(id){
        parent.$.fancybox({
            href:"<?=Url::to(['edit-attr'])?>?id="+id,
            type:"iframe",
            padding:0,
            autoSize:false,
            width:500,
            height:500,
            fitToView:false
        });
    }
</script>