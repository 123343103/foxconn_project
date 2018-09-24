<?php
/**
 * User: F1678086
 * Date: 2017/3/10
 * Time: 8:39
 */
use \yii\bootstrap\ActiveForm;
use \yii\helpers\Url;
$this->title = "商品开发需求审核";

?>
<?php $form = ActiveForm::begin(['id'=>'check-form']); ?>
    <h1 class="head-first">
        <?= $this->title ?>
        <span class="head-code">编号：<?= $result['vco_code'] ?></span>
    </h1>
    <h1 class="head-second">商品经理人</h1>
    <div class="mb-20">
        <label class="width-80">开发中心</label>
        <span class="width-120"><?= $model->developCenterName ?></span>
        <label class="width-80">开发部</label>
        <span class="width-120"><?= $model->developDepartmentName ?></span>
        <label class="width-80">商品经理人</label>
        <span class="width-120"><?= $model->productManagerName->name ?>/<?= $model->productManagerName->code ?></span>
    </div>
    <div class="mb-20">
        <label class="width-80">需求类型</label>
        <span class="width-120"><?= $model->pdqSourceTypeName ?></span>
        <label class="width-80">开发类型</label>
        <span class="width-120"><?= $model->developTypeName ?></span>
        <label class="width-80">Commodity</label>
        <span class="width-120"><?= $model->commodityName ?></span>
    </div>
    <h1 class="head-second">商品基本信息</h1>
    <div class="mb-20">
        <table class="product-list" style="width:650px;">
            <thead>
            <tr>
                <th>序号</th>
                <th>商品名称</th>
                <th>商品规格型号</th>
                <th>商品定位</th>
                <th>一阶</th>
                <th>二阶</th>
                <th>三阶</th>
                <th>四阶</th>
                <th>五阶</th>
                <th>六阶</th>
                <th>商品要求</th>
                <th>制程要求</th>
                <th>品质要求</th>
                <th>备注</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model->products as $key => $val) { ?>
                <tr>
                    <td><?= $key+1 ?></td>
                    <td><?= $val->product_name ? $val->product_name : "" ?></td>
                    <td><?= $val->product_size ? $val->product_size : "" ?></td>
                    <td><?= $val->levelName ? $val->levelName : "" ?></td>
                    <td><?= $val->typeName[0] ? $val->typeName[0] : "" ?></td>
                    <td><?= $val->typeName[1] ? $val->typeName[1] : "" ?></td>
                    <td><?= $val->typeName[2] ? $val->typeName[2] : "" ?></td>
                    <td><?= $val->typeName[3] ? $val->typeName[3] : "" ?></td>
                    <td><?= $val->typeName[4] ? $val->typeName[4] : "" ?></td>
                    <td><?= $val->typeName[5] ? $val->typeName[5] : "" ?></td>
                    <td><?= $val->product_requirement ? $val->product_requirement : "" ?></td>
                    <td><?= $val->product_process_requirement ? $val->product_process_requirement : "" ?></td>
                    <td><?= $val->product_quality_requirement ? $val->product_quality_requirement : "" ?></td>
                    <td><?= $val->other_des ? $val->other_des : "" ?></td>
                </tr>
            <?php } ?>
            </tbody>
            </thead>
        </table>
    </div>

    <div class="mb-20">
        <label class="vertical-top">签核意见</label>
        <textarea style="width:616px;" name="remark" id="" cols="3" rows="3"></textarea>
    </div>
    <div class="space-20"></div>
    <div class="width-400 margin-auto">
        <button class="button-blue-big" type="button" id="pass">通&nbsp过</button>
        <button class="button-blue-big" type="button" id="reject">驳&nbsp回</button>
        <button class="button-white-big" type="button" onclick="close_select()">取&nbsp消</button>
    </div>
    <input type="hidden" name="id" value="<?= $id ?>">
<?php ActiveForm::end(); ?>
<script>
        $("#pass").on("click", function () {
            layer.confirm("是否通过?",
                {
                    btn:['确定', '取消'],
                    icon:2
                },
                function () {
                    $.ajax({
                        type: "post",
                        dataType: "json",
                        data:$("#check-form").serialize(),
                        url: "<?= Url::to(['/system/verify-record/audit-pass']) ?>",
                        success: function (msg) {
                            if( msg.flag == 1){
                                layer.alert(msg.msg,{icon:1},function(){
                                    parent.window.location.href = '<?= Url::to(['index']) ?>'
                                });
                            }else{
                                layer.alert(msg.msg,{icon:2})
                            }
                        }
                    })
                },
                function () {
                    layer.closeAll();
                }
            )
        });
//        $("#reject").one("click", function () {
//            $("#check-form").attr('action','<?//= Url::to(['/system/verify-record/audit-reject']) ?>//');
//            return ajaxSubmitForm($("#check-form"));
//        });
        $("#reject").on("click", function () {
            layer.confirm("是否驳回?",
                {
                    btn:['确定', '取消'],
                    icon:2
                },
                function () {
                    $.ajax({
                        type: "post",
                        dataType: "json",
                        data:$("#check-form").serialize(),
                        url: "<?= Url::to(['/system/verify-record/audit-reject']) ?>",
                        success: function (msg) {
                            if( msg.flag == 1){
                                layer.alert(msg.msg,{icon:1},function(){
                                    parent.window.location.href = '<?= Url::to(['index']) ?>'
                                });
                            }else{
                                layer.alert(msg.msg,{icon:2})
                            }
                        }
                    })
                },
                function () {
                    layer.closeAll();
                }
            )
        });

</script>

