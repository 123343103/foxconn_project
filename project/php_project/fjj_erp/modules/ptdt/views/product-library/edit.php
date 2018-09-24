<?php
/**
 * User: F1676624 Date: 2016/12/2
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->params['homeLike'] = ['label' => '商品库管理', 'url' => ['/']];
$this->params['breadcrumbs'][] = ['label' => '商品库','url'=>['index']];
$this->params['breadcrumbs'][] = ['label' => '编辑商品'];
$this->title = '编辑商品';/*BUG修正 增加title*/
?>
<div class="content">
    <div class="mb-30">
        <h2 class="head-second">
            <p>商品详情</p>
        </h2>
        <?php $form=ActiveForm::begin([
                "id"=>"edit-form",
                "method"=>"POST"
        ]);?>
            <div class="mb-20 ml-20">
                <label class="width-100">料号</label>
                <input name="pdt_no" class="width-120 " readonly="readonly" id="part_no" value="<?=$model->pdt_no;?>">
                <label class="width-100 ">商品名称</label>
                <input name="pdt_name" class="width-120 " id="pdt_name" value="<?= $model->pdt_name ?>">
                <label class="width-100">品牌</label>
                <input name="brand" class="width-120 " id="brand" value="<?= $model->brand_name ?>">
                <label class="width-80 ">商品经理人</label>
                <input name="pdt_manager" class="width-120 " id="pdt_manager" value="<?= $model->pdt_manager ?>">
            </div>
            <div class="mb-20">
                <label class="width-120" for="type">类别</label>
                <select class="width-130 type" id="type_1" name="type_1" data-options="required:'true'">
                    <option value>请选择</option>
                    <?php foreach ($productTypeIdToValue as $key => $val) { ?>
                        <option
                                value="<?= $key ?>" <?= isset($model->type_1) && $model->type_1 == $val ? "selected" : null ?>><?= $val ?></option>
                    <?php } ?>
                </select>
                <select class="width-130 type easyui-validatebox validatebox-text validatebox-invalid" id="type_2" name="type_2" data-options="required:'true'">
                    <option value>请选择</option>
                </select>
                <select class="width-130 type easyui-validatebox validatebox-text validatebox-invalid" id="type_3" name="type_3" data-options="required:'true'">
                    <option value>请选择</option>
                </select>
                <select class="width-130 type easyui-validatebox validatebox-text validatebox-invalid" id="type_4" name="type_4" data-options="required:'true'">
                    <option value>请选择</option>
                </select>
                <select class="width-130 type easyui-validatebox validatebox-text validatebox-invalid" id="type_5" name="type_5" data-options="required:'true'">
                    <option value>请选择</option>
                </select>
                <select class="width-130 type easyui-validatebox validatebox-text validatebox-invalid" id="type_6" name="type_6" data-options="required:'true'">
                    <option value>请选择</option>
                </select>
            </div>
            <div class="mb-20 ml-20">
                <label class="width-100">开发中心</label>
                <select class="width-120" name="center" id="center">
                    <option <?=$model->center=="机构件开发中心"?"selected":""?> value="机构件开发中心">机构件开发中心</option>
                    <option <?=$model->center=="原材料开发中心"?"selected":""?> value="原材料开发中心">原材料开发中心</option>
                    <option <?=$model->center=="设备开发中心"?"selected":""?>  value="设备开发中心">设备开发中心</option>
                    <option <?=$model->center=="生产辅助材料开发中心"?"selected":""?> value="生产辅助材料开发中心">生产辅助材料开发中心</option>
                </select>
                <label class="width-100">开发部</label>
                <input name="applydep" class="width-120 " id="applydep" value="<?= $model->applydep ?>">
                <label class="width-100 ">Community</label>
                <input class="width-120 " id="applydep" value="<?= $model->category_id; ?>">
            </div>
            <div class="mb-20">
                    <label class="width-120">销售区域</label>
                    <input <?=$model->sale_area==0?"checked":"";?> name="sale_area" type="radio" value="0" onfocus="$('#area-selector').css('display','none')"><span>全国</span>
                    <input <?=$model->sale_area===1?"checked":"";?> name="sale_area" type="radio" value="1" onfocus="$('#area-selector').css('display','none')"><span>全国(不含港澳台)</span>
                    <input id="district_id" <?=$model->sale_area>1?"checked":"";?> name="sale_area" type="radio" value="<?=$model->sale_area?>" onfocus="$('#area-selector').css('display','block')"><span>省</span>
                    <div id="area-selector" class="ml-100" style="display:<?=$model->sale_area>1?"block":"none"?>;">
                    <select class=" ml-30 width-130 disName" name="" id="disName_1">
                        <option value="">选择</option>
                        <?php if($model->sale_area){ ?>
                            <?php foreach($district[oneLevel] as $k=>$v){ ?>
                                <option <?=$v[district_id]==$district[oneLevelId]?"selected":""?> value="<?=$v[district_id]?>"><?=$v[district_name];?></option>
                            <?php }}else{ ?>
                            <option value="1">中国</option>
                        <?php } ?>
                    </select>
                    <select class="width-130 disName" name="" id="disName_2">
                        <option value="">选择</option>
                        <?php foreach($district[twoLevel] as $k=>$v){ ?>
                            <option <?=$v[district_id]==$district[twoLevelId]?"selected":""?> value="<?=$v[district_id]?>"><?=$v[district_name];?></option>
                        <?php } ?>
                    </select>
                    <select class="width-130 disName" name="" id="disName_3">
                        <option value="">选择</option>
                        <?php foreach($district[threeLevel] as $k=>$v){ ?>
                            <option <?=$v[district_id]==$district[threeLevelId]?"selected":""?> value="<?=$v[district_id]?>"><?=$v[district_name];?></option>
                        <?php } ?>
                    </select>
                    <select class="width-130 disName" onchange="$('#district_id').val($(this).val())" id="disName_4">
                        <option value="">选择</option>
                        <?php foreach($district[fourLevel] as $k=>$v){ ?>
                            <option <?=$v[district_id]==$district[fourLevelId]?"selected":""?> value="<?=$v[district_id]?>"><?=$v[district_name];?></option>
                        <?php } ?>
                    </select>
                    </div>
            </div>

            <div class="mb-20 ml-20">
                <label class="width-100">富贸料号</label>
                <input class="width-120 " id="pdt_name" value="">
                <label class="width-100 ">供应商品名</label>
                <input class="width-120 " value="<?=$model->pdt_name;?>">
                <label class="width-100">型号/规格</label>
                <input name="tp_spec" class="width-120 " id="tp_spec" value="<?= $model->tp_spec ?>">
            </div>
            <div class="mb-20 ml-20">
                <label class="width-100">适用产业</label>
                <input name="usefor" class="width-120 " id="usefor" value="<?= $model->usefor ?>">
