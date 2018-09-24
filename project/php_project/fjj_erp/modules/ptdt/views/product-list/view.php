<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use app\widgets\ueditor\Ueditor;
$this->params['homeLike'] = ['label' => '商品开发与管理'];
$this->params['breadcrumbs'][] = ['label' => '商品列表','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '修改商品信息'];
$this->title = '修改商品信息';/*BUG修正 增加title*/
$status=\Yii::$app->request->get('status');
?>
<div class="content">
    <h1 class="head-first">
        <?= Html::encode($this->title) ?>
    </h1>







































    <style>
        ._pic{width: 120px;height: 120px;background: #5bb75b;float: left;margin-top: 5px;margin-right: 10px;}
        ._ueditor{width: 635px;margin-left: 70px;}
        .head-three{background: #c9c9c9;line-height: 20px;}
        #img_dir{  float: left;  margin-left: 10px;  height:120px;   position:relative;  width: 120px;  }
        #uploadfile{  height:120px;  width: 120px;  font-size: 30px;  position:absolute;  right:0;  top:0;  opacity: 0;  filter:alpha(opacity=0);  cursor:pointer;  }
        #img_dir img{  width: 120px;height: 120px;margin-top: 4px;  }
        ._remove{float: right;font-size: 14px;cursor: pointer; }
        ._first{margin-top: 98px;font-size: 18px;cursor: pointer; }

        .details img{
            max-width: 100%;
        }

        .label-width{
            width: 140px;
        }
        .value-width{
            width:200px;
        }
        #product_table td{
            word-break: break-all;
            word-wrap: break-word;
        }
    </style>
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <h2 class="head-three">
        <i class="icon-caret-down"></i>
        <a href="javascript:void(0)">基本信息</a>
    </h2>
    <div>
        <table width="90%" class="no-border vertical-center mb-10">
            <tbody>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="8%">商品類別：</td>
                    <td class="no-border vertical-center" width="18%"><?=$model["cat_three_level"];?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="8%">品名：</td>
                    <td class="no-border vertical-center" width="18%"><?=empty($model["pdt_name"])?"/":$model["pdt_name"]?></td>
                    <td class="no-border vertical-center label-align" width="8%">品牌：</td>
                    <td class="no-border vertical-center" width="18%"><?=empty($options["brands"][$model["brand_id"]])?"/":$options["brands"][$model["brand_id"]]?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="8%">商品标题：</td>
                    <td class="no-border vertical-center" width="18%"><?=empty($model['pdt_title'])?"/":$model['pdt_title']?></td>
                    <td class="no-border vertical-center label-align" width="8%">商品关键字：</td>
                    <td class="no-border vertical-center" width="18%"><?=empty($model['pdt_keyword'])?"/":$model['pdt_keyword']?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="8%">商品标签：</td>
                    <td class="no-border vertical-center" width="18%"><?=empty($model["pdt_label"])?"/":$model["pdt_label"]?></td>
                    <td class="no-border vertical-center label-align" width="8%">商品属性：</td>
                    <td class="no-border vertical-center" width="18%"><?=empty($options["pdt_attribute"][$model["pdt_attribute"]])?"/":$options["pdt_attribute"][$model["pdt_attribute"]]?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="8%">商品形态：</td>
                    <td class="no-border vertical-center" width="18%"><?=empty($options["pdt_form"][$model["pdt_form"]])?"/":$options["pdt_form"][$model["pdt_form"]]?></td>
                    <td class="no-border vertical-center label-align" width="8%">计量单位：</td>
                    <td class="no-border vertical-center" width="18%"><?=empty($options["pdt_unit"][$model["unit"]])?"/":$options["pdt_unit"][$model["unit"]]?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <h2 class="head-three">
        <i class="icon-caret-down"></i>
        <a href="javascript:void(0)">图片和详细说明</a>
    </h2>
    <div>
        <table width="90%" style="table-layout: fixed;" class="no-border vertical-center mb-10">
            <tbody>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-top label-align" width="8%">商品图片：</td>
                    <td class="no-border vertical-center" width="40%">
                        <?=\app\widgets\upload\PreviewUploadWidget::widget([
                            "name"=>"pdt_img[]",
                            "extensions"=>"png,jpg",
                            "items"=>$model['pdt_img'],
                            "addible"=>false
                        ])?>
                    </td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="8%">3D图片转动方式：</td>
                    <td class="no-border vertical-center" width="40%">
                        <select class="width-200 easyui-validatebox" name=""  disabled="true">
                            <option value>-请选择-</option>
                            <option value="">全球</option>
                            <option value="">半球</option>
                            <option value="">360</option>
                        </select>
                    </td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="8%">上传3D图片：</td>
                    <td class="no-border vertical-center" width="40%">
                        <input type="button" value="查看商品图片" onclick="window.location.href='<?=trim(\Yii::$app->params['FtpConfig']['httpIP'],'/').'/'.trim($model['upload3D'],'/')?>'">
                    </td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="8%">商品视频：</td>
                    <td class="no-border vertical-center" width="40%">
                        <input type="button" id="_vedio" value="管理商品视频"/>
                    </td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-top label-align" width="8%"><span class="red">*</span>详细说明：</td>
                    <td class="details no-border vertical-center" width="40%">
                        <?=Html::decode($model["details"])?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="height: 30px"></div>
    <h2 class="head-three">
        <i class="icon-caret-down"></i>
        <a href="javascript:void(0)">关联商品</a>
    </h2>
    <div>
        <table class="table">
            <thead>
            <tr>
                <th width="100"><input type='checkbox'  disabled="true"></th>
                <th width="100">商品编号</th>
                <th>商品名称</th>
                <th width="100">类别名称</th>
            </tr>
            </thead>
            <?php if(count($model["related_product"])>0){ ?>
            <tbody id="product_table">
            <?php foreach ($model["related_product"] as $product){?>
                <tr>
                    <td><input type='checkbox' disabled="true" name="RPdtPdt[r_pdt_pkid][]" value="<?=$product['pdt_pkid']?>" <?=$product["selected"]?"checked":""?> ></td>
                    <td><?=$product["pdt_no"]?></td>
                    <td><?=$product["pdt_name"]?></td>
                    <td><?=$product["catg_name"]?></td>
                </tr>
            <?php } ?>
            </tbody>
            <?php }else{ ?>
                <tfoot>
                    <tr>
                        <td colspan="4">没有相关数据！</td>
                    </tr>
                </tfoot>
            <?php } ?>
        </table>
    </div>
    <div class="space-10"></div>
    <div class="text-center">
        <?php if($status!="selling" && $status!="checking"){ ?>
            <button type="button" class="button-blue-big" id="_edits" onclick="window.location.href='<?=Url::to(["edit",'id'=>\Yii::$app->request->get('id'),'status'=>\Yii::$app->request->get('status')])?>'" >修改</button>
        <?php } ?>
        <button type="submit" style="display: none" id="_save" class="button-blue-big save-form">保存</button>
        <button class="button-white-big" onclick="window.location.href='<?=Url::to(["partno-list",'id'=>\Yii::$app->request->get('id'),'status'=>\Yii::$app->request->get('status')])?>'" type="button">查看相关料号</button>
        <button class="button-white-big" onclick="window.location.href='<?= Url::to(["index"]) ?>'" type="button">返回</button>
    </div>
    <?php ActiveForm::end(); ?>
    <script>
        $(function () {
            ajaxSubmitForm($("#add-form"));


            //修改页面各部分收放
            $(".head-three").next("div:eq(0)").css("display", "block");
            $(".head-three>a").click(function () {
                $(this).parent().next().slideToggle();
                $(this).prev().toggleClass("icon-caret-right");
                $(this).prev().toggleClass("icon-caret-down");
            });
            //复选框操作
            var $thr = $('table thead tr');
            var $tbr = $('table tbody tr');
            var $checkAllTh = $('<th><input type="checkbox" id="checkAll" name="checkAll" /></th>');
            var $checkAll = $thr.find('input');
            $checkAll.click(function(event){
                /*将所有行的选中状态设成全选框的选中状态*/
                $tbr.find('input').prop('checked',$(this).prop('checked'));
                /*阻止向上冒泡，以防再次触发点击操作*/
                event.stopPropagation();
            });
            /*点击全选框所在单元格时也触发全选框的点击操作*/
            $checkAllTh.click(function(){
                $(this).find('input').click();
            });
            $("#_edits").click(function () {
                $("input,select").prop("disabled",false);
                $("input,select").change(function(){$(this).prop("disabled",false)});
//            $("input").removeAttr("disabled");
//            $("select").removeAttr("disabled");
                $(this).css("display","none");
                $("#_save").removeAttr("style");
                $("#img_dir").removeAttr("style");
                $("._remove").removeAttr("style");
                $("._first").removeAttr("style");
                return false;
            })




            //上传图片自动提交
            $('input[name=uploadfile]').change(function() {
                $("#myform").submit();
                location.reload();
            });
            //"X"号删除图片
            $("._remove").click(function () {
                $(this).parent("li").remove();
            });
            //"v"号设为首图
            $("._first").click(function () {
                var showhtml = $(this).parent()[0].outerHTML;
                $(this).parent().remove();
                $('.firstpic').after(showhtml);
            })

        });
    </script>


    <style>

    </style>























</div>
