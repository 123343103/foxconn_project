<?php
/**
 * Created by PhpStorm.
 * User: G0007903
 * Date: 2017/11/28
 */
use app\assets\JqueryUIAsset;  //ajax引用jQuery样
use yii\widgets\ActiveForm;
JqueryUIAsset::register($this);
\app\assets\JeDateAsset::register($this);
?>
<?php $form =ActiveForm::begin(['method' => "get", "action" => "index"]);?>
<div class="content">
    <h1 class="head-first">收货通知</h1>
    <div style="margin-bottom: 10px">
    <a>采购部门:<span><?=$hr['hr'][0]['organization_name']?></span></a>
    <a style="margin-left: 200px">采购人:<span><?=$hr['hr'][0]['staff_name']?></span></a>
    </div>
        <div style="width: 600px" id="messages"></div>
    <div style="margin-top: 15px;text-align: center;">
        <button id="submit" class="button-blue-big" type="submit">确定</button>
        <button id="cancel" style="margin-left: 20px" class="button-white-big" type="button">取消</button>
    </div>
</div>
<script>
    $(function(){
        $("#messages").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "prch_dt_id",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            singleSelect: false,
            columns:[[
                {field: 'ck', checkbox: true},
                {field: "req_dt_id", title: "商品详情ID", hidden: true},
                {field: "part_no", title: " 料号", width: 181},
                {field: "pdt_name", title: "品名", width: 181},
                {field: "tp_spec", title: "规格", width: 181},
                {field: "brand", title: "品牌", width: 181},
                {field: "unit", title: "单位", width: 181},
//                {field: "spp_id", title: "供应商代码", width: 181},
//                {field: "spp_fname", title: "供应商名称", width: 181},
                {field: "prch_num", title: "采购数量", width: 181},
                {field: "num", title: "到货数量",width: 181,formatter: function (value, rowData, rowIndex) {
                     $i=rowData.prch_num;
                    return '<input type="text" value="<?=$i?>">';
                }},
                {field: "price", title: "单价", width: 181},
                {field: "total_amount", title: "金额", width: 181},
//                {field: "bsp_svalue", title: "付款方式", width: 181},
//                {field: "tax", title: "税别/税率", width: 181},
//                {field: "cur_code", title: "币别", width: 181},
                {field: "date", title: "到货时间", width: 181,formatter: function (value, rowData, rowIndex) {
                    return '<input type="text" class="Wdate" onclick="date()" id="_date" readonly="readonly">';
                }},
                {field: "wh_addr", title: "收货中心", width: 181},
                {field: "remark", title: "备注",width: 181,formatter: function (value, rowData, rowIndex) {
            return '<input type="text">';
        }}
//                {field: "req_no", title: "关联单号", width: 181}
            ]],
            onLoadSuccess: function (messages) {
//                datagridTip("#messages");
                setMenuHeight();
            }
        });
        $("#cancel").click(function () {
            parent.$.fancybox.close();
        });
//        ajaxSubmitForm($("#form"),'',function(data){
//            if(data.status == 1){
//                parent.layer.alert(data.msg,{icon:1,end: function () {
//                    parent.$.fancybox.close();
//                    parent.window.location.reload();
//                }});
//            } else {
//                parent.layer.alert(data.msg, {
//                    icon: 1, end: function () {
//                        return false;
//                    }
//                });
//            }
//        })
        //申请时间
    });
    function date() {
            WdatePicker({
                skin:"whyGreen",
                dateFmt:"yyyy-MM-dd HH:mm"
            });
    }
</script>

