<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
?>
<style>
    .head-second{background-color: #ffffff}
    #tab_bar {  width: 950px;  height: 20px;  float: left;  }
    #tab_bar ul {  padding: 0px;  margin: 0px;  height: 23px;  text-align: center;  }
    #tab_bar li {  list-style-type: none;  float: left;  width: 85px;  height: 23px;  background-color: #1f7ed0;  margin-right: 5px;  line-height: 23px;  cursor: pointer;  color: #ffffff;  }
    .tab_css {  width: 990px; /*height: 200px;*/ /*background-color: darkgray;*/  height: auto;  display: none;  float: left;  margin-top: 20px;  }
    ._basic{height:800px;}
    ._house{height: 370px;width:950px;margin:0 auto; background: #c9c9c9;overflow: auto}
    ._addrows{height: 30px;width: 100px;border: 1px solid #00b3ee}
    label::after {  content: '';  }
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
</style>
<div class="content">
    <h1 class="head-first">
        修改商品信息
    </h1>
<?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <h2 class="head-three">
        <i class="icon-caret-down"></i>
        <a href="javascript:void(0)">基本信息</a>
    </h2>
    <div class="ml-10">
        <div class="mb-20">
            <label class="width-70"><span class="red">*</span>品&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp名</label>
            <input name="pdt_name" class="width-200  add-require easyui-validatebox "  data-options=""    type="text" value="" maxlength="50">
            <label class="width-140">品&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp牌</label>
            <?=Html::dropDownList("brand_id",$model["brand_id"],$options["brands"],["prompt"=>"请选择","class"=>"width-200 easyui-validatebox","data-options"=>"required:true"])?>
        </div>
        <div class="mb-20">
            <label class="width-70" for="">商品标题</label>
            <input class="width-200 easyui-validatebox" data-options="" type="text" name="pdt_title" value="" maxlength="33" >

            <label class="width-140" for="">商品关键字</label>
            <input class="width-200 easyui-validatebox" data-options="" type="text" name="pdt_keyword" value="" maxlength="50" >
        </div>
        <div class="mb-20">
            <label class="width-70">商品标签</label>
            <input class="width-200 easyui-validatebox" data-options="required:'true'" data-options="validType:'telphone'" type="text" name="pdt_label" value="" maxlength="50" >
            <label class="width-140"><span class="red">*</span>商&nbsp品&nbsp属&nbsp性</label>
            <?=Html::dropDownList("pdt_attribute",$model["pdt_attribute"],$options["pdt_attribute"],["prompt"=>"请选择","class"=>"width-200 easyui-validatebox","data-options"=>"",])?>
        </div>
    </div>
    <div id="fullbg"></div>
    <div id="dialog">
        <p class="close">
            <a href="#" onclick="closeBg();">关闭</a></p>
        <div>
            <ul>
                <li class="_li" id="_button">中国</li>
            </ul>
        </div>
    </div>
    <div id="dialog2">
        <p class="close">
            <a href="#" onclick="closeBg2();">关闭</a></p>
        <div>
            <ul>
                <li class="_li" id="_button2">广东</li>
            </ul>
        </div>
    </div>
    <div id="dialog3">
        <p class="close">
            <a href="#" onclick="closeBg3();">关闭</a></p>
        <div class="mb-20">
            <input type="hidden" class="i_name" value="">
            <ul>
                <li class="_li"><input style="height: 0px" name="_city" value="深圳" type="checkbox">深圳</li>
                <li class="_li"><input style="height: 0px" name="_city" value="广州" type="checkbox">广州</li>
                <li class="_li"><input style="height: 0px" name="_city" value="中山" type="checkbox">中山</li>
                <li class="_li"><input style="height: 0px" name="_city" value="梅州" type="checkbox">梅州</li>
                <li class="_li"><input style="height: 0px" name="_city" value="湛江" type="checkbox">湛江</li>
                <li class="_li"><input style="height: 0px" name="_city" value="汕头" type="checkbox">汕头</li>
            </ul>
        </div>
        <div class="text-center" style="clear: both;margin-top: 70px;">
            <!--                    <div style="width: 37px;height: 20px;border: 1px solid #00b3ee;margin-left: 60px;float: left"><input type="checkbox" style="display: none" id="checkall" value="全选"><label for="checkall">全选</label></div>-->
            <input type="button"  id="checkall" value="全选" class="button-blue" style="color: #000">
            <input type="button"   id="send" value="确定" class="button-blue" style="color: #000">
            <input type="button"  id="_close" value="关闭" class="button-blue" style="color: #000">
        </div>
    </div>
    <div class="_basic">
        <h2 class="head-second mt-20" style="background-color: #d9f0ff;margin-top: 48px">料号:</h2>
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
                    <li class="tab_li" id="tab9" onclick="myclick(9)">设备用途</li>
                </ul>
            </div>
        </div>
        <div class="tab_css" id="tab1_content" style="display: block">
            <div class="mb-20">
                <label class="width-100" for=""><span class="red">*</span>原&nbsp;&nbsp;    产 &nbsp;   地</label>
                <input class="width-200  data-options="" type="text" name="" value=""  >
                <label class="width-120" for="">型&nbsp;&nbsp;&nbsp;&nbsp;号</label>
                <input class="width-200  data-options="style="margin-left: 20px" " type="text" name="" value=""  >
                <label class="width-120" for=""><span class="red">*</span>保质期（月）</label>
                <input class="width-200  data-options="" type="text" name="" value=""  >
            </div>
            <div class="mb-20">
                <label class="width-100" for=""><span class="red">*</span>最小起订量</label>
                <input class="width-200 easyui-validatebox"  data-options="" type="number" name="" value=""  >
                <label class="width-140" style="margin-left: 45px;" for=""><span class="red">*</span>销售包装内商品数量</label>
                <input class="width-155"  data-options="" type="text" name="" value=""  >
                <label class="width-100" for=""><span class="red">*</span>供&nbsp;&nbsp; 应&nbsp;&nbsp;商</label>
                <input style="margin-left: 20px" class="width-200  data-options=" type="text" name="" value=""  >
            </div>
            <div class="mb-20">
                <label class="width-100" for="">商 品 定 位</label>
                <select class="width-200 easyui-validatebox" name="" data-options="required:'true'" >
                    <option value="">--请选择--</option>
                    <option value="">高</option>
                    <option value="">中</option>
                    <option value="">低</option>
                </select>
                <label class="width-140" for="">L / T（天）</label>
                <input class="width-200  data-options="" type="text" name="" value=""  disabled="true">
                <label class="width-120" for="">法务风险等级</label>
                <select class="width-200 easyui-validatebox" name="" data-options="required:'true'" >
                    <option value="">--请选择--</option>
                    <option value="">高</option>
                    <option value="">中</option>
                    <option value="">低</option>
                </select>
            </div>
            <div class="mb-20">
                <label class="width-100" for=""><span class="red">*</span>是否可议价</label>
                <input type="radio" name="radio" value="1" >是 &nbsp;&nbsp;
                <input type="radio" name="radio" value="2" >否
                <label style="margin-left: 22px" class="width-250" for=""><span class="red">*</span>是 否 保 税</label>
                <input type="radio" name="radio1" value="3" >是 &nbsp;&nbsp;
                <input type="radio" name="radio1" value="4" >否
                <label class="width-250" for=""><span class="red">*</span>是 否 自 提</label>
                <input type="radio" name="radio2" id="_radio2" value="5" ><label for="_radio2">是</label> &nbsp;&nbsp;
                <input type="radio" name="radio2" id="_radio3" value="6" ><label for="_radio3">否</label>
            </div>
            <div class="mb-20">
                <label class="width-100" for="">是 否 代 理</label>
                <input type="radio" name="radio3" value="1" >是 &nbsp;&nbsp;
                <input type="radio" name="radio3" value="2" >否
                <label style="margin-left: 33px" class="width-250" for="">是否批次管理</label>
                <input type="radio" name="radio4" value="1" >是 &nbsp;&nbsp;
                <input type="radio" name="radio4" value="2" >否
                <label class="width-250" for="">是否拳头商品</label>
                <input type="radio" name="radio5" value="1" >是 &nbsp;&nbsp;
                <input type="radio" name="radio5" value="2" >否
            </div>
            <div class="mb-20">
                <label class="width-100" for="">备注</label>
                <textarea name="" id="" cols="105" rows="1" ></textarea>
            </div>
            <div class="mb-20" id="_byself" style="display: none">
                <label class="width-80" for="">自提仓库</label>
                <div class="_house">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>序号</th>
                            <th>仓库名</th>
                            <th>仓库地址</th>
                            <th>联系人</th>
                            <th>联系电话</th>
                        </tr>
                        </thead>
                        <tbody id="product_table">
                        <tr>
                            <td>1</td>
                            <td>150528001241</td>
                            <td>除湿防锈润滑剂(桶装)</td>
                            <td>设备用油</td>
                            <td>23113213212</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>150528001241</td>
                            <td>除湿防锈润滑剂(桶装)</td>
                            <td>设备用油</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>150529001242</td>
                            <td>除湿防锈润滑剂</td>
                            <td>设备用油</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>150528001241</td>
                            <td>除湿防锈润滑剂(桶装)</td>
                            <td>设备用油</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>150528001241</td>
                            <td>除湿防锈润滑剂(桶装)</td>
                            <td>设备用油</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>150529001242</td>
                            <td>除湿防锈润滑剂</td>
                            <td>设备用油</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>150528001241</td>
                            <td>除湿防锈润滑剂(桶装)</td>
                            <td>设备用油</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>150528001241</td>
                            <td>除湿防锈润滑剂(桶装)</td>
                            <td>设备用油</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>150529001242</td>
                            <td>除湿防锈润滑剂</td>
                            <td>设备用油</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>150528001241</td>
                            <td>除湿防锈润滑剂(桶装)</td>
                            <td>设备用油</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>150528001241</td>
                            <td>除湿防锈润滑剂(桶装)</td>
                            <td>设备用油</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>150529001242</td>
                            <td>除湿防锈润滑剂</td>
                            <td>设备用油</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>150528001241</td>
                            <td>除湿防锈润滑剂(桶装)</td>
                            <td>设备用油</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>150529001242</td>
                            <td>除湿防锈润滑剂</td>
                            <td>设备用油</td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab_css" id="tab3_content">
            <table class="table">
                <thead>
                <tr>
                    <th>最小值</th>
                    <th>最大值</th>
                    <th>价格</th>
                    <th>币别</th>
                </tr>
                </thead>
                <tbody id="product_table">
                <tr>
                    <td>1</td>
                    <td>50</td>
                    <td>50</td>
                    <td>RMB</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>20</td>
                    <td>30 </td>
                    <td>RMB</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>50</td>
                    <td>50</td>
                    <td>RMB</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="tab_css" id="tab4_content">
            <div class="mb-20">
                <label class="width-100"><span class="red">*</span>成   份</label>
                <input class="width-200 easyui-validatebox" type="text" name="" value=""  >
            </div>
            <div class="mb-20">
                <label class="width-100">材质</label>
                <select class="width-200" name="" data-options="required:'true'" >
                    <option value="">--请选择--</option>
                    <option value="">列表项一</option>
                    <option value="">列表项二</option>
                </select>
            </div>
            <div class="mb-20">
                <label class="width-100">地对地导弹</label>
                <input type="checkbox" name="" value="1">Checkbox &nbsp;&nbsp;
                <input type="checkbox" name="" value="2">Checkbox &nbsp;&nbsp;
                <input type="checkbox" name="" value="3">Checkbox &nbsp;&nbsp;
                <input type="checkbox" name="" value="4">Checkbox &nbsp;&nbsp;
            </div>
            <div class="mb-20">
                <label class="width-100">适用范围</label>生产辅助材料
                <input class="width-200 " type="text" name="" value=""  >
            </div>
            <div class="mb-20">
                <label class="width-100">地对地导弹</label>
                <input type="radio" name="radio" value="1">Radio Button &nbsp;&nbsp;
                <input type="radio" name="radio" value="2">Radio Button &nbsp;&nbsp;
            </div>
        </div>
        <div class="tab_css" id="tab5_content">
            <table class="table">
                <thead>
                <tr>
                    <th>最小值</th>
                    <th>最大值</th>
                    <th>备货时间（天）</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody id="product_table1">
                </tbody>
            </table>
            <div class="mb-20" style="margin-top: 20px;">
                <input type="button" class="_addrows" id="_addrow" value="+添加区间">
            </div>
        </div>
        <div class="tab_css" id="tab6_content">
            <table class="table">
                <thead>
                <tr>
                    <th>国别</th>
                    <th>省分</th>
                    <th>城市</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody class="product_table_addr">
                </tbody>
            </table>
            <div class="mb-20" style="margin-top: 20px;">
                <input type="button" class="_addrows" id="_addcountry" value="+添加国家">
            </div>

        </div>
        <div class="tab_css" id="tab7_content">
            <div class="mb-20">
                <label class="width-10" for=""><span class="red">*</span></label>
                <input type="radio" name="radio" id="country_free" value="1"  ><label for="country_free">全国免运费</label> &nbsp;&nbsp;
                <input type="radio" name="radio" id="country_part_free" value="2" ><label for="country_part_free">全国部分城市免运费</label>&nbsp;&nbsp;
                <input type="radio" name="radio" id="country_nofree" value="1" ><label for="country_nofree">全国不免运费</label> &nbsp;&nbsp;
                <input type="radio" name="radio" id="country_part_nofree" value="2" ><label for="country_part_nofree">全国部分城市不免运费 </label>&nbsp;&nbsp;
            </div>
            <div class="mb-20">
                <div class="mb-10" id="_addadressess" style="display: none">
                <table class="table" >
                    <thead>
                    <tr>
                        <th>国别</th>
                        <th>省分</th>
                        <th>城市</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody class="product_table_addr2">
                    </tbody>
                </table>
                <div class="mb-20" style="margin-top: 20px;">
                    <input type="button" class="_addrows" id="_addcountry2" value="+添加国家">
                </div>
                </div>
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
                        <th>序号</th>
                        <th>商品长</th>
                        <th>商品宽</th>
                        <th>商品高</th>
                        <th>商品重</th>
                        <th>使用富金机包装</th>
                        <th>使用线板</th>
                    </tr>
                    </thead>
                    <tbody id="product_table">
                    <tr>
                        <td>1</td>
                        <td ></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
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
                        <th>序号</th>
                        <th>包装长</th>
                        <th>包装宽</th>
                        <th>包装高</th>
                        <th>包装毛重</th>
                        <th>包材名称</th>
                        <th>销售包装内商品数量</th>
                    </tr>
                    </thead>
                    <tbody id="product_table">
                    <tr>
                        <td>1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
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
                        <th>序号</th>
                        <th>包装长</th>
                        <th>包装宽</th>
                        <th>包装高</th>
                        <th>包装毛重</th>
                        <th>包材名称</th>
                        <th>外包装数量</th>
                    </tr>
                    </thead>
                    <tbody id="product_table">
                    <tr>
                        <td>1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
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
                        <th>序号</th>
                        <th>包装长</th>
                        <th>包装宽</th>
                        <th>包装高</th>
                        <th>包装毛重</th>
                        <th>包材名称</th>
                        <th>散货包装数量</th>
                    </tr>
                    </thead>
                    <tbody id="product_table">
                    <tr>
                        <td>1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
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
                        <th>序号</th>
                        <th>包装长</th>
                        <th>包装宽</th>
                        <th>包装高</th>
                        <th>包装毛重</th>
                        <th>板线数量</th>
                        <th>包装件数量</th>
                    </tr>
                    </thead>
                    <tbody id="product_table">
                    <tr>
                        <td>1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab_css" id="tab9_content" >
            <div class="mb-20">
                <label class="width-70"><span class="red">*</span>延保方案</label>
                <input type="radio" name="radio" value="1" id="_newequip" checked="checked" ><label for="_newequip">新机 &nbsp;&nbsp;</label>
                <input type="radio" name="radio" value="2" id="_oldequip"><label for="_oldequip">二手设备</label>
                <input type="radio" name="radio" value="3" id="_roequip"><label for="_roequip">设备租赁 &nbsp; &nbsp;&nbsp;&nbsp;</label>
            </div>
            <div class="mb-20">
                <label class="width-70">出厂年限:</label>
                <input type="text" class="width-200"  name="" value="2017">
            </div>
            <div class="mb-20" id="_ware" style="display: none">
                <label class="width-70">库 &nbsp;&nbsp;&nbsp;&nbsp; 存:</label>
                <input type="text" class="width-200"  name="" value="" >
            </div>
            <div class="mb-20" id="_newold" style="display: none">
                <label class="width-70">新旧程度:</label>
                <input type="text" class="width-200"  name="" value="" >
            </div>
            <div class="mb-20" id="rotime" style="display: none">
                <label class="width-70">租 &nbsp;&nbsp;&nbsp;&nbsp; 期:</label>
                <input type="text" class="width-200"  name="" value="" >月
            </div>
            <div class="mb-20" id="romoney" style="display: none">
                <label class="width-70">租 &nbsp;&nbsp;&nbsp;&nbsp; 金:</label>
                <input type="text" class="width-200"  name="" value="" >RMB/月
            </div>
            <div class="mb-20" id="_money" style="display: none"    >
                <label class="width-70">押 &nbsp;&nbsp;&nbsp;&nbsp; 金:</label>
                <input type="text" class="width-200"  name="" value="" >RMB
            </div>
            <div class="mb-20"style="margin-bottom: 10px;">
                <span style="font-size:14px;margin-left: 11px ">延保方案</span>
            </div>
            <div class="mb-20">
                <table class="table">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>延保时间(月)</th>
                        <th>延保费用(RMB)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>

            </div>
            <div class="mb-20"style="margin-bottom: 10px;">
                <input type="button" class="_addrows" value="+延保方案">
            </div>
            <label class="width-70" style="float: left">详细说明</label>
            <div class="_ueditor">
                <?=\app\widgets\ueditor\Ueditor::widget(['id'=>'editor'])?>
            </div>
        </div>
    </div>

    <div class="text-center">
        <button class="button-blue-big sub" type="submit">提交</button>
        <button class="button-white-big" onclick="history.go(-1)">返回</button>
    </div>

<?php ActiveForm::end()?>
</div>
<script>
    var myclick = function(v) {
        var llis = $(".tab_li");
        for(var i = 0; i < llis.length; i++) {
            var lli = llis[i];
            if(lli == document.getElementById("tab" + v)) {
                lli.style.backgroundColor = "#1f7ed0";
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
    //是否自提
    $("#_radio2").click(function () {
        $("#_byself").removeAttr("style");
    });
    $("#_radio3").click(function () {
        $("#_byself").css("display","none");
    });

    //免运费控制
    $("#country_free,#country_nofree").click(function () {
        $("#_addadressess").css("display","none");
    });
    $("#country_part_free,#country_part_nofree").click(function () {
        $("#_addadressess").removeAttr("style");
    });
    //添加备货期
    $("#_addrow").click(function () {
        $("#product_table1").append('<tr><td></td><td contentEditable="true"></td><td contentEditable="true"></td><td><a class="delete_reception ">删除</a></td></tr>')
    });
    //删除操作
    $("#product_table1").on("click",".delete_reception",function () {
        $(this).parents("tr").remove();
    });


    //添加国家
    $("#_addcountry").click(function () {
        var bh = $("body").height();
        var bw = $("body").width();
        $("#fullbg").css({
            height: bh,
            width: bw,
            display: "block"
        });
        $("#dialog").show();
        $(".i_name").val("product_table_addr");
    });
    $("#_addcountry2").click(function () {
        var bh = $("body").height();
        var bw = $("body").width();
        $("#fullbg").css({
            height: bh,
            width: bw,
            display: "block"
        });
        $("#dialog").show();
        $(".i_name").val("product_table_addr2");
    });

    //关闭灰色 jQuery 遮罩
    function closeBg() {
        $("#fullbg,#dialog,#dialog2,#dialog3").hide();
    }
    function closeBg2() {
        $("#dialog2,#dialog3").hide();
    }
    function closeBg3() {
        $("#dialog3").hide();
    }

    $("#_button").click(function () {
        $("#dialog2").show();
    });
    //获取城市
    $("#_button2").click(function () {
        $("#dialog3").show();
    });
    //全选
    $("#checkall").click(function () {
        var num=0;
        var _name=document.getElementsByName("_city");
        for(var i=0;i<_name.length;i++){
            if(_name[i].checked=="checked"||_name[i].checked==true){
                num+=1;
            }
        }
        if(num==_name.length){
            $('[name=_city]:checkbox').prop("checked",false);
        }else{
            $('[name=_city]:checkbox').prop("checked",true);
        }
    })
    //确定选择的值
    $("#send").click(function () {
        var _url=$(".i_name").val();
        var country=$("#_button").text();
        var province=$("#_button2").text();
        var num=0;
        var _name=document.getElementsByName("_city");
        for(var i=0;i<_name.length;i++){
            if(_name[i].checked=="checked"||_name[i].checked==true){
                num+=1;
            }
        }
        if(num==0){
            alert("请至少选择一个地址")
        }else{
            $('[name=_city]:checkbox:checked').each(function () {
                var aa=$(this).val();
                var _td=$("."+_url+" tr td:nth-child(3)").text();
                if(_td.indexOf(aa)>0||_td==aa){
                    alert("不能重复添加地址");
                    return false;
                }else{
                    $("."+_url+"").append('<tr><td>'+country+'</td><td>'+province+'</td><td>'+$(this).val()+'</td><td><a class="delete_reception" onclick="_delite(this,'+ "'_url'" +')">删除</a></td></tr>');
                }
            });
        }
    });
    //关闭操作
    $("#_close").click(function () {
        $("#dialog3").hide();
    })

    //删除操作
    function _delite(obj) {
        $(obj).parents("tr").remove();
    }
    //新机操作
    $("#_newequip").click(function () {
        $("#rotime,#romoney,#_money,#_ware,#_newold").css('display','none');
    });
    //二手设备radio控制
    $("#_oldequip").click(function () {
        $("#_ware,#_newold").removeAttr('style');
        $("#rotime,#romoney,#_money").css('display','none');
    })
    //设备租赁控制
    $("#_roequip").click(function () {
        $("#rotime,#romoney,#_money").removeAttr('style');
        $("#_ware,#_newold").css('display','none');
    })
</script>