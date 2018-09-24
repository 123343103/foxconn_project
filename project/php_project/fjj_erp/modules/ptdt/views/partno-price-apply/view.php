<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2016/12/15
 * Time: 上午 10:34
 */
use yii\helpers\Url;
use app\classes\Menu;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->params['homeLike'] = ['label' => '商品库管理', 'url' => ['/']];
$this->params['breadcrumbs'][] = ['label' => '商品定价','url'=>['index']];
$this->params['breadcrumbs'][] = ['label' => '定价申请列表'];
?>
<div class="content">
    <div class="mb-30">
        <h2 class="head-second">
            <p>商品定价申請详情</p>
        </h2>


        <div class="mb-20">
            <label class="width-130 ">状态</label>
            <span><?=$model['status'];?></span>
        </div>
        <div class="mb-20">
            <label class="width-130 ">申请单号</label>
            <span><?=$model['price_no']; ?></span>
        </div>
        <div class="mb-20">
            <label class="width-130 ">备注</label>
            <span><?=$model['remark']; ?></span>
        </div>

        <div class="mb-20 step-container">
            <div class="step-bar">
                <div class="step-bar-inner"></div>
            </div>
            <div class="step-circle">
                <div>
                    <span>1</span>
                    <p>新增申请</p>
                    <p><?=$model['creatdate'];?></p>
                </div>
                <div>
                    <span>2</span>
                    <p>定价作业</p>
                </div>
                <div>
                    <span>3</span>
                    <p>完成</p>
                </div>
            </div>
        </div>


        <div class="mb-10">
            <?= Menu::isAction('/ptdt/partno-price-apply/edit')?Html::button("修改",["id"=>"edit","class"=>"button-blue"]):'' ?>
            <?= Menu::isAction('/ptdt/partno-price-apply/delete')?Html::button("删除",["id"=>"delete","class"=>"button-blue"]):'' ?>
            <?= Menu::isAction('/ptdt/partno-price-apply/index')?Html::button("切换列表",["id"=>"to-list","class"=>"button-blue"]):'' ?>
            <?= Menu::isAction('/ptdt/partno-price-confirm/edit')?Html::button("去定价",["id"=>"to-confirm","class"=>"button-blue"]):'' ?>
            <?= Menu::isAction('/ptdt/partno-price-apply/create')?Html::button("新增申请",["id"=>"create","class"=>"button-blue"]):'' ?>
            <?= Menu::isAction('/ptdt/partno-price-apply/view')?Html::button("送审",["id"=>"to-check","class"=>"button-blue"]):'' ?>
            <?= Menu::isAction('/ptdt/partno-price-apply/index')?Html::button("导出",["id"=>"export","class"=>"button-blue"]):'' ?>
        </div>
        <div style="height:2px;background:#9acfea;"></div>



        <div class="space-10"
        ></div>
        <h2 class="head-second">
            <p>料号其他信息维护</p>
        </h2>



        <div class="space-30"></div>

        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">商品经理人：</td>
                <td width="30%" class="no-border vertical-center"><?=$model['pdt_manager'];?></td>
                <td width="10%" class="no-border vertical-center">定价类型：</td>
                <td width="20%" class="no-border vertical-center"><?=$model['price_type'];?></td>
                <td width="10%" class="no-border vertical-center">定价发起来源：</td>
                <td width="20%" class="no-border vertical-center"><?=$model['price_from'];?></td>
            </tr>
        </table>


        <div class="space-30"></div>



        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">主要竞争对手：</td>
                <td width="30%" class="no-border vertical-center"><?=$model['archrival'];?></td>
                <td width="10%" class="no-border vertical-center">市场均价：</td>
                <td width="20%" class="no-border vertical-center"><?=$model['market_price'];?></td>
                <td width="10%" class="no-border vertical-center">适用产业：</td>
                <td width="20%" class="no-border vertical-center"><?=$model['usefor'];?></td>
            </tr>
        </table>


        <div class="space-30"></div>


        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">是否客制化：</td>
                <td width="30%" class="no-border vertical-center"><?=$model['iskz']==1?"Y":"N";?></td>
                <td width="10%" class="no-border vertical-center">是否取得代理：</td>
                <td width="20%" class="no-border vertical-center"><?=$model['isproxy']==1?"Y":"N";?></td>
                <td width="10%" class="no-border vertical-center">是否线上销售：</td>
                <td width="20%" class="no-border vertical-center"><?=$model['isonlinesell']==1?"Y":"N";?></td>
            </tr>
        </table>


        <div class="space-30"></div>



        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">法务风险等级：</td>
                <td width="30%" class="no-border vertical-center"><?=$model['risk_level'];?></td>
                <td width="10%" class="no-border vertical-center">是否拳头商品：</td>
                <td width="20%" class="no-border vertical-center"><?=$model['istitle']==1?"Y":"N";?></td>
                <td width="10%" class="no-border vertical-center">商品定位：</td>
                <td width="20%" class="no-border vertical-center"><?=$model['pdt_level'];?></td>
            </tr>
        </table>



        <div class="space-30"></div>



        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">价格有效日期：</td>
                <td width="30%" class="no-border vertical-center"><?=$model['valid_date'];?></td>
                <td width="10%" class="no-border vertical-center">品牌：</td>
                <td width="20%" class="no-border vertical-center"><?=$model['brand'];?></td>
                <td width="10%" class="no-border vertical-center">发到销售系统：</td>
                <td width="20%" class="no-border vertical-center"><?=$model['isto_xs']==1?"Y":"N"; ?></td>
            </tr>
        </table>



        <div class="space-30"></div>



        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">包装规格：</td>
                <td width="30%" class="no-border vertical-center"><?=$model['packagespc'];?></td>
                <td width="10%" class="no-border vertical-center">销售区域：</td>
                <td width="50%" class="no-border vertical-center"><?=$model['salearea'];?></td>
            </tr>
        </table>



        <div class="space-30"></div>



        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="15%" class="no-border vertical-center">是否关联料号定价：</td>
                <td width="85%" class="no-border vertical-center"><?=empty($model['isrelation'])?"N":"Y";?></td>
            </tr>
        </table>


        <div class="space-30"></div>



        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">补充说明：</td>
                <td width="90%" class="no-border vertical-center"><?=$model['remark'];?></td>
            </tr>
        </table>

        <div class="space-30"></div>



        <div class="space-10"
        ></div>
        <h2 class="head-second">
            <p>料号基本信息</p>
        </h2>
        <table class="table">
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


        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center"><span style="color:red;">*</span> 工号：</td>
                <td width="30%" class="no-border vertical-center"><?=$model['creator']['staff_code'];?></td>
                <td width="10%" class="no-border vertical-center">创建人：</td>
                <td width="20%" class="no-border vertical-center"><?=$model['creator']['staff_name'];?></td>
                <td width="10%" class="no-border vertical-center">部门：</td>
                <td width="20%" class="no-border vertical-center"><?=$model['creator']['organization_code'];?></td>
            </tr>
        </table>


        <div class="space-30"></div>


        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="10%" class="no-border vertical-center">邮箱：</td>
                <td width="30%" class="no-border vertical-center"><?=$model['creator']['staff_email'];?></td>
                <td width="10%" class="no-border vertical-center">创建时间：</td>
                <td width="20%" class="no-border vertical-center"><?=$model['creator']['create_at'];?></td>
                <td width="10%" class="no-border vertical-center">联系方式：</td>
                <td width="20%" class="no-border vertical-center"><?=$model['creator']['staff_mobile'];?></td>
            </tr>
        </table>



        <div class="space-30"></div>


        <div class="text-center pt-20 pb-20">
            <?php $form=ActiveForm::begin([
                "method"=>"post",
                "id"=>"edit-form",
                "action"=>["edit",'id'=>$model['id']]
            ]); ?>
            <input type="hidden" name="id" value="<?=$model['id']?>">
            <button class="button-blue-big" type="submit" name="status" value="1" <?=$model['status']==1?"disabled":"";?>>通过</button>
            <button class="button-blue-big ml-10 mr-10" type="submit" name="status" value="5" <?=$model['status']==5?"disabled":"";?>>驳回</button>
            <button class="button-white-big" type="button" onclick="history.go(-1)">返回</button>
            <?php $form->end();?>
        </div>


        <div class="space-30"></div>
    </div>

    <style type="text/css">
        .step-container{
            font-size: 0;
            height: 100px;
            width:600px;
            position: relative;
            margin: 0 auto;
            margin-bottom:50px;
        }
        .step-bar{
            position: absolute;
            width:100%;
            height:10px;
            border:#666 1px solid;
            display: inline-block;
        }
        .step-bar-inner{
            width:25%;
            height: 10px;
            background:#eee;
        }
        .step-circle{
            position: absolute;
            top:50%;
            width:100%;
            margin-top:-15px;
            font-size: 0;
        }

        .step-circle div{
            width:200px;
            text-align: center;
            font-size: 14px;
            color:#666;
            position:absolute;

        }
        .step-circle span{
            width:30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            background: #eee;
            border-radius: 50%;
            border:#666 1px solid;
            font-size: 14px;
            color:#666;
        }


        .step-circle div{
            top:-45px;
        }
        .step-circle p{
            margin-top:20px;
        }
        .step-circle div:nth-child(1){
            left:0px;
            margin-left:-100px;
        }
        .step-circle div:nth-child(2){
            left:50%;
            margin-left: -100px;
        }
        .step-circle div:nth-child(3){
            right:0px;
            margin-right:-100px;
        }

        button:hover{
            border:none;
        }
    </style>
    <script>
        $(function(){
            ajaxSubmitForm($("#edit-form"));


            $("[name=salearea]").click(function(){
                if($(this).index("[name=salearea]")==2){
                    $("#area-selector").css("display","inline-block");
                }else{
                    $("#area-selector").css("display","none");
                }
            });

            //区域选择
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

            $("#create").click(function(){
                window.location.href="<?=Url::to(['create'])?>";
            });
            $("#edit").click(function(){
                window.location.href="<?=Url::to(['edit','id'=>\Yii::$app->request->get('id')])?>";
            });
            $("#to-list").click(function(){
                window.location.href="<?=Url::to(['index'])?>";
            });
            $("#to-confirm").click(function(){
                window.location.href="<?=Url::to(['partno-price-confirm/edit','id'=>\Yii::$app->request->get('id')])?>";
            });
            $("#to-check").click(function(){
                window.location.href="<?=Url::to(['edit','id'=>\Yii::$app->request->get('id')])?>";
            });
            $("#export").click(function(){
                window.location.href="<?=Url::to(['index','export'=>1,'id'=>\Yii::$app->request->get('id')])?>";
            });
            $("#return").click(function(){
                window.history.go(-1);
            });

            //删除定价申请
            $("#delete").click(function(){
                layer.confirm("确定要删除选中的记录吗？",{
                    btn: ['確定', '取消'],
                    icon: 2
                },function(){
                    $.get("<?=Url::to(['delete','id'=>\Yii::$app->request->get('id')])?>",function(res){
                        obj=JSON.parse(res);
                        if(obj.flag==1){
                            layer.alert("删除成功",{icon:2},function(){
                                window.location.href="<?=Url::to(['index'])?>";
                            });
                        }else{
                            layer.alert("删除失败",{icon:2},function(){
                                window.location.href="<?=Url::to(['index'])?>";
                            });
                        }
                    });
                });
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