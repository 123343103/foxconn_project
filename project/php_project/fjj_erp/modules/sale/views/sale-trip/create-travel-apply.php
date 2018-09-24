<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/2/13
 * Time: 上午 11:34
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
//use app\assets\MultiSelectAsset;
//MultiSelectAsset::register($this);

$this->title = '新增出差申請';
$this->params['homeLike'] = ['label' => 'CRM'];
$this->params['breadcrumbs'][] = ['label' => '出差申請及消单报告列表'];
$this->params['breadcrumbs'][] = ['label' => '新增出差申请'];
?>

<style>
    .select2-selection{
        width: 578px;/*分級分類輸入框寬度*/
        height: auto;/*分級分類輸入框高度樣式*/
        overflow:hidden;
    }
</style>
<div class="content">
    <h1 class="head-first">
        新增出差申请
    </h1>
    <div class="mb-30">
        <?php $form = ActiveForm::begin([
            'id' => 'add-form',
            /*'fieldConfig' => [
                'options'=>['class'=>'form-row'],
                'labelOptions' => ['class' => 'width-100'],
                'inputOptions' => ['class' => 'width-200'],
            ],*/
        ]); ?>
            <div class="inline-block">
                <label class="width-100">出差类型</label>
                <div class="inline-block radio-div" id="pdfirmreport-report_agents_type">
                    <label class="ml-10"><input type="radio" value="100049" name="PdFirmReport[report_agents_type]">期间出差申请</label>
                    <label class="ml-20"><input type="radio" value="100050" name="PdFirmReport[report_agents_type]">日出差申请</label>
                </div>
            </div>
        <div class="space-20"></div>
        <div class="inline-block">
            <label for="pdfirm-firm_compprincipal" class="width-100">工号</label>
            <input class="width-150"  value="" name="PdFirm[firm_compprincipal]">
            <label for="pdfirm-firm_comptel" class="width-100">申请人</label>
            <input class="width-150"  value="" name="PdFirm[firm_comptel]">
            <label for="pdfirm-firm_compmail" class="width-100 ml-130">职位</label>
            <input class="width-150"  value="" name="PdFirm[firm_compmail]">
        </div>
        <div class="space-20"></div>
        <div class="inline-block">
            <label for="pdfirm-firm_contaperson" class="width-100">资位</label>
            <input class="width-150"  value="" name="PdFirm[firm_contaperson]">
            <label for="pdfirm-firm_contaperson_tel" class="width-100">部门</label>
            <input class="width-150"  value="" name="PdFirm[firm_contaperson_tel]">
            <input class="width-150"  value="" name="PdFirm[firm_contaperson_tel]">
            <label for="pdfirm-firm_contaperson_mail" class="width-80 ml--3">费用代码</label>
            <input class="width-150"  value="" name="PdFirm[firm_contaperson_mail]" id="pdfirm-firm_contaperson_mail">
        </div>
        <div class="space-20"></div>
        <div class="inline-block">
            <label for="pdfirm-firm_contaperson" class="width-100">所属军区</label>
            <input class="width-150"  value="" name="PdFirm[firm_contaperson]">
            <label for="pdfirm-firm_contaperson_tel" class="width-100">出差期间</label>
            <input class="width-100"  value="" name="PdFirm[firm_contaperson_tel]">
            至
            <input class="width-100"  value="" name="PdFirm[firm_contaperson_tel]">
            <label for="pdfirm-firm_contaperson_mail" class="width-100 ml-62">工作代理人</label>
            <input class="width-150"  value="" name="PdFirm[firm_contaperson_mail]" id="pdfirm-firm_contaperson_mail">
        </div>
        <div class="space-20"></div>
        <div class="inline-block">
            <label for="" class="width-100">客户名称</label>
            <!--<input class="width-150"  value="" name="PdFirm[firm_contaperson]">-->
            <input  id="firm_sname" class="width-150 easyui-validatebox" data-options="required:'true'">
            <span class="width-50 ml-10"><a href="" id="select-com"
                                            class="fancybox.ajax">选择</a></span>
        </div>
        <div class="space-20"></div>
        <div class="mb-20">
            <label class="width-100"><span class="red">*</span>出差地点</label>
            <select class="width-105 disName easyui-validatebox" data-options="required:'true'" id="disName_1">
                <option value="">請選擇</option>
                <?php foreach ($firmDisName as $key => $val) { ?>
                    <option value="<?= $key ?>"><?= $val ?></option>
                <?php } ?>
            </select>
            <select class="width-105 disName easyui-validatebox" data-options="required:'true'" id="disName_2">
                <option value>請選擇</option>
            </select>
            <select class="width-105 disName easyui-validatebox" data-options="required:'true'" id="disName_3">
                <option value>請選擇</option>
            </select>
            <select class="width-105 disName easyui-validatebox" data-options="required:'true'" id="disName_4">
                <option value>請選擇</option>
            </select>
            <select class="width-105 disName easyui-validatebox" data-options="required:'true'" id="pdfirm-firm_district_id" name="PdFirm[firm_district_id]">
                <option value>請選擇</option>
            </select>

            <input class=" ml-10 easyui-validatebox" data-options="required:'true'" style="width: 242px;" id="pdfirm-firm_compaddress" name="PdFirm[firm_compaddress]">
        </div>
        <div class="mb-10">
            <label class="width-100 vertical-top">出差任务简述</label>
            <textarea style="width: 795px" rows="4" id="pdfirm-firm_remark1" name="PdFirm[firm_remark1]"></textarea>
        </div>
    </div>
    <div>
        <h2 class="head-second text-center" style="">
            出差计划
        </h2>
        <div class="mb-20" style="width:99%;">
            <div class="space-5 clear"></div>
            <table class="table-small">
                <thead>
                <tr>
                    <th class="width-150">序号</th>
                    <th class="width-150">计划日期</th>
                    <th class="width-150">接洽对象</th>
                    <th class="width-250">具体工作项目及目标</th>
                    <th class="width-150">操作</th>
                </tr>
                </thead>
                <tbody id="vacc_body">
                <tr>
                    <td>
                        <input type="text" name="vacc[]" placeholder="请输入工号"
                               class="width-150  no-border text-center" onblur="job_num(this)">
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><a onclick="reset(this)">重置</a> <a onclick="vacc_del(this)">删除</a></td>
                </tr>
                </tbody>
            </table>
        </div>
        <p  class="text-right mr-50">
            <button class="button-blue text-center" onclick="vacc_add()" type="button">+ 添&nbsp;加</button>
        </p>
    </div>
    <div class="space-20"></div>
    <div>
        <h2 class="head-second text-center">
            差旅费详情
        </h2>
        <div class="mb-20" style="width:99%;">
            <table width="200" class="table-small">
                <tr>
                    <td colspan="2" class="width-700">出差费用标准(币别RMB)</td>
                    <td class="width-300">预计差旅费(借)</td>
                </tr>
                <tr>
                    <td class="width-350">A.住宿费 </td>
                    <td class="width-350">--        元/天 </td>
                    <td><input>元</td>
                </tr>
                <tr>
                    <td>B.膳食费 </td>
                    <td>--        元/天 </td>
                    <td> <input>元</td>
                </tr>
                <tr>
                    <td>C.交通费 </td>
                    <td>--        元 </td>
                    <td> <input>元</td>
                </tr>
                <tr>
                    <td>D.交际费 </td>
                    <td>--        元 </td>
                    <td> <input>元</td>
                </tr>
                <tr>
                    <td>E.其它费用 </td>
                    <td>--        元 </td>
                    <td> <input>元</td>
                </tr>
                <tr>
                    <td  style="height: 60px">
                        费用总计(A+B+C+D+E)
                    </td>
                    <td>--元</td>
                    <td>--元</td>
                </tr>
            </table>
            <div class="space-5 clear"></div>
            <!--<table class="table-small">
                <thead>
                <tr>
                    <th class="width-500">出差费用标准(币别RMB)</th>
                    <th class="width-500">预计差旅费(借)</th>
                </tr>
                </thead>
                <tbody id="vacc_body">
                <tr>
                    <td class="width-200">
                        <input type="text" name="vacc[]" placeholder="请输入工号"
                               class="width-150  no-border text-center" onblur="job_num(this)">
                    </td>
                    <td></td>
                    <td class="width-100"></td>
                </tr>
                </tbody>
            </table>-->
        </div>
    </div>
    <div class="space-40 ml-50"></div>
    <button class="button-blue-big ml-320" type="submit">提交</button>
    <button class="button-white-big ml-20" type="button" onclick="history.go(-1)">返回</button>
    <?php ActiveForm::end(); ?>
    <script>
        $(function () {
            ajaxSubmitForm($("#add-form"));//ajax提交

            $('#firmCate').change(function () {
                $('#pdfirm-firm_category_id').val($('#firmCate').val())
            });

            $('#goback').click(function () {
                history.back(-1);
            });

            /**
             *地址联动查询
             */
            $('.disName').on("change", function () {
                var $select = $(this);
                //console.log($select);
                getNextDistrict($select);
            });
            //遞歸清除級聯選項
            function clearOption($select) {
                if ($select == null) {
                    $select = $("#disName_1")
                }
                $tagNmae = $select.next().prop("tagName");
                if ($select.next().length != 0 && $tagNmae =='SELECT') {
                    $select.next().html('<option value=>請選擇</option>');
                    clearOption($select.next());
                }
            }

            function getNextDistrict($select) {
                var id = $select.val();
                //console.log(id);
                if (id == "") {
                    clearOption($select);
                    return;
                }
                $.ajax({
                    type: "get",
                    dataType: "json",
                    async: false,
                    data: {"id": id},
                    url: "<?=Url::to(['/ptdt/firm/get-district']) ?>",
                    success: function (data) {
//                        console.log(data);
                        var $nextSelect = $select.next("select");
//                        console.log();
                        clearOption($nextSelect);
                        $nextSelect.html('<option value>請選擇</option>')
                        if ($nextSelect.length != 0)
                            for (var x in data) {
                                $nextSelect.append('<option value="' + data[x].district_id + '" >' + data[x].district_name + '</option>')
                            }
                    }
                })
            }
        });
    </script>