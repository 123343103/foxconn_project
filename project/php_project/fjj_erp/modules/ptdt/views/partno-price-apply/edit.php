<?php
/**
 * User: F1676624 Date: 2016/12/2
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->params['homeLike'] = ['label' => '商品库管理', 'url' => ['/']];
$this->params['breadcrumbs'][] = ['label' => '定价列表','url'=>['index']];
$this->params['breadcrumbs'][] = ['label' => '定价修改'];
?>
<div class="content">
    <div class="mb-30">
        <h2 class="head-second">
            <p>新增商品定价申请修改</p>
        </h2>
        <?php $form = ActiveForm::begin(['id' => 'add-form','method'=>"POST"]); ?>
            <div class="mb-20">
                <label class="width-130 ">商品经理人</label>
                <input name="pdt_manager" class="width-150 " id="pdt_manager" value="<?=$model['pdt_manager']; ?>">
                <label class="width-150">定价类型</label>
                <?=Html::dropDownList("price_type",$model["price_type"],$downlist["price_type"],["class"=>"width-150"])?>
                <label class="width-150 ">定价发起来源</label>
                <?=Html::dropDownList("price_from",$model["price_from"],$downlist["price_from"],["class"=>"width-150"])?>
            </div>
            <div class="mb-20">
                <label class="width-130">主要竞争对手</label>
                <input name="archrival" class="width-150 " id="center" value=" <?=$model['archrival'];?>">
                <label class="width-150">市场均价</label>
                <input name="market_price" class="width-150 " id="applydep" value=" <?=$model['market_price']; ?>">
                <label class="width-150 ">适用产业</label>
                <input name="usefor" class="width-150 " id="applydep" value=" <?= $model['usefor']; ?>">
            </div>
            <div class="mb-20">
                <label class="width-130">是否客制化</label>
                <?=Html::dropDownList("iskz",$model["iskz"],[""=>"请选择","1"=>"是","0"=>"否"],["class"=>"width-150"])?>

                <label class="width-150">是否取得代理</label>
                <?=Html::dropDownList("isproxy",$model["isproxy"],[""=>"请选择","1"=>"是","0"=>"否"],["class"=>"width-150"])?>

                <label class="width-150">是否线上销售</label>
                <?=Html::dropDownList("isonlinesell",$model["isonlinesell"],[""=>"请选择","1"=>"是","0"=>"否"],["class"=>"width-150"])?>
            </div>
            <div class="mb-20">
                <label class="width-130">法务风险等级</label>
                <?=Html::dropDownList("risk_level",$model["risk_level"],$downlist["risk_level"],["class"=>"width-150"])?>
                <label class="width-150">是否拳头商品</label>
                <?=Html::dropDownList("istitle",$model["istitle"],[""=>"请选择","1"=>"是","0"=>"否"],["class"=>"width-150"])?>
                <label class="width-150">商品定位</label>
                <?=Html::dropDownList("pdt_level",$model["pdt_level"],$downlist["pdt_level"],["class"=>"width-150"])?>
            </div>
            <div class="mb-20">
                <label class="width-130">价格有效日期</label>
                <input name="valid_date" class="width-150 select-date" type="text" id="pdt_name" value=" <?=$model['valid_date']; ?>">
                <label class="width-150">品牌</label>
                <input name="brand" class="width-150 " id="iskz" value=" <?=$model['brand']; ?>">
                <label class="width-150">发到销售系统</label>
                <?=Html::dropDownList("isto_xs",$model["isto_xs"],[""=>"请选择","1"=>"是","0"=>"否"],["class"=>"width-150"])?>
            </div>
            <div class="mb-20">
                <label class="width-130">包装规格</label>
                <input name="packagespc" class="width-150 " id="packagespc" value=" <?=$model['packagespc']; ?>">
            </div>
            <div class="mb-20">
                <label class="width-130">补充说明</label>
                <textarea class="width-800" rows="3" id="type_description"
                          name="remark"><?=$model['remark']; ?></textarea>
            </div>

            <div class="space-10"
            ></div>
            <h2 class="head-second">
                <p>料号基本信息</p>
            </h2>


        <div id="partNoBox" style="display: none;height:320px;overflow: hidden;">
            <input type="text" id="keywords">
            <button class="button-blue" id="partNoSearch">搜索</button>
            <button class="button-blue float-right" id="partNoEnsure">确定</button>
            <div class="space-10"></div>
            <table id="part-table" style="width:600px;">
            </table>
        </div>

        <button type="button" class="button-blue-big" id="selectPartNo">选择料号</button>
        <input type="hidden" name="part_no" value="<?=$model['part_no'];?>">
        <div class="space-10"></div>
            <table class="table" id="partno_info">
                <thead>
                <th>料号</th>
                <th>品名</th>
                <th>规格型号</th>
                <th>一阶</th>
                <th>二阶</th>
                <th>三阶</th>
                <th>四阶</th>
                <th>五阶</th>
                <th>六阶</th>
                <th>品牌</th>
                </thead>
                <tbody>
                <tr>
                    <td><?=$model['product']['pdt_no'];?></td>
                    <td><?=$model['product']['pdt_name'];?></td>
                    <td><?=$model['product']['tp_spec'];?></td>
                    <td><?=$model['type_1'];?></td>
                    <td><?=$model['type_2'];?></td>
                    <td><?=$model['type_3'];?></td>
                    <td><?=$model['type_4'];?></td>
                    <td><?=$model['type_5'];?></td>
                    <td><?=$model['type_6'];?></td>
                    <td><?=$model['product']['brand_name'];?></td>
                </tr>
                </tbody>
            </table>
            <div class="space-30"></div>
            <h2 class="head-second">
                <p>创建人信息</p>
            </h2>
            <div class="mb-20">
                <label class="width-100" for=""><span style="color:red;">*</span>工号</label>
                <input name="creatby" class="width-150 easyui-validatebox validatebox-text validatebox-invalid" type="text" data-options="required:'true'" value="<?=$model['creator']['staff_code'];?>">
                <label class="width-100" for=""><span style="color:red;">*</span>创建人</label>
                <input class="width-150" type="text" value="<?=$model['creator']['staff_name'];?>">
                <label class="width-100" for="">部门</label>
                <input class="width-150" type="text" value="<?=$model['creator']['organization_code'];?>">
            </div>
        <div class="mb-20">
            <label class="width-100" for="">邮箱</label>
            <input class="width-150" type="text"  value="<?=$model['creator']['staff_email'];?>">
            <label name="creatdate" class="width-100" for="">创建时间</label>
            <input name="creatdate" class="width-150 select-date" value="">
            <label class="width-100" for="">联系方式</label>
            <input class="width-150" type="text" value="<?=$model['creator']['staff_mobile'];?>">
        </div>
            <div class="space-30"></div>
            <div class="text-center pt-20 pb-20">
                <button class="button-blue-big" type="submit">保存</button>
                <button class="button-white-big ml-20" type="button" onclick="history.go(-1)">取消</button>
            </div>

    <?php $form->end(); ?>

        <div class="space-30"></div>
    </div>

    <script>
        $(function(){
            ajaxSubmitForm($("#add-form"));

            $("[name=salearea]").click(function(){
                if($(this).index("[name=salearea]")==2){
                    $("#area-selector").css("display","inline-block");
                }else{
                    $("#area-selector").css("display","none");
                }
            });



            //选择料号
            $("#selectPartNo").click(function(){
                $.fancybox({
                    href:"#partNoBox",
                    afterLoad:function(){
                        $("#part-table").datagrid({
                            url:"<?=Url::to(['partno-select'])?>",
                            method:"get",
                            idField: "id",
                            pagination:true,
                            pageSize:5,
                            pageList:[5,10,15],
                            singleSelect: true,
                            columns:[[
                                {
                                    field:"pdt_no",
                                    title:"料号",
                                    width:300
                                },
                                {
                                    field:"pdt_name",
                                    title:"品名",
                                    width:300
                                }
                            ]]
                        });
                    }
                });
            });


            //料号搜索
            $("#partNoSearch").click(function(){
                $("#part-table").datagrid('load',{
                    url:"<?=Url::to(['index'])?>",
                    part_no:$("#keywords").val()

                });
            });


            $("#partNoEnsure").click(function(){
                var row=$("#part-table").datagrid('getSelected');
                $("#partno_info tbody").html("<tr><td>"+row.pdt_no+"</td><td>"+row.pdt_name+"</td><td>"+row.tp_spec+"</td><td>"+row.type_1+"</td><td>"+row.type_2+"</td><td>"+row.type_3+"</td><td>"+row.type_4+"</td><td>"+row.type_5+"</td><td>"+row.type_6+"</td><td>"+row.brand_name+"</td></tr>");
                $("[name=part_no]").val(row.pdt_no);
                $.fancybox.close();
            });



            //地区选择
            $('.disName').on("change", function () {
                var $select = $(this);
                //console.log($select);
                getNextDistrict($select);

                var distArr=[];
                $(this).prevAll(".disName").andSelf().each(function(){
                    distArr.push($(this).children(":selected").html());
                });
                $("#cur-addr-input").val(distArr.join());
                $("#cur-addr-text").text(distArr.join());
            });
            //遞歸清除級聯選項
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
        });
    </script>