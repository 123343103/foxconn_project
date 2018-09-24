<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
\app\assets\JeDateAsset::register($this);
?>
<style>
    .head-second{background-color: #ffffff}
    #tab_bar {  width: 950px;  height: 20px;  float: left;  }
    #tab_bar ul {  padding: 0px;  margin: 0px;  height: 23px;  text-align: center;  }
    #tab_bar li {  list-style-type: none;  float: left;  width: 85px;  height: 23px;  background-color: #1f7ed0;  margin-right: 5px;  line-height: 23px;  cursor: pointer;  color: #ffffff;  }
    .tab_css {  width:100%; /*height: 200px;*/ /*background-color: darkgray;*/  height: auto;  display: none;  float: left;  margin-top: 20px;  }
    ._basic{height:1000px;}
    ._house{height: 370px;width:950px;margin:0 auto;overflow: auto}
    ._addrows{height: 30px;width: 100px;border: 1px solid #00b3ee}
    ._li{float: left;margin-right: 20px;width: 70px;height: 20px;text-align: center}
    #fullbg {  background-color:#333333;  left: 0;  opacity: 0.3;  position: absolute;  top: 0;  z-index: 3;  filter: alpha(opacity=50);  -moz-opacity: 0.5;  -khtml-opacity: 0.5;  }
    #fullbg2 {  background-color:#333333;  left: 0;  opacity: 0.3;  position: absolute;  top: 0;  z-index: 3;  filter: alpha(opacity=50);  -moz-opacity: 0.5;  -khtml-opacity: 0.5;  }
    #dialog {  background-color: #fff; /*border: 5px solid rgba(0,0,0, 0.4);*/  height: 140px;  left: 50%;  margin: -200px 0 0 -200px;  padding: 1px;  position: fixed !important; /* 浮动对话框 */  position: absolute;  top: 50%;  width: 476px;  z-index: 5; /*border-radius: 5px;*/  display: none;  }
    #dialog2 {  background-color: #fff;  border: 2px solid rgba(0,0,0, 0.4);  height: 250px;  left: 60%;  margin: -200px 0 0 -200px;  padding: 1px;  position: fixed !important; /* 浮动对话框 */  position: absolute;  top: 60%;  width: 400px;  z-index: 10; /*border-radius: 5px;*/  display: none;  }
    #dialog3 {  background-color: #fff;  border: 2px solid rgba(0,0,0, 0.4);  height: auto;  left: 65%;  margin: -200px 0 0 -200px;  padding: 1px;  position: fixed !important; /* 浮动对话框 */  position: absolute;  top: 70%;  width: 300px;  z-index: 15; /*border-radius: 5px;*/  display: none;  }
    #dialog p {  margin: 0 0 12px;  height: 24px;  line-height: 24px;  background: #0099FF;  }
    #dialog2 p {  margin: 0 0 12px;  height: 24px;  line-height: 24px;  background: #fff;  }
    #dialog3 p {  margin: 0 0 12px;  height: 24px;  line-height: 24px;  background: #fff;  }
    #dialog p.close {  text-align: right;  padding-right: 10px;  }
    #dialog2 p.close {  text-align: right;  padding-right: 10px;  }
    #dialog3 p.close {  text-align: right;  padding-right: 10px;  }
    #dialog p.close a {  color: #fff;  text-decoration: none;  }
    #dialog2 p.close a {    text-decoration: none;  }
    #dialog3 p.close a {    text-decoration: none;  }
    ._ueditor{margin-left: 70px;}
    .country-picker label:after{
        content:""
    }



    #tab4_content table{
        width:auto !important;
    }
    #tab4_content label{
        text-align: left;
    }
    #tab4_content label input{
        vertical-align: middle;
    }
    #tab4_content label:after{
        content: "";
    }
    #tab4_content tr{
        height: 50px;
        line-height: 50px;
    }

    #tab4_content table,#tab4_content tr,#tab4_content td{
        border: none;
    }

    td.editable input{
        text-align:center;
        border: none;
        outline: none;
        width: 100%;
        height: 30px;
    }
    td.editable input:read-only{
        background:#eee;
    }
    td.editable .validatebox-invalid{
        border:#ffa8a8 1px solid !important;
    }

    .label-width{
        width: 120px;
    }
    .value-width{
        width: 160px;
    }
    .hide{
        display: none;
    }