<!--                <label class="width-100">交货条件</label>-->
<!--                <input name="" class="width-120 " id="pdt_name" value="">-->
<!--                <label class="width-100">付款条件</label>-->
<!--                <input class="width-120 " id="pdt_name" value="">-->
                <label class="width-100">包装规格</label>
                <input name="packagespc" class="width-120 " id="pdt_name" value="<?=$model->packagespc;?>">
            </div>
            <div class="mb-20 ml-20">
                <label class="width-100">L/T（天）</label>
                <input class="width-120 " id="pdt_name" value="">
                <label class="width-100">是否客制化</label>
                <select class="width-120" name="iskz" id="">
                    <option value="">请选择</option>
                    <option <?=$model->iskz==1?"selected":""?> value="1">是</option>
                    <option <?=$model->iskz==0?"selected":""?> value="0">否</option>
                </select>
                <label class="width-100">是否线上销售</label>
                <select class="width-120" name="isonlinesell" id="">
                    <option value="">请选择</option>
                    <option <?=$model->isonlinesell==1?"selected":""?> value="1">是</option>
                    <option <?=$model->isonlinesell==0?"selected":""?> value="0">否</option>
                </select>

                <label class="width-80">是否代理</label>
                <select class="width-120" name="isproxy" id="">
                    <option value="">请选择</option>
                    <option <?=$model->isproxy==1?"selected":""?> value="1">是</option>
                    <option <?=$model->isproxy==0?"selected":""?> value="0">否</option>
                </select>
            </div>
            <div class="mb-20 ml-20">
                <label class="width-100">法务风险等级</label>
                <select class="width-120" name="risk_level" id="">
                    <option value="">请选择</option>
                    <option <?=$model->risk_level==0?"selected":""?> value="0">高</option>
                    <option <?=$model->risk_level==1?"selected":""?> value="1">中</option>
                    <option <?=$model->risk_level==2?"selected":""?> value="2">低</option>
                </select>
                <label class="width-100">是否拳头商品</label>
                <select class="width-120" name="istitle" id="">
                    <option value="">请选择</option>
                    <option <?=$model->istitle==1?"selected":""?> value="1">是</option>
                    <option <?=$model->istitle==0?"selected":""?> value="0">否</option>
                </select>
                <label class="width-100">商品定位</label>
                <select class="width-120" name="pdt_level" id="">
                    <option value="">请选择</option>
                    <option <?=$model->pdt_level==1?"selected":""?> value="1">高</option>
                    <option <?=$model->pdt_level==2?"selected":""?> value="2">中</option>
                    <option <?=$model->pdt_level==3?"selected":""?> value="3">低</option>
                </select>
                <label class="width-80">定价类型</label>
                <select class="width-120" name="price_type" id="">
                    <option value="">请选择</option>
                    <option <?=$model->price_type==0?"selected":""?> value="0">新增</option>
                    <option <?=$model->price_type==1?"selected":""?> value="1">降价</option>
                    <option <?=$model->price_type==2?"selected":""?> value="2">涨价</option>
                    <option <?=$model->price_type==3?"selected":""?> value="3">定价不变，利润率变更</option>
                    <option <?=$model->price_type==4?"selected":""?> value="4">延期</option>
                </select>
            </div>
            <div class="mb-20 ml-20">
                <label class="width-100">是否批次管理</label>
                <input class="width-120 " id="pdt_name" value="">
                <label class="width-100">状态</label>
                <select class="width-120" name="status" id="">
                    <option value="">请选择</option>
                    <option <?=$model->status=="封存"?"selected":""?> value="0">封存</option>
                    <option <?=$model->status=="正常"?"selected":""?> value="1">正常</option>
                </select>
                <label class="width-100">库存安全量</label>
                <input class="width-120 " id="pdt_name" value="">
                <label class="width-80">商品属性</label>
                <input class="width-120 " id="pdt_name" value="">
            </div>


            <div class="space-20"></div>
            <div id="load-content" class="overflow-auto"></div>
            <div class="space-30"></div>
            <div class="text-center pt-20 pb-20">
                <button class="button-blue-big" type="submit">保存</button>
                <button class="button-white-big ml-20" type="button" onclick="history.go(-1)">取消</button>
            </div>
        <?php $form->end(); ?>

        <div class="space-30"></div>
    </div>
    <script>
        window.onload=function(){
            var type_2="<?=$model->type_2;?>";
            var type_3="<?=$model->type_3;?>";
            var type_4="<?=$model->type_4;?>";
            var type_5="<?=$model->type_5;?>";
            var type_6="<?=$model->type_6;?>";

            ajaxSubmitForm($("#edit-form"));


            var id = getUrlParam('id');
            $('#load-content').load("<?=Url::to(['/ptdt/product-library/load-price']) ?>?id=" + id, function () {
                setMenuHeight();
            });


            //选中当前产品多级分类
            $('.type').each(function(){
                if($(this).index()<6) {
                    if ($(this).val() != "") {
                        getMyNextType($(this), "<?=Url::to(['/ptdt/product-library/get-product-type']) ?>", "select");
                        var index = eval("type_" + ($(this).index() + 1));
                        $(this).next().children(":contains('" + index + "')").prop("selected", true);
                    }
                }
            });


            $("[name=salearea]").click(function(){
                if($(this).index("[name=salearea]")==2){
                    $("#area-selector").css("display","inline-block");
                }else{
                    $("#area-selector").css("display","none");
        }
            });

            //产品多级分类选择
            $('.type').on("change", function () {
                var $select = $(this);
                getMyNextType($select, "<?=Url::to(['/ptdt/product-library/get-product-type']) ?>", "select");
            });

            //获取地区
            $('.disName').on("change", function () {
                var $select = $(this);
                getNextDistrict($select);

                var distArr=[];
                $(this).prevAll(".disName").andSelf().each(function(){
                    distArr.push($(this).children(":selected").html());
                });
                $("#cur-addr-input").val(distArr.join());
                $("#cur-addr-text").text(distArr.join());

            });

            //获取url参数
            function getUrlParam(name) {
                var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
                var r = window.location.search.substr(1).match(reg); //匹配目标参数
                if (r != null) return unescape(r[2]);
                return null; //返回参数值
            }



            //获取下级分类
            function getMyNextType($select, url, selectStr) {
                var id = $select.val();
                if (id == "") {
                    clearOption($select);
                    return;
                }
                $.ajax({
                    type: "get",
                    dataType: "json",
                    async: false,
                    data: {"id": id},
                    url: url,
                    success: function (data) {
                        var $nextSelect = $select.next(selectStr);
                        clearOption($nextSelect);
                        $nextSelect.html('<option value>请选择</option>');
                        if ($nextSelect.length != 0)
                            for (var x in data) {
                                $nextSelect.append('<option value="' + data[x].category_id + '" >' + data[x].category_name + '</option>');
                            }
                    }

                })
            }










            function clearOption($select) {
                if ($select == null) {
                    $select = $("#disName_1")
                }
                $tagNmae = $select.next().prop("tagName");
                if ($select.next().length != 0 && $tagNmae =='SELECT') {
                    $select.next().html('<option value=>请选择</option>');
                    clearOption($select.next());
                }
            }


            //获取下级地区
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
                        $nextSelect.html('<option value>请选择</option>')
                        if ($nextSelect.length != 0)
                            for (var x in data) {
                                $nextSelect.append('<option value="' + data[x].district_id + '" >' + data[x].district_name + '</option>')
                            }
                    }

                })
            }
        }

    </script>