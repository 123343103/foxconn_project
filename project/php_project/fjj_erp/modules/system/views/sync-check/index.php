<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/11/24
 * Time: 9:20
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use \app\classes\Menu;
use kartik\select2\Select2;
use app\assets\MultiSelectAsset;
MultiSelectAsset::register($this);
$this->title = '数据查询';
$this->params['homeLike'] = ['label' => '系统平台设置'];
$this->params['breadcrumbs'][] = ['label' => '数据查询', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '数据查询列表'];
?>
<style>
    .label-width{
        width:80px;
    }
    .value-width{
        width:200px;
    }
    .width-485{
        width:485px;
    }
    .mb-20{
        margin-bottom: 20px;
    }
    .ml-180{
        margin-left:180px;
    }
    .select2-container .select2-selection--single .select2-selection__rendered {
        overflow: visible !important;
    }
    table{
        width:990px;
        overflow: scroll;
        table-layout: fixed;
    }
    table tr{
        height:30px;
    }
    table tr th,td{
        width:100px;
        text-align: center;
        padding: 0 10px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }
    .mt-50{
        margin-top: 50px;
    }

</style>
<div class="content">
    <?php $form = ActiveForm::begin(['id' => 'add-form','method'=>'post','action'=>Url::to(['check'])]); ?>
    <div class="mb-20">
        <div class="mb-10">
            <label class="label-width label-align">数据库选择<label>：</label></label>
            <select name="database" id="" class="value-width value-align easyui-validatebox" data-options="required:true">
                <option value="">请选择...</option>
                <option value="db" <?= !empty($post['database'])?($post['database'] == 'db'?'selected':''):''; ?>>db</option>
                <option value="pdt" <?= !empty($post['database'])?($post['database'] == 'pdt'?'selected':''):''; ?>>pdt</option>
                <option value="wms" <?= !empty($post['database'])?($post['database'] == 'wms'?'selected':''):''; ?>>wms</option>
                <option value="spp" <?= !empty($post['database'])?($post['database'] == 'spp'?'selected':''):''; ?>>spp</option>
                <option value="oms" <?= !empty($post['database'])?($post['database'] == 'oms'?'selected':''):''; ?>>oms</option>
                <option value="prch" <?= !empty($post['database'])?($post['database'] == 'prch'?'selected':''):''; ?>>prch</option>
            </select>
            <label class="label-width label-align">参数集合<label>：</label></label>
            <?php
            echo Select2::widget([
                'name' => 'kv_theme_select3',
                'data' => $data,
                'options' => ['placeholder' => '请选择...'],
                'class' => 'value-width',
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
    </div>
    <div class="mb-20">
        <label class="label-width vertical-top">输入SQL <label>：</label></label>
        <textarea name="sql" id="sql" rows="5" class="width-485 easyui-validatebox" data-options="required:true,validateOnBlur:true,validType:['checksql','confirmEnding']" placeholder="请输入sql语句,并以;结束"><?= !empty($post['sql'])?trim($post['sql']):''; ?></textarea>
    </div>
    <div class="ml-180">
        <button class="button-blue-big" type="submit" id="aa"><i class="icon-search"></i> 查询</button>
<!--        <button class="button-blue-big" type="reset"><i class="icon-undo"></i> 重置</button>-->
        <?= Html::button('<i class="icon-undo"></i> 重置', ['class' => 'button-blue-big', 'onclick'=>'window.location.href="'.Url::to(['check']).'"']) ?>
        <span class="red">*输入SQL只能包含关键字SELECT</span>
    </div>
    <?php ActiveForm::end(); ?>
    <?php if(!empty($res)){ ?>
        <div id="tabs" class="easyui-tabs mt-50">
            <?php foreach ($res as $key => $val){ ?>
                <div title="表格<?= $key+1 ?>">
                    <div>
                        <?php if(!empty($val)){ ?>
                            <table>
                                <thead>
                                <tr>
                                    <?php foreach ($val[0] as $k => $value){ ?>
                                        <th><?= $k ?></th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($val as $vala){ ?>
                                    <tr>
                                        <?php foreach ($vala as $k => $v){ ?>
                                            <td><?= $v ?></td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        <?php } ?>
                        <?php if(!empty($reserr)){ ?>
                            <div class="red"><?= $reserr['name'].':'.$reserr['message'] ?></div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
    <?php if(!empty($reserr)){ ?>
        <div class="red mt-50"><?= $reserr['name'].':'.$reserr['message'] ?></div>
    <?php } ?>
</div>
<script>
    $(function(){
        /*表格高度限制*/
        var $t = $('table');
        $t.each(function(){
            var $this = $(this);
            var h = $this.height();
            if(h>400){
                $($this.parent()).css('height','400px');
            }else{
                $($this.parent()).css('height',h+'px');
            }
        })
        $("#tabs").tabs({
            tabPosition:'top',
            height:'auto'
        });
        $('#aa').click(function(){
            $('#add-form').on("beforeSubmit", function () {
                if (typeof(fun) == "function" && !fun()) {
                    $("button[type='submit']").prop("disabled", false);
                    return false;
                }

                if (!$(this).form('validate')) {
                    $("button[type='submit']").prop("disabled", false);
                    return false;
                }
            })

        })
//        ajaxSubmitForm($('#add-form'));
//        $("#data").datagrid({
//            url: "<?//= Url::to(['check']) ;?>//",
//            rownumbers: true,
//            method: "get",
//            idField: "cust_id",
//            loadMsg: "加载中...",
//            pagination: true,
//            singleSelect: true,
//            selectOnCheck: false,
//            checkOnSelect: false,
//            columns: [[
//                <?php //foreach($res['rows'][0] as $key => $val){ ?>
//                    {field:'<?//= $key ?>//',title:'<?//= $key ?>//',width:'200'},
//                <?php //} ?>
//            ]]
//        })
    })

</script>