</style>
<div class="content">
    <div class="_basic">
        <h2 class="head-second mt-20" style="background-color: #d9f0ff;margin-top: 48px">料号：&nbsp;<span id="partno"><?=isset($data['partno']['part_no'])?$data['partno']['part_no']:''?></span>
        </h2>
        <div id="content">
            <div id="tab_bar">
                <ul>
                    <li class="tab_li" id="tab1" onclick="myclick(1)" style="background-color: #1f7ed0">基本信息</li>
                    <li class="tab_li" id="tab3" onclick="myclick(3)">销售价格</li>
                    <li class="tab_li" id="tab4" onclick="myclick(4)">规格参数</li>
                    <li class="tab_li" id="tab5" onclick="myclick(5)">备货期</li>
                    <li class="tab_li" id="tab6" onclick="myclick(6)">发货地</li>
                    <li class="tab_li" id="tab7" onclick="myclick(7)">免运费收货地</li>
                    <li class="tab_li" id="tab8" onclick="myclick(8)">包装信息</li>
                    <?php if($data["pdt"]["isDevice"]){ ?><li class="tab_li" id="tab9" onclick="myclick(9)">设备用途</li><?php } ?>
                </ul>
            </div>
        </div>
        <div class="tab_css" id="tab1_content" style="display: block">
            <div class="mb-10">
                <label class="label-width label-align" for=""><span class="red">*</span>原产地：</label>
                <input class="value-width value-align easyui-validatebox"  data-options="required:true" type="text" name="BsPartno[pdt_origin]" value="<?=isset($data['partno']['pdt_origin'])?$data['partno']['pdt_origin']:''?>"  maxlength="20" >
                <label class="label-width label-align" for="">型号：</label>
                <input class="value-width value-align"  type="text" name="BsPartno[tp_spec]" value="<?=isset($data['partno']['tp_spec'])?$data['partno']['tp_spec']:''?>"  maxlength="100" >
                <label class="label-width label-align" for=""><span class="red">*</span>保质期（月）：</label>
                <input class="value-width value-align easyui-validatebox"  data-options="required:true,validType:'int'" type="text" name="BsPartno[warranty_period]" value="<?=isset($data['partno']['warranty_period'])?$data['partno']['warranty_period']:''?>" maxlength="9">
            </div>
            <div class="mb-10">
                <label class="label-width label-align"  for=""><span class="red">*</span>销售包装数量：</label>
                <input class="value-width value-align easyui-validatebox"  data-options="required:true,validType:['decimal[8,2]']" id="pdt_qty" type="text" name="BsPack[1][pdt_qty]" value="<?=isset($data['pack'][1]['pdt_qty'])?$data['pack'][1]['pdt_qty']:''?>" maxlength="9">
                <label class="label-width label-align" for=""><span class="red">*</span>最小起订量：</label>
                <input class="value-width value-align easyui-validatebox" id="min_order"  data-options="required:true,validType:['decimal[8,2]','minOrder']" type="text" name="BsPartno[min_order]" value="<?=isset($data['partno']['min_order'])?$data['partno']['min_order']:''?>" maxlength="9"  >
                <label class="label-width label-align" for="">供应商：</label>
                <span class="value-width value-align" id="supplier"><?=Html::encode($data["partno"]["spp"])?></span>
            </div>
            <div class="mb-10">
                <label class="label-width label-align" for="">商品定位：</label>
                <?=Html::hiddenInput("BsPartno[cm_pos]",$data["partno"]["cm_pos"])?>
                <span class="value-width value-align">
                    <?php
                    if(isset($data["partno"]["cm_pos"])){
                        switch($data["partno"]["cm_pos"]){
                            case 1:
                                echo "高";
                                break;
                            case 2:
                                echo "中";
                                break;
                            case 3:
                                echo "低";
                                break;
                            default:
                                echo "/";
                                break;
                        }
                    }
                    ?>
                </span>
                <label  class="label-width label-align" for="">L / T（天）：</label>
                <?=Html::hiddenInput("BsPartno[l/t]",$data["partno"]["l/t"])?>
                <span class="value-width value-align"><?=isset($data['partno']['l/t'])?$data['partno']['l/t']:"/"?></span>
                <label class="label-width label-align" for="">法务风险等级：</label>
                <?=Html::hiddenInput("BsPartno[leg_lv]",$data["partno"]["leg_lv"])?>
                <span style="width:200px;">
                    <?php
                    if(isset($data["partno"]["leg_lv"])){
                        switch($data["partno"]["leg_lv"]){
                            case 0:
                                echo "高";
                                break;
                            case 1:
                                echo "中";
                                break;
                            case 2:
                                echo "低";
                                break;
                            default:
                                echo "/";
                                break;
                        }
                    }
                    ?>
                </span>
            </div>
            <div class="mb-10">
                <label class="label-width label-align" for=""><span class="red">*</span>是否可议价：</label>
                <span class="value-width value-align">
                    <?=Html::radio("BsPartno[yn_inquiry]",isset($data["partno"]["yn_inquiry"]) && $data["partno"]["yn_inquiry"]==1,["value"=>1])?> 是&nbsp;&nbsp;<?=Html::radio("BsPartno[yn_inquiry]",isset($data["partno"]["yn_inquiry"]) && $data["partno"]["yn_inquiry"]!=1,["value"=>0])?> 否
                </span>
                <label class="label-width label-align" for=""><span class="red">*</span>是 否 保 税：</label>
                <span class="value-width value-align">
                    <?=Html::radio("BsPartno[yn_tax]",isset($data["partno"]["yn_tax"]) && $data["partno"]["yn_tax"]==1,["value"=>1])?> 是&nbsp;&nbsp;<?=Html::radio("BsPartno[yn_tax]",isset($data["partno"]["yn_tax"]) && $data["partno"]["yn_tax"]!=1,["value"=>0])?> 否
                </span>
                <label class="label-width label-align" for=""><span class="red">*</span>是 否 自 提：</label>
                <span class="value-width value-align">
                    <?=Html::radio("BsPartno[isselftake]",isset($data["partno"]["isselftake"]) && $data["partno"]["isselftake"]==1,["value"=>'1'])?> 是&nbsp;&nbsp;<?=Html::radio("BsPartno[isselftake]",isset($data["partno"]["isselftake"]) && $data["partno"]["isselftake"]==0,["value"=>'0'])?> 否
                </span>
            </div>
            <div class="mb-10">
                <label class="label-width label-align" for="">是 否 代 理：</label>
                <?=Html::hiddenInput("BsPartno[is_agent]",$data["partno"]["is_agent"])?>
                <span class="value-width value-align"><?=$data["partno"]["is_agent"]==1?"是":"否"?></span>
                <label  class="label-width label-align" for="">是否批次管理：</label>
                <?=Html::hiddenInput("BsPartno[is_batch]",$data["partno"]["is_batch"])?>
                <span class="value-width value-align"><?=$data["partno"]["is_batch"]==1?"是":"否"?></span>
                <label class="label-width label-align" for="">是否拳头商品：</label>
                <?=Html::hiddenInput("BsPartno[is_first]",$data["partno"]["is_first"])?>
                <span class="value-width value-align"><?=$data["partno"]["is_first"]==1?"是":"否"?></span>
            </div>
            <div class="mb-20">
                <label style="vertical-align: top" class="label-width label-align" for="">备注：</label>
                <textarea name="BsPartno[marks]" id="" style="width:795px;height:100px;" maxlength="200" placeholder="最多输入200个字"><?=isset($data["partno"]["marks"])?$data["partno"]["marks"]:''?></textarea>
            </div>
            <div class="mb-20" id="_byself" style="display:<?=(isset($data['partno']['isselftake']) && $data['partno']['isselftake'])?"block":"none"?>">
                <label class="width-80" for="">自提仓库</label>
                <div class="_house">
                    <table class="table">
                        <thead>
                        <tr>
                            <th width="100"><input id="house_all" type="checkbox"></th>
                            <th width="150">仓库名</th>
                            <th width="150">仓库地址</th>
                            <th width="150">联系人</th>
                            <th width="150">联系电话</th>
                        </tr>
                        </thead>
                        <tbody id="product_table">
                        <?php if(count($data["wh"])>0){ ?>
                            <?php foreach($data["wh"] as $k=>$wh){ ?>
                                <tr>
                                    <td><input type="checkbox" name="RPrtWh[wh_id][]" value="<?=$wh['wh_id']?>" <?=$wh['selected']?'checked':''?>></td>
                                    <td><?=$wh["wh_name"]?></td>
                                    <td><?=$wh["wh_address"]?></td>
                                    <td><?=$wh["staff_name"]?></td>
                                    <td><?=$wh["staff_mobile"]?></td>
                                </tr>
                            <?php } ?>
                        <?php }else{ ?>
                            <tr>
                                <td colspan="5">没有相关数据！</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-10"></div>

            <div class="text-center">
                <button class="button-blue-big save" type="submit">保存</button>
                <?php if($type==1) {
                    if($status=="notupshelf"){
                        echo Html::button("提交", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                    }
                    if($status=="downshelf"){
                        echo Html::button("重新上架", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                    }
                }
                ?>
                <?php if($type==2) {
                    echo Html::button("提交", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                    echo Html::button("返回", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "button", "onclick" => "window.history.go(-1)"]);
                }
                ?>
            </div>

        </div>
        <div class="tab_css" id="tab3_content">
            <p style="color: red;margin-bottom:10px;">提示：销售价格的“最小值”等于基本信息的“最小起订量”！</p>
            <table class="table">
                <thead>
                <tr>
                    <th>最小值</th>
                    <th>最大值</th>
                    <th>价格</th>
                    <th>币别</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody id="product_table" class="price-box">
                <?php if(count($data["price"])>0){ ?>
                <?php foreach ($data["price"] as $k=>$price){ ?>
                <tr>
                    <td class="editable" width="200">
                        <?php if($k==0){?>
                        <input class="minqty easyui-validatebox" data-options="validType:['decimal[19,2]','minqty']" name="BsPrice[minqty][]" type="text" value="<?=$price['minqty']?>" maxlength="11" readonly>
                        <?php }else{ ?>
                            <input class="minqty"  name="BsPrice[minqty][]" type="text" value="<?=$price['minqty']?>" maxlength="11" readonly>
                        <?php } ?>
                    </td>
                    <td class="editable" width="200">
                        <?php if($k+1==count($data["price"])){ ?>
                            <input class="maxqty easyui-validatebox" data-options="validType:['decimal[19,2]','maxqty']" name="BsPrice[maxqty][]" type="text" value="<?=$price['maxqty']?>" maxlength="11" readonly>
                        <?php }else{ ?>
                            <input class="maxqty easyui-validatebox" data-options="validType:['decimal[19,2]','maxqty']" name="BsPrice[maxqty][]" type="text" value="<?=$price['maxqty']?>" maxlength="11">
                        <?php } ?>
                    </td>
                    <td class="editable" width="200">
                        <input class="easyui-validatebox" data-options="required:true,validType:'price'" name="BsPrice[price][]" type="text" value="<?=$price['price']?>" maxlength="20">
                    </td>
                    <td class="editable" width="200">
                        <?=Html::dropDownList('BsPrice[currency][]',$price['currency_id'],$options['currency'],['style'=>'width:120px;'])?>
                    </td>
                    <td>
                        <?php if($k>0){ ?>
                        <a class="remove" style="display: none;">删除</a>
                        <?php } ?>
                    </td>
                </tr>
                    <?php } ?>
                <?php }else{ ?>
                    <tr>
                        <td class="editable" width="200">
                            <input class="minqty easyui-validatebox" data-options="validType:['decimal[19,2]','minqty']" name="BsPrice[minqty][]" type="text" value="<?=$data['partno']['min_order']?>" maxlength="11" readonly>
                        </td>
                        <td class="editable" width="200">
                            <input class="maxqty easyui-validatebox" data-options="validType:['decimal[19,2]','maxqty']" name="BsPrice[maxqty][]" type="text" value="<?=$price['maxqty']?>" maxlength="11">
                        </td>
                        <td class="editable" width="200">
                            <input class="easyui-validatebox" data-options="required:true,validType:'price'" name="BsPrice[price][]" type="text" value="<?=$price['price']?>" maxlength="20">
                        </td>
                        <td class="editable" width="200">
                            <?=Html::dropDownList('BsPrice[currency][]',$price['currency_id'],$options['currency'],['style'=>'width:120px;'])?>
                        </td>
                        <td>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <div class="mb-20" style="margin-top: 20px;">
                <input type="button" class="_addrows" id="_addprice" value="+添加区间">
            </div>



            <div class="space-10"></div>

            <div class="text-center">
                <button class="button-blue-big save" type="submit">保存</button>
                <?php if($type==1) {
                    if($status=="notupshelf"){
                        echo Html::button("提交", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                    }
                    if($status=="downshelf"){
                        echo Html::button("重新上架", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                    }
                }
                ?>
                <?php if($type==2) {
                    echo Html::button("提交", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                    echo Html::button("返回", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "button", "onclick" => "window.history.go(-1)"]);
                }
                ?>
            </div>

        </div>
        <div class="tab_css" id="tab4_content">
            <?php
            $html=Html::beginTag("table",["id"=>"attr-box"]);
            foreach ($data["attrs"] as $k=>$attr){
                if($attr["isrequired"]){
                    $html.=Html::beginTag("tr",["class"=>"req"]);
                }else{
                    $html.=Html::beginTag("tr");
                }
                $html.=Html::beginTag("td",["class"=>"label-width label-align","style"=>"white-space: nowrap;"]);
                $attr["isrequired"] && $html.="<span class='red'>*&nbsp;</span>";
                $html.=$attr["name"]."：";
                $html.=Html::endTag("td");
                $html.=Html::hiddenInput("RPrtAttr[$k][attr_name]",$attr["name"]);
                $html.=Html::hiddenInput("RPrtAttr[$k][attr_type]",$attr["type"]);
                $html.=Html::beginTag("td",["style"=>"word-wrap: break-word;word-break: break-all;"]);
                switch($attr["type"]){
                    case 0:
                        $html.=Html::hiddenInput("RPrtAttr[$k][attr_value]","");
                        $html.=Html::checkboxList("RPrtAttr[$k][attr_val_id]",$attr['sel'],$attr["items"],['encode'=>false]);
                        break;
                    case 1:
                        $html.=Html::hiddenInput("RPrtAttr[$k][attr_value]","");
                        $html.=Html::radioList("RPrtAttr[$k][attr_val_id]",$attr['sel'],$attr["items"],['encode'=>false]);
                        break;
                    case 2:
                        $html.=Html::hiddenInput("RPrtAttr[$k][attr_value]","");
                        $html.=Html::dropDownList("RPrtAttr[$k][attr_val_id]",$attr['sel'],$attr["items"],["class"=>"value-width value-align","prompt"=>"请选择",'encode'=>false]);
                        break;
                    case 3:
                        $html.=Html::hiddenInput("RPrtAttr[$k][attr_val_id]","");
                        $html.=Html::input("text","RPrtAttr[$k][attr_value]",$attr['val'],["class"=>"value-width value-align","maxlength"=>100]);
                        break;
                }
                $html.=Html::endTag("td");
                $html.=Html::endTag("tr");
            }
            $html.=Html::endTag("table");
            echo $html;
            ?>

            <div class="space-10"></div>

            <div class="text-center">
                <button class="button-blue-big save" type="submit">保存</button>
                <?php if($type==1) {
                    if($status=="notupshelf"){
                        echo Html::button("提交", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                    }
                    if($status=="downshelf"){
                        echo Html::button("重新上架", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                    }
                }
                ?>
                <?php if($type==2) {
                    echo Html::button("提交", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                    echo Html::button("返回", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "button", "onclick" => "window.history.go(-1)"]);
                }
                ?>
            </div>

        </div>
        <div class="tab_css" id="tab5_content">
            <p style="color: red;margin-bottom:10px;">提示：备货期的“最小值”等于基本信息的“最小起订量”！</p>
            <table class="table">
                <thead>
                <tr>
                    <th>最小值</th>
                    <th>最大值</th>
                    <th>备货时间（天）</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody id="product_table1" class="stock-box">
                <?php if(count($data["stock"])>0){ ?>
                    <?php foreach($data["stock"] as $k=>$stock){ ?>
                        <tr>
                            <?php if($k==0){ ?>
                                <td class="editable" width="200"><input type="text" class="easyui-validatebox minqty" data-options="required:true,validType:['decimal[11,2]','minqty']" name="BsStock[min_qty][]" value="<?=$stock["min_qty"]?>" maxlength="11" readonly></td>
                            <?php }else{ ?>
                                <td class="editable" width="200"><input type="text" class="easyui-validatebox minqty" data-options="required:true,validType:'decimal[11,2]'" name="BsStock[min_qty][]" value="<?=$stock["min_qty"]?>" maxlength="11" readonly></td>
                            <?php } ?>
                            <?php if($k+1==count($data["stock"])){ ?>
                                <td class="editable" width="200"><input type="text" class="easyui-validatebox maxqty" data-options="validType:['decimal[11,2]','maxqty']" name="BsStock[max_qty][]" value="<?=$stock["max_qty"]?>" maxlength="11" readonly></td>
                            <?php }else{ ?>
                                <td class="editable" width="200"><input type="text" class="easyui-validatebox maxqty" data-options="required:true,validType:['decimal[11,2]','maxqty']" name="BsStock[max_qty][]" value="<?=$stock["max_qty"]?>" maxlength="11"></td>
                            <?php } ?>
                            <td class="editable" width="200"><input type="text" class="easyui-validatebox" data-options="required:true,validType:'int'" name="BsStock[stock_time][]" value="<?=$stock["stock_time"]?>" maxlength="9"></td>
                            <td>
                                <?php if($k>0){ ?>
                                    <a class="remove">删除</a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php }else{ ?>
                    <tr>
                        <td class="editable" width="200"><input type="text" class="easyui-validatebox minqty" data-options="required:true,validType:['decimal[11,2]','minqty']" name="BsStock[min_qty][]"  maxlength="11" value="<?=$data['partno']['min_order']?>" readonly></td>
                        <td class="editable" width="200"><input type="text" class="easyui-validatebox maxqty" data-options="validType:['decimal[11,2]','maxqty']" name="BsStock[max_qty][]"  maxlength="11" readonly></td>
                        <td class="editable" width="200"><input type="text" class="easyui-validatebox" data-options="required:true,validType:'int'" name="BsStock[stock_time][]"  maxlength="9"></td>
                        <td></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <div class="mb-20" style="margin-top: 20px;">
                <input type="button" class="_addrows" id="_addrow" value="+添加区间">
            </div>

            <div class="space-10"></div>

            <div class="text-center">
                <button class="button-blue-big save" type="submit">保存</button>
                <?php if($type==1) {
                    if($status=="notupshelf"){
                        echo Html::button("提交", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                    }
                    if($status=="downshelf"){
                        echo Html::button("重新上架", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                    }
                }
                ?>
                <?php if($type==2) {
                    echo Html::button("提交", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                    echo Html::button("返回", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "button", "onclick" => "window.history.go(-1)"]);
                }
                ?>
            </div>

        </div>
        <div class="tab_css" id="tab6_content">
            <table class="table">
                <thead>
                <tr>
                    <th width="100">序号</th>
                    <th width="200">国别</th>
                    <th width="200">省分</th>
                    <th width="200">城市</th>
                    <th width="200">操作</th>
                </tr>
                </thead>
                <tbody class="product_table_addr ship-box">
                <?php if(count($data["ship"])>0){ ?>
                    <?php foreach ($data["ship"] as $k=>$ship){ ?>
                        <tr>
                            <?=Html::hiddenInput("BsShip[country_no][]",$ship['country_no'],['class'=>'country_no'])?>
                            <?=Html::hiddenInput("BsShip[province_no][]",$ship['province_no'],['class'=>'province_no'])?>
                            <?=Html::hiddenInput("BsShip[city_no][]",$ship['city_no'],['class'=>'city_no'])?>
                            <?=Html::hiddenInput("BsShip[country_name][]",$ship['country_name'],['class'=>'country_name'])?>
                            <?=Html::hiddenInput("BsShip[province_name][]",$ship['province_name'],['class'=>'province_name'])?>
                            <?=Html::hiddenInput("BsShip[city_name][]",$ship['city_name'],['class'=>'city_name'])?>
                            <td><?=$k+1?></td>
                            <td><?=$ship["country_name"]?></td>
                            <td><?=$ship["province_name"]?></td>
                            <td><?=$ship["city_name"]?></td>
                            <td><a class="remove">删除</a></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
                <tfoot style="display: <?=count($data["ship"])>0?'none':'table-footer-group'?>">
                <tr>
                    <td colspan="5">没有相关数据！</td>
                </tr>
                </tfoot>
            </table>
            <div class="mb-20" style="margin-top: 20px;">
                <input type="button" class="_addrows ship-add" id="_addcountry" value="+添加发货地">
            </div>

            <div class="space-10"></div>

            <div class="text-center">
                <button class="button-blue-big save" type="submit">保存</button>
                <?php if($type==1) {
                    if($status=="notupshelf"){
                        echo Html::button("提交", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                    }
                    if($status=="downshelf"){
                        echo Html::button("重新上架", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                    }
                }
                ?>
                <?php if($type==2) {
                    echo Html::button("提交", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                    echo Html::button("返回", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "button", "onclick" => "window.history.go(-1)"]);
                }
                ?>
            </div>

        </div>
        <div class="tab_css" id="tab7_content">
            <div class="mb-20">
                <label class="width-10" for=""><span class="red">*</span></label>
                <?=Html::radio("BsPartno[yn_free_delivery]",$data["partno"]["yn_free_delivery"]==0,['class'=>'city_all','value'=>0])?>&nbsp;全国免运费&nbsp;
                <?=Html::radio("BsPartno[yn_free_delivery]",$data["partno"]["yn_free_delivery"]==1,['class'=>'city_part','value'=>1])?>&nbsp;全国部分城市免运费&nbsp;
                <?=Html::radio("BsPartno[yn_free_delivery]",$data["partno"]["yn_free_delivery"]==2,['class'=>'city_all','value'=>2])?>&nbsp;全国不免运费&nbsp;
                <?=Html::radio("BsPartno[yn_free_delivery]",$data["partno"]["yn_free_delivery"]==3,['class'=>'city_part','value'=>3])?>&nbsp;全国部分城市不免运费&nbsp;
            </div>
            <div class="mb-20">
                <?php if($data["partno"]["yn_free_delivery"]==0 || $data["partno"]["yn_free_delivery"]==2){ ?>
                <div class="mb-10" id="_addadress" style="display:none;">
                <?php }else{ ?>
                <div class="mb-10" id="_addadress">
                <?php } ?>
                    <table class="table" >
                        <thead>
                        <tr>
                            <th>序号</th>
                            <th>国别</th>
                            <th>省分</th>
                            <th>城市</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody class="product_table_addr2 deliv-box">
                        <?php if(count($data["deliv"])>0){ ?>
                            <?php foreach ($data["deliv"] as $k=>$deliv){ ?>
                                <tr>
                                    <?=Html::hiddenInput("BsDeliv[country_no][]",$deliv["country_no"],['class'=>'country_no'])?>
                                    <?=Html::hiddenInput("BsDeliv[province_no][]",$deliv["province_no"],['class'=>'province_no'])?>
                                    <?=Html::hiddenInput("BsDeliv[city_no][]",$deliv["city_no"],['class'=>'city_no'])?>
                                    <?=Html::hiddenInput("BsDeliv[country_name][]",$deliv["country_name"],['class'=>'country_name'])?>
                                    <?=Html::hiddenInput("BsDeliv[province_name][]",$deliv["province_name"],['class'=>'province_name'])?>
                                    <?=Html::hiddenInput("BsDeliv[city_name][]",$deliv["city_name"],['class'=>'city_name'])?>
                                    <td><?=$k+1?></td>
                                    <td><?=$deliv["country_name"]?></td>
                                    <td><?=$deliv["province_name"]?></td>
                                    <td><?=$deliv["city_name"]?></td>
                                    <td><a class="remove">删除</a></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                        <tfoot style="display: <?=count($data["deliv"])>0?'none':'table-footer-group'?>">
                        <tr>
                            <td colspan="5">没有相关数据！</td>
                        </tr>
                        </tfoot>
                    </table>
                    <div class="mb-20" style="margin-top: 20px;">
                        <input type="button" class="_addrows deliv-add" id="_addcountry2" value="+添加地址">
                    </div>
                </div>
            </div>

            <div class="space-10"></div>

                <div class="text-center">
                    <button class="button-blue-big save" type="submit">保存</button>
                    <?php if($type==1) {
                        if($status=="notupshelf"){
                            echo Html::button("提交", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                        }
                        if($status=="downshelf"){
                            echo Html::button("重新上架", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                        }
                    }
                    ?>
                    <?php if($type==2) {
                        echo Html::button("提交", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                        echo Html::button("返回", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "button", "onclick" => "window.history.go(-1)"]);
                    }
                    ?>
                </div>

        </div>
        <div class="tab_css" id="tab8_content">
            <div class="mb-20"style="margin-bottom: 10px;">
                <span style="color: #0000FF;font-size:14px">基本包装信息</span>
            </div>
            <div class="mb-20">
                <table class="table">
                    <thead>
                    <tr>
                        <th width="100">序号</th>
                        <th width="150">商品长</th>
                        <th width="150">商品宽</th>
                        <th width="150">商品高</th>
                        <th width="150">商品重</th>
                        <th width="150">使用富金机包装</th>
                        <th width="150">使用线板</th>
                        <th width="100">操作</th>

                    </tr>
                    </thead>
                    <tbody id="product_table" class="pack-box">
                    <tr>
                        <td>1</td>
                        <td class="editable"><input name="BsPack[0][pdt_length]" type="text" value="<?=$data["pack"][0]["pdt_length"]?>" class="easyui-validatebox" data-options="validType:'decimal[10,2]'" maxlength="11"></td>
                        <td class="editable"><input name="BsPack[0][pdt_width]" type="text" value="<?=$data["pack"][0]["pdt_width"]?>" class="easyui-validatebox" data-options="validType:'decimal[10,2]'" maxlength="11"></td>
                        <td class="editable"><input name="BsPack[0][pdt_height]" type="text" value="<?=$data["pack"][0]["pdt_height"]?>" class="easyui-validatebox" data-options="validType:'decimal[10,2]'" maxlength="11"></td>
                        <td class="editable"><input name="BsPack[0][pdt_weight]" class="easyui-validatebox" data-options="validType:'decimal[19,2]'" type="text" value="<?=$data["pack"][0]["pdt_weight"]?>"  maxlength="20"></td>
                        <?=Html::hiddenInput("BsPack[0][pck_type]",1)?>
                        <?=Html::hiddenInput("BsPack[0][pdt_mater]","")?>
                        <?=Html::hiddenInput("BsPack[0][pdt_qty]","")?>
                        <td><input name="BsPartno[yn_pa_fjj]" value="1" type="checkbox" <?=$data['partno']['yn_pa_fjj']?'checked':''?> ></td>
                        <td><input name="BsPartno[yn_pallet]" value="1" type="checkbox" <?=$data['partno']['yn_pallet']?'checked':''?> ></td>
                        <td><a class="reset">重置</a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mb-20"style="margin-bottom: 10px;">
                <span style="color: #0000FF;font-size:14px">销售包装（内包装）</span>
            </div>
            <div class="mb-20">
                <table class="table">
                    <thead>
                    <tr>
                        <th width="100">序号</th>
                        <th width="150">包装长</th>
                        <th width="150">包装宽</th>
                        <th width="150">包装高</th>
                        <th width="150"><span class="red">*</span>包装毛重</th>
                        <th width="150"><span class="red">*</span>净重</th>
                        <th width="150"><span class="red">*</span>包材名称</th>
                        <th width="150">销售包装内商品数量</th>
                        <th width="100">操作</th>
                    </tr>
                    </thead>
                    <tbody id="product_table" class="pack-box">
                    <tr>
                        <td>2</td>
                        <td class="editable"><input name="BsPack[1][pdt_length]" type="text" value="<?=$data["pack"][1]["pdt_length"]?>" class="easyui-validatebox" data-options="validType:'decimal[10,2]'"  maxlength="11"></td>
                        <td class="editable"><input name="BsPack[1][pdt_width]" type="text" value="<?=$data["pack"][1]["pdt_width"]?>" class="easyui-validatebox" data-options="validType:'decimal[10,2]'"  maxlength="11"></td>
                        <td class="editable"><input name="BsPack[1][pdt_height]" type="text" value="<?=$data["pack"][1]["pdt_height"]?>" class="easyui-validatebox" data-options="validType:'decimal[10,2]'"  maxlength="11"></td>
                        <td class="editable"><input name="BsPack[1][pdt_weight]" class="easyui-validatebox" data-options="validType:'decimal[19,2]',required:true" type="text" value="<?=$data["pack"][1]["pdt_weight"]?>"  maxlength="20" placeholder="必填"></td>
                        <td class="editable"><input name="BsPack[1][net_weight]" class="easyui-validatebox" data-options="validType:'decimal[10,2]',required:true" type="text" value="<?=$data["pack"][1]["net_weight"]?>"  maxlength="11" placeholder="必填"></td>
                        <td class="editable"><input name="BsPack[1][pdt_mater]" class="easyui-validatebox" data-options="required:true" type="text" value="<?=$data["pack"][1]["pdt_mater"]?>" maxlength="100" placeholder="必填"></td>
                        <td><input type="text" id="pack_num" value="<?=isset($data['pack'][1]['pdt_qty'])?$data['pack'][1]['pdt_qty']:''?>" readonly style="text-align:center;height: 30px;border:none;"></td>
                        <?=Html::hiddenInput("BsPack[1][pck_type]",2)?>
                        <td><a class="reset">重置</a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mb-20"style="margin-bottom: 10px;">
                <span style="color: #0000FF;font-size:14px">外包装</span>
            </div>
            <div class="mb-20">
                <table class="table">
                    <thead>
                    <tr>
                        <th width="100">序号</th>
                        <th width="150">包装长</th>
                        <th width="150">包装宽</th>
                        <th width="150">包装高</th>
                        <th width="150">包装毛重</th>
                        <th width="150">包材名称</th>
                        <th width="150">外包装数量</th>
                        <th width="100">操作</th>
                    </tr>
                    </thead>
                    <tbody id="product_table" class="pack-box">
                    <tr>
                        <td>3</td>
                        <td class="editable"><input name="BsPack[2][pdt_length]" type="text"  value="<?=$data["pack"][2]["pdt_length"]?>" class="easyui-validatebox" data-options="validType:'decimal[10,2]'"  maxlength="11"></td>
                        <td class="editable"><input name="BsPack[2][pdt_width]" type="text"  value="<?=$data["pack"][2]["pdt_width"]?>" class="easyui-validatebox" data-options="validType:'decimal[10,2]'"  maxlength="11"></td>
                        <td class="editable"><input name="BsPack[2][pdt_height]" type="text"  value="<?=$data["pack"][2]["pdt_height"]?>" class="easyui-validatebox" data-options="validType:'decimal[10,2]'"  maxlength="11"></td>
                        <td class="editable"><input name="BsPack[2][pdt_weight]" class="easyui-validatebox" data-options="validType:'decimal[19,2]'" type="text" value="<?=$data["pack"][2]["pdt_weight"]?>"  maxlength="20"></td>
                        <td class="editable"><input name="BsPack[2][pdt_mater]" type="text"  value="<?=$data["pack"][2]["pdt_mater"]?>" maxlength="100"></td>
                        <td class="editable"><input name="BsPack[2][pdt_qty]" type="text"  value="<?=$data["pack"][2]["pdt_qty"]?>" class="easyui-validatebox" data-options="validType:'decimal[8,2]'" maxlength="9"></td>
                        <?=Html::hiddenInput("BsPack[2][pck_type]",3)?>
                        <td><a class="reset">重置</a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mb-20"style="margin-bottom: 10px;">
                <span style="color: #0000FF;font-size:14px">散货包装</span>
            </div>
            <div class="mb-20">
                <table class="table">
                    <thead>
                    <tr>
                        <th width="100">序号</th>
                        <th width="180">包装长</th>
                        <th width="180">包装宽</th>
                        <th width="180">包装高</th>
                        <th width="180">包装毛重</th>
                        <th width="180">包材名称</th>
                        <th width="180">散货包装数量</th>
                        <th width="100">操作</th>
                    </tr>
                    </thead>
                    <tbody id="product_table" class="pack-box">
                    <tr>
                        <td>4</td>
                        <td class="editable"><input name="BsPack[3][pdt_length]" type="text" value="<?=$data["pack"][3]["pdt_length"]?>" class="easyui-validatebox" data-options="validType:'decimal[10,2]'"  maxlength="11"></td>
                        <td class="editable"><input name="BsPack[3][pdt_width]" type="text" value="<?=$data["pack"][3]["pdt_width"]?>" class="easyui-validatebox" data-options="validType:'decimal[10,2]'"  maxlength="11"></td>
                        <td class="editable"><input name="BsPack[3][pdt_height]" type="text" value="<?=$data["pack"][3]["pdt_height"]?>" class="easyui-validatebox" data-options="validType:'decimal[10,2]'"  maxlength="11"></td>
                        <td class="editable"><input name="BsPack[3][pdt_weight]" class="easyui-validatebox" data-options="validType:'decimal[19,2]'" type="text" value="<?=$data["pack"][3]["pdt_weight"]?>"  maxlength="20"></td>
                        <td class="editable"><input name="BsPack[3][pdt_mater]" type="text" value="<?=$data["pack"][3]["pdt_mater"]?>" maxlength="100"></td>
                        <td class="editable"><input name="BsPack[3][pdt_qty]" type="text" value="<?=$data["pack"][3]["pdt_qty"]?>" class="easyui-validatebox" data-options="validType:'decimal[8,2]'" maxlength="9"></td>
                        <?=Html::hiddenInput("BsPack[3][pck_type]",4)?>
                        <td><a class="reset">重置</a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mb-20"style="margin-bottom: 10px;">
                <span style="color: #0000FF;font-size:14px">板线包装</span>
            </div>
            <div class="mb-20">
                <table class="table">
                    <thead>
                    <tr>
                        <th width="100">序号</th>
                        <th width="150">包装长</th>
                        <th width="150">包装宽</th>
                        <th width="150">包装高</th>
                        <th width="150">包装毛重</th>
                        <th width="150">板线数量</th>
                        <th width="150">包装件数量</th>
                        <th width="100">操作</th>
                    </tr>
                    </thead>
                    <tbody id="product_table" class="pack-box">
                    <tr>
                        <td>5</td>
                        <td class="editable"><input name="BsPack[4][pdt_length]" type="text" value="<?=$data["pack"][4]["pdt_length"]?>" class="easyui-validatebox" data-options="validType:'decimal[10,2]'"  maxlength="11"></td>
                        <td class="editable"><input name="BsPack[4][pdt_width]" type="text" value="<?=$data["pack"][4]["pdt_width"]?>" class="easyui-validatebox" data-options="validType:'decimal[10,2]'"  maxlength="11"></td>
                        <td class="editable"><input name="BsPack[4][pdt_height]" type="text" value="<?=$data["pack"][4]["pdt_height"]?>" class="easyui-validatebox" data-options="validType:'decimal[10,2]'"  maxlength="11"></td>
                        <td class="editable"><input name="BsPack[4][pdt_weight]" class="easyui-validatebox" data-options="validType:'decimal[19,2]'" type="text" value="<?=$data["pack"][4]["pdt_weight"]?>"  maxlength="20"></td>
                        <td class="editable"><input name="BsPack[4][plate_num]" type="text" value="<?=$data["pack"][4]["plate_num"]?>" class="easyui-validatebox" data-options="validType:'int'" maxlength="9"></td>
                        <td class="editable"><input name="BsPack[4][pdt_qty]" type="text" value="<?=$data["pack"][4]["pdt_qty"]?>" class="easyui-validatebox" data-options="validType:'decimal[8,2]'" maxlength="9"></td>
                        <?=Html::hiddenInput("BsPack[4][pck_type]",5)?>
                        <td><a class="reset">重置</a></td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="space-10"></div>

            <div class="text-center">
                <button class="button-blue-big save" type="submit">保存</button>
                <?php if($type==1) {
                    if($status=="notupshelf"){
                        echo Html::button("提交", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                    }
                    if($status=="downshelf"){
                        echo Html::button("重新上架", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                    }
                }
                ?>
                <?php if($type==2) {
                    echo Html::button("提交", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                    echo Html::button("返回", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "button", "onclick" => "window.history.go(-1)"]);
                }
                ?>
            </div>

        </div>

            <?php if($data["pdt"]["isDevice"]){ ?>
            <div class="tab_css" id="tab9_content" >
            <div class="mb-20 warr-tab">
                <label class="label-width label-align"><span class="red">*</span>延保方案</label>
                <?=Html::radio("BsPartno[machine_type]",$data["partno"]["machine_type"]==0,['value'=>'0'])?>新机 &nbsp;&nbsp;
                <?=Html::radio("BsPartno[machine_type]",$data["partno"]["machine_type"]==1,['value'=>'1'])?>二手设备 &nbsp;&nbsp;
                <?=Html::radio("BsPartno[machine_type]",$data["partno"]["machine_type"]==2,['value'=>'2'])?>设备租赁 &nbsp;&nbsp;
            </div>
            <div class="warr-tab-content">
                <?php if($data["partno"]["machine_type"]==0){ ?>
                    <div class="warr-tab-item">
                        <div class="mb-10">
                            <label class="label-width label-align">出厂年限：</label>
                            <input type="text" class="value-width value-align Wdate" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy',maxDate:'%y-%M-%d' })"  name="BsMachine[out_year]" value="<?=$data['machine']['out_year']?>" readonly>
                        </div>
                    </div>
                <?php } ?>

                <?php if($data["partno"]["machine_type"]==1){ ?>
                    <div class="warr-tab-item">
                        <div class="mb-10">
                            <label class="label-width label-align">出厂年限：</label>
                            <input type="text" class="value-width value-align Wdate" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy',maxDate:'%y-%M-%d' })"  name="BsMachine[out_year]" value="<?=$data['machine']['out_year']?>" readonly>
                        </div>
                        <div class="mb-10">
                            <label class="label-width label-align">库存：</label>
                            <input type="text" class="value-width value-align easyui-validatebox" data-options="validType:'int'" name="BsMachine[stock_num]" value="<?=$data['machine']['stock_num']?>" maxlength="9" >
                        </div>
                        <div class="mb-10">
                            <label class="label-width label-align">新旧程度：</label>
                            <input type="text" class="value-width value-align" name="BsMachine[recency]" value="<?=$data['machine']['recency']?>"  maxlength="50">
                        </div>
                    </div>
                <?php } ?>

                <?php if($data["partno"]["machine_type"]==2){ ?>
                    <div class="warr-tab-item">
                        <div class="mb-10">
                            <label class="label-width label-align">出厂年限：</label>
                            <input type="text" class="value-width value-align Wdate" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy',maxDate:'%y-%M-%d' })"  name="BsMachine[out_year]" value="<?=$data['machine']['out_year']?>" readonly>
                        </div>
                        <div class="mb-10">
                            <label class="label-width label-align">租期：</label>
                            <input type="text" class="value-width value-align easyui-validatebox" data-options="validType:'int'"  name="BsMachine[rentals]" value="<?=$data['machine']['rentals']?>" maxlength="9">&nbsp;月
                        </div>
                        <div class="mb-10">
                            <label class="label-width label-align">租金：</label>
                            <input type="text" class="value-width value-align easyui-validatebox" data-options="validType:'int'"  name="BsMachine[rental_unit]" value="<?=$data['machine']['rental_unit']?>" maxlength="9">&nbsp;RMB/月
                        </div>
                        <div class="mb-10">
                            <label class="label-width label-align">押金：</label>
                            <input type="text" class="value-width value-align easyui-validatebox" data-options="validType:'int'"  name="BsMachine[deposit]" value="<?=$data['machine']['deposit']?>" maxlength="9">&nbsp;RMB
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="mb-20" style="margin-bottom: 10px;">
                <span style="font-size:14px;margin-left: 11px ">延保方案</span>
            </div>
            <div class="mb-20">
                <table class="table">
                    <thead>
                    <tr>
                        <th width="100">序号</th>
                        <th width="200">延保时间(月)</th>
                        <th width="200">延保费用(RMB)</th>
                        <th width="100">操作</th>
                    </tr>
                    </thead>
                    <tbody class="warr-box">
                    <?php if(count($data["warr"])>0){ ?>
                        <?php foreach ($data["warr"] as $k=>$warr){ ?>
                            <tr>
                                <td><?=$k+1?></td>
                                <td class="editable"><input name="BsWarr[wrr_prd][]" class="easyui-validatebox" data-options="validType:'int'" type="text" value="<?=$warr["wrr_prd"]?>" maxlength="9" ></td>
                                <td class="editable"><input name="BsWarr[wrr_fee][]" class="easyui-validatebox" data-options="validType:'decimal[10,2]'" type="text" value="<?=$warr["wrr_fee"]?>" maxlength="11" ></td>
                                <td><a class="remove">删除</a></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                    <tfoot style="display:<?=count($data["warr"])>0?'none':'table-footer-group' ?>;">
                    <tr>
                        <td colspan="4">没有相关数据</td>
                    </tr>
                    </tfoot>
                </table>

            </div>
            <div class="space-10"></div>
            <div class="mb-20">
                <button style="width:120px;height: 30px;line-height: 30px;" type="button"  class="add-warr button-blue">+延保方案</button>
            </div>
            <div class="space-10"></div>
            <label class="width-70" style="float: left">设备信息：</label>
            <div id="editor" style="margin-left: 70px;"></div>
            <div id="editor_content" style="display: none;"><?=Html::decode($data["partno"]["details"])?></div>

            <div class="space-10"></div>

                <div class="text-center">
                    <button class="button-blue-big save" type="submit">保存</button>
                    <?php if($type==1) {
                        if($status=="notupshelf"){
                            echo Html::button("提交", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                        }
                        if($status=="downshelf"){
                            echo Html::button("重新上架", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                        }
                    }
                    ?>
                    <?php if($type==2) {
                        echo Html::button("提交", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "submit"]);
                        echo Html::button("返回", ["style" => "margin-left:40px;", "class" => "button-blue-big sub", "type" => "button", "onclick" => "window.history.go(-1)"]);
                    }
                    ?>
                </div>
        </div>
            <?php } ?>
    </div>
        <style type="text/css">
            .province-picker .selected{
                color:lightseagreen;
            }
        </style>
    <script>
        $.fn.district_picker=function(a,b){
            var methods={
                init:function(){
                    var t=$(this);
                    function update(){
                        var data=[];
                        var result=[];

                        $(".province-list li").each(function(){
                            var id=$(this).data("id");
                            var rows=$(this).data("rows");
                            if($.isArray(rows)){

                                $(this).addClass("selected");
                                data[id]=rows;
                                rows.forEach(function(row,index){
                                    result.push(row);
                                });

                                if(rows.filter(function(row){return row;}).length>0){
                                    $(this).addClass("selected");
                                }else{
                                    $(this).removeClass("selected");
                                }
                            }else{
                                $(this).removeClass("selected");
                            }
                        });

                        if($(".city-list li.selected").size()==$(".city-list li").size()){
                            $(".city-all").addClass("all");
                        }else{
                            $(".city-all").removeClass("all");
                        }


                        t.data("rows",data);
                        return b.apply(t,[result]);
                    }
                    function regenerate(){
                        $("body").find(".district-layer").remove();
                        $("body").find(".province-picker").remove();
                        var district_layer=$("<div class='district-layer'></div>").css({
                            "position":"fixed",
                            "left":"0px",
                            "right":"0px",
                            "top":"0px",
                            "bottom":"0px",
                            "background":"#000",
                            "opacity":"0.5",
                            "z-index":100
                        }).hide();



                        var province_picker_title=$("<div class='title'></div>").css({
                            "position":"absolute",
                            "width":"380px",
                            "height":"30px",
                            "line-height":"30px",
                            "text-indent":"10px",
                            "background":"#1f7ed0",
                            "text-overflow":"ellipsis",
                            "overflow":"hidden",
                            "color":"#fff"
                        }).text("省份选择");

                        var province_picker_close=$("<span class='close'></span>").css({
                            "position":"absolute",
                            "width":"20px",
                            "display":"block",
                            "text-align":"center",
                            "height":"30px",
                            "right":"0px",
                            "line-height":"30px",
                            "background":"#1f7ed0",
                            "color":"#fff",
                            "cursor":"pointer"
                        }).html("&times").click(function(){
                            $(this).parents(".province-picker").hide();
                            $(".district-layer").hide();
                        });

                        var province_list=$("<ul class='province-list'></ul>").css({
                            "position":"absolute",
                            "left":"0px",
                            "right":"0px",
                            "top":"30px",
                            "bottom":"0px",
                            "display":"block",
                            "line-height":"30px"
                        });

                        var province_picker=$("<div class='province-picker'></div>").css({
                            "position":"fixed",
                            "width":"400px",
                            "height":"300px",
                            "left":"50%",
                            "top":"50%",
                            "margin-left":"-200px",
                            "margin-top":"-150px",
                            "background":"#fff",
                            "border":"#1f7ed0 1px solid",
                            "z-index":102
                        }).append(province_picker_title).append(province_picker_close).append(province_list).hide();


                        var city_list=$("<ul class='city-list'></ul>").css({
                            "position":"absolute",
                            "left":"0px",
                            "right":"0px",
                            "top":"0px",
                            "bottom":"0px",
                            "display":"block",
                            "line-height":"30px"
                        });


                        var city_picker=$("<div class='city-picker'></div>").css({
                            "position":"absolute",
                            "width":"400px",
                            "height":"300px",
                            "left":"50px",
                            "top":"50px",
                            "background":"#fff",
                            "border":"#1f7ed0 1px solid",
                            "z-index":103
                        }).mouseleave(function(){
                            $(this).fadeOut();
                        }).append(city_list).hide();

                        $("body").append(district_layer);
                        $("body").append(province_picker.append(city_picker));

                        $(".search input").change(function(){
                            var keywords=$(this).val();
                            $.ajax({
                                url:'<?=Url::to(['district'])?>?keywords='+keywords+'&type=2',
                                dataType:'json',
                                success:function(res){
                                    $(".country-list").empty();
                                    for(var x in res){
                                        var li=$("<li data-id='"+x+"'>"+res[x]+"</li>").css({
                                            "display":"inline-block",
                                            "list-style":"none",
                                            "margin-left":"10px",
                                            "cursor":"pointer"
                                        });
                                        $(".country-list").append(li);
                                    }
                                    $(".district-layer").show();
                                    $(".country-picker").show();
                                }
                            });
                        });


                        $(".province-list").delegate("li","click",function(){
                            var _this=$(this);
                            $(".city-list").empty();
                            $(this).addClass("active").siblings("li").removeClass("active");
                            $.ajax({
                                url:'<?=Url::to(['district'])?>?type=2&district_id='+_this.data('id'),
                                dataType:'json',
                                success:function(res){
                                    for(var x in res){
                                        var li=$("<li data-id='"+x+"'>"+res[x]+"</li>").css({
                                            "display":"inline-block",
                                            "list-style":"none",
                                            "margin-left":"10px",
                                            "cursor":"pointer"
                                        });
                                        if($.isArray(_this.data("rows"))){
                                            var r=_this.data("rows").filter(function(row){
                                                return row.city.id==x;
                                            });
                                            if(r.length){
                                                li.addClass("selected");
                                            }
                                        }
                                        $(".city-list").append(li);
                                    }
                                    $(".city-picker").fadeIn();
                                }
                            });
                        });

                        $(".city-list").delegate("li","click",function(){
                            var city=$(this);
                            city.addClass("active").siblings("li").removeClass("active");
                            var province=$(".province-list li.active");
                            var rows=province.data("rows");
                            var city_id=city.data('id');
                            if($(this).hasClass("selected")){
                                delete rows[city_id];
                            }else{
                                if(!$.isArray(rows)){
                                    rows=[];
                                }
                                rows[city_id]={
                                    country:{
                                        id:1,
                                        name:"中国"
                                    },
                                    province:{
                                        id:province.data("id"),
                                        name:province.text()
                                    },
                                    city:{
                                        id:city.data("id"),
                                        name:city.text()
                                    }
                                };
                            }
                            province.data("rows",rows);
                            $(this).toggleClass("selected");
                            update();
                        });

                        $.ajax({
                            url:'<?=Url::to(['district'])?>?district_id=1&type=2',
                            dataType:'json',
                            success:function(res){
                                for(var x in res){
                                    var li=$("<li data-id='"+x+"'>"+res[x]+"</li>").css({
                                        "display":"inline-block",
                                        "list-style":"none",
                                        "margin-left":"10px",
                                        "cursor":"pointer"
                                    });
                                    if($.isArray(t.data("rows")[x])){
                                        li.data("rows",t.data("rows")[x]);
                                        li.addClass("selected");
                                    }
                                    $(".province-list").append(li);
                                }
                                $(".district-layer").show();
                                $(".province-picker").fadeIn();
                            }
                        });


                    }

                    $(this).click(function(){
                        regenerate.apply(this);
                        a.apply(t);
                    });
                },
                getData:function(){
                    var data=[];
                    $(".province-list li").each(function(){
                        if($.isArray($(this).data("rows"))){
                            $(this).data("rows").forEach(function(row){
                                data.push(row);
                            });
                        }
                    });
                    return data;
                }
            };
            if(typeof a!="function" || typeof b!="function"){
                $.error("error");
            }
            return methods.init.apply(this,arguments);
        }


        $(".ship-add").district_picker(function(){
            var res=[];
            $(".ship-box tr").each(function(){
                var province=$(this).find(".province_no");
                var city=$(this).find(".city_no");
                var province_id=province.val();
                var city_id=city.val();
                if(!$.isArray(res[province_id])){
                    res[province_id]=[];
                }
                res[province_id][city_id]={
                    country:{
                        id:$(this).find(".country_no").val(),
                        name:$(this).find(".country_name").val()
                    },
                    province:{
                        id:$(this).find(".province_no").val(),
                        name:$(this).find(".province_name").val()
                    },
                    city:{
                        id:$(this).find(".city_no").val(),
                        name:$(this).find(".city_name").val()
                    }
                };
            });
            $(this).data("rows",res);
        },function(res){
            $(".ship-box tr").remove();
            res.forEach(function(row,index){
                var tr=$("<tr></tr>")
                    .append("<input type='hidden' class='country_name' name='BsShip[country_name][]' value='"+row.country.name+"' >")
                    .append("<input type='hidden' class='province_name' name='BsShip[province_name][]' value='"+row.province.name+"' >")
                    .append("<input type='hidden' class='city_name' name='BsShip[city_name][]' value='"+row.city.name+"' >")
                    .append("<input type='hidden' class='country_no'  name='BsShip[country_no][]' value='"+row.country.id+"' >")
                    .append("<input type='hidden' class='province_no'  name='BsShip[province_no][]' value='"+row.province.id+"' >")
                    .append("<input type='hidden'  class='city_no' name='BsShip[city_no][]' value='"+row.city.id+"' >")
                    .append("<td>"+index+"</td>")
                    .append("<td>"+row.country.name+"</td>")
                    .append("<td>"+row.province.name+"</td>")
                    .append("<td>"+row.city.name+"</td>")
                    .append($("<td><a class='remove'>删除</a></td>"));
                $(".ship-box").append(tr);
                $(".ship-box tr").each(function(){
                    $(this).find("td").first().text(parseInt($(this).index())+1)
                });
            });
            $(".ship-box").next("tfoot").css("display",$(".ship-box tr").size()>0?"none":"table-footer-group");
        });

        $(".ship-box").delegate(".remove","click",function(){
            $(this).parents("tr").remove();
            $(".ship-box tr").each(function(){
                $(this).find("td").first().text(parseInt($(this).index())+1)
            });
            $(".ship-box").next("tfoot").css("display",$(".ship-box tr").size()>0?"none":"table-footer-group");
        });

        $(".deliv-add").district_picker(function(){
            var res=[];
            $(".deliv-box tr").each(function(){
                var province=$(this).find(".province_no");
                var city=$(this).find(".city_no");
                var province_id=province.val();
                var city_id=city.val();
                if(!$.isArray(res[province_id])){
                    res[province_id]=[];
                }
                res[province_id][city_id]={
                    country:{
                        id:$(this).find(".country_no").val(),
                        name:$(this).find(".country_name").val()
                    },
                    province:{
                        id:$(this).find(".province_no").val(),
                        name:$(this).find(".province_name").val()
                    },
                    city:{
                        id:$(this).find(".city_no").val(),
                        name:$(this).find(".city_name").val()
                    }
                };
            });
            $(this).data("rows",res);
        },function(res){
            $(".deliv-box tr").remove();
            res.forEach(function(row,index){
                var tr=$("<tr></tr>")
                    .append("<input type='hidden' class='country_name' name='BsDeliv[country_name][]' value='"+row.country.name+"' >")
                    .append("<input type='hidden' class='province_name' name='BsDeliv[province_name][]' value='"+row.province.name+"' >")
                    .append("<input type='hidden' class='city_name' name='BsDeliv[city_name][]' value='"+row.city.name+"' >")
                    .append("<input type='hidden' class='country_no' name='BsDeliv[country_no][]' value='"+row.country.id+"' >")
                    .append("<input type='hidden' class='province_no' name='BsDeliv[province_no][]' value='"+row.province.id+"' >")
                    .append("<input type='hidden' class='city_no' name='BsDeliv[city_no][]' value='"+row.city.id+"' >")
                    .append("<td>"+index+"</td>")
                    .append("<td>"+row.country.name+"</td>")
                    .append("<td>"+row.province.name+"</td>")
                    .append("<td>"+row.city.name+"</td>")
                    .append($("<td><a class='remove'>删除</a></td>"));
                $(".deliv-box").append(tr);
                $(".deliv-box tr").each(function(){
                    $(this).find("td").first().text(parseInt($(this).index())+1)
                });
            });
            $(".deliv-box").next("tfoot").css("display",$(".deliv-box tr").size()>0?"none":"table-footer-group");
        });

        $(".deliv-box").delegate(".remove","click",function(){
            $(this).parents("tr").remove();
            $(".deliv-box tr").each(function(){
                $(this).find("td").first().text(parseInt($(this).index())+1)
            });
            $(".deliv-box").next("tfoot").css("display",$(".deliv-box tr").size()>0?"none":"table-footer-group");
        });

    </script>
</div>


<script>


    $(function(){

        $.extend($.fn.validatebox.defaults.rules, {
            minOrder: {
                validator: function(value,param){
                    var qty=parseFloat($("#pdt_qty").val());
                    return value && value>=qty && (value*100)%(qty*100)==0;
                },
                message: '必须大于或等于销售包装内商品数量且为整数倍'
            },
            minqty: {
                validator: function(value,param){
                    var min_order=parseFloat($("#min_order").val());
                    return value==min_order;
                },
                message: '最小值必须等于最小订购量'
            },
            maxqty: {
                validator: function(value,param){
                    var qty=parseFloat($("#pdt_qty").val());
                    var min=parseFloat($(this).parents("tr").find('.minqty').val());
                    return value>min && value%qty==0;
                },
                message: '最大值必须大于最小值且为销售包装内商品数量整数倍'
            },
            price: {
                validator: function(value,param){
                    var pattern=/^\d{1,14}(\.\d{1,5})?$/;
                    return ($.isNumeric(value) && value>0 && pattern.test(value)) || parseInt(value)==-1;
                },
                message: '价格必须为大于等于0的数字(最多14位整数,最多5位小数)或-1(面议),'
            },
            decimal: {
                validator: function(value, param){
                    var a=parseInt(param[0]);
                    var b=parseInt(param[1]);
                    if(!($.isNumeric(value) && /^\d+(.\d+)?$/.test(value))){
                        $.fn.validatebox.defaults.rules.decimal.message='请输入有效数字';
                        return false;
                    }
                    var pattern=new RegExp("^[0-9]{1,"+(a-b)+"}([.][0-9]{1,"+b+"})?$");
                    if(!pattern.test(value)){
                        $.fn.validatebox.defaults.rules.decimal.message='最多输入'+(a-b)+'位整数,'+b+'位小数';
                        return false;
                    }
                    return true;
                }
            }
        });

        $("#house_all").click(function(){
            $("._house :checkbox").prop("checked",$(this).prop("checked"));
        });
        $("._house :checkbox").click(function(){
            $("#house_all").prop("checked", $("._house tbody :checkbox").size()==$("._house tbody :checked").size());
        });
        $("#house_all").prop("checked",$("._house tbody :checkbox").size()==$("._house tbody :checked").size());


        $(".warr-tab :radio").click(function(){
            var index=$(this).index();
            $(".warr-tab-content").empty().html($(".warr-tab-item").eq(index-1).clone());
            $.parser.parse($(".warr-tab-content"));
        });

        $("#min_order").change(function(){
            $(".price-box .minqty:first").val($(this).val());
            $(".stock-box .minqty:first").val($(this).val());
        });

        $("#pdt_qty").change(function(){
            if($(this).validatebox("isValid")){
                $("#pack_num").val($(this).val());
            }
        });

        $(".select-year").click(function () {
            jeDate({
                dateCell: this,
                zIndex:8831,
                format: "YYYY",
                skinCell: "jedatedeep",
                isTime: false,
                okfun:function(elem) {
                    $(elem).change();
                },
                //点击日期后的回调, elem当前输入框ID, val当前选择的值
                choosefun:function(elem) {
                    $(elem).change();
                }
            })
        });





        $(".city_part").click(function(){
            $("#_addadress").show();
        });

        $(".city_all").click(function(){
            $("#_addadress").hide();
        });



        //是否自提
        $("[name='BsPartno[isselftake]']").click(function () {
            $("#_byself").css("display",$(this).val()==1?"block":"none");
        });

        //免运费控制
        $("#country_free,#country_nofree").click(function () {
            $("#_addadress").removeAttr("style");
        });
        $("#country_part_free,#country_part_nofree").click(function () {
            $("#_addadress").css("display","none");
        });
        //添加备货期
        $("#_addrow").click(function () {
            var tr=$('<tr>' +
                '<td class="editable" width="200"><input class="minqty"  name="BsStock[min_qty][]" type="text" maxlength="11" readonly></td>' +
                '<td class="editable" width="200"><input class="easyui-validatebox maxqty" data-options="required:true,validType:[\'decimal[11,2]\',\'maxqty\']" name="BsStock[max_qty][]" type="text" maxlength="11"></td>' +
                '<td class="editable" width="200"><input class="easyui-validatebox" data-options="required:true,validType:\'decimal[11,2]\'" name="BsStock[stock_time][]" type="text" maxlength="11"></td>' +
                '<td><a class="remove">删除</a></td></tr>');
            $("#product_table1").append(tr);
            $.parser.parse(tr);
            $(".stock-box .maxqty").validatebox({
                required:true,
                validType:['decimal[11,2]','maxqty']
            });
            $(".stock-box .maxqty:last").validatebox({
                required: false,
                validType:['decimal[11,2]','maxqty']
            });
            $(".stock-box .maxqty").prop("readonly",false);
            $(".stock-box .maxqty:last").prop("readonly",true);
            var pattern=/^\d+(.\d+)?$/;
            var qty=parseFloat($("#pdt_qty").val());
            var min=parseFloat(tr.prev("tr").find(".maxqty").val());
            if(min && pattern.test(min)){
                tr.find(".minqty").val((min*100+qty*100)/100);
            }
        });
        //删除操作
        $("#product_table1").on("click",".remove",function () {
            $(this).parents("tr").remove();
            $(".stock-box .maxqty").validatebox({
                required:true,
                validType:['decimal[11,2]','maxqty']
            });
            $(".stock-box .maxqty:last").validatebox({
                required: false,
                validType:['decimal[11,2]','maxqty']
            });
            $(".stock-box .maxqty").prop("readonly",false);
            $(".stock-box .maxqty:last").prop("readonly",true).val("");
        });






        $(".price-box .remove:last").show();
        $(".price-box select").css("background","rgb(235, 235, 228)").prop("disabled",true);
        //添加销售价格
        $("#_addprice").click(function () {
            $(".price-box .remove").hide();
            var tr=$('<tr>' +
                '<td class="editable" width="200"><input class="minqty" name="BsPrice[minqty][]" type="text" maxlength="11" readonly></td>' +
                '<td class="editable" width="200"><input class="maxqty easyui-validatebox" data-options="required:true,validType:[\'decimal[19,2]\',\'maxqty\']" name="BsPrice[maxqty][]" type="text" maxlength="11"></td>' +
                '<td class="editable" width="200"><input class="easyui-validatebox" data-options="required:true,validType:\'price\'" name="BsPrice[price][]" type="text" maxlength="20"></td>' +
                '<td class="editable" width="200">'+$("#currency-tmpl").html()+'</td>' +
                '<td><a class="remove">删除</a></td></tr>');
            $(".price-box").append(tr);
            $(".price-box select").css("background","rgb(235, 235, 228)").prop("disabled",true);
            $.parser.parse();
            $(".price-box .maxqty").validatebox({
                required:true,
                validType:['decimal[19,2]','maxqty']
            });
            $(".price-box .maxqty:last").validatebox({
                required: false,
                validType:['decimal[19,2]','maxqty']
            });
            $(".price-box .maxqty").prop("readonly",false);
            $(".price-box .maxqty:last").prop("readonly",true);
            var pattern=/^\d+(.\d+)?$/;
            var qty=parseFloat($("#pdt_qty").val());
            var min=parseFloat(tr.prev("tr").find(".maxqty").val());
            if(min && pattern.test(min)){
                tr.find(".minqty").val((min*100+qty*100)/100);
            }
        });

        $("body").on("input",".maxqty",function(){
            var val=parseFloat($(this).val());
            var qty=parseFloat($("#pdt_qty").val());
            if($(this).validatebox("isValid")){
                $(this).parents("tr").next().find(".minqty").val((val*100+qty*100)/100);
            }
        });

        //销售价格删除
        $("body").on("click",".remove",function () {
            $(this).parents("tr").remove();
            $(".price-box .remove:last").show();
            $.parser.parse();
            $(".price-box .maxqty").validatebox({
                required:true,
                validType:['decimal[19,2]','maxqty']
            });
            $(".price-box .maxqty:last").validatebox({
                required: false,
                validType:['decimal[19,2]','maxqty']
            });
            $(".price-box .maxqty").prop("readonly",false);
            $(".price-box .maxqty:last").prop("readonly",true).val("");
        });


        //包装信息-重置
        $(".pack-box").delegate(".reset","click",function(){
            $(this).parents("tr").find(".editable input").val("");
            $(this).parents("tr").find(":checked").prop("checked",false);
            $.parser.parse($(this).parents("tr"));
        });

        //延保方案-新增
        $(".add-warr").click(function(){
            var tr=$("<tr>" +
                "<td></td>" +
                "<td class='editable'><input type='text' class='easyui-validatebox' data-options="+"validType:'int'"+"  name='BsWarr[wrr_prd][]' maxlength='9'></td>" +
                "<td class='editable'><input type='text' class='easyui-validatebox' data-options="+"validType:'decimal[10,2]'"+"  name='BsWarr[wrr_fee][]' maxlength='11'></td>" +
                "<td><a class='remove'>删除</a></td>" +
                "</tr>");
            $(".warr-box").append(tr);
            $(".warr-box tr").each(function(){
                $(this).find("td").first().text(parseInt($(this).index())+1)
            });
            $(".warr-box").next("tfoot").css("display",$(".warr-box tr").size()>0?"none":"table-footer-group");
            $.parser.parse(tr);
        });
        //延保方案-删除
        $(".warr-box").delegate(".remove","click",function(){
            $(this).parents("tr").remove();
            $(".warr-box tr").each(function(){
                $(this).find("td").first().text(parseInt($(this).index())+1)
            });
            $(".warr-box").next("tfoot").css("display",$(".warr-box tr").size()>0?"none":"table-footer-group");
        });
    });

    var myclick = function(v) {
        var llis = $(".tab_li");
        for(var i = 0; i < llis.length; i++) {
            var lli = llis[i];
            if(lli == document.getElementById("tab" + v)) {
                lli.style.backgroundColor = "#1f7ea0";
            } else {
                lli.style.backgroundColor = "#1f7ed0";
            }
        }
        var divs = document.getElementsByClassName("tab_css");
        for(var i = 0; i < divs.length; i++) {

            var divv = divs[i];

            if(divv == document.getElementById("tab" + v + "_content")) {
                divv.style.display = "block";
            } else {
                divv.style.display = "none";
            }
        }

    };



</script>