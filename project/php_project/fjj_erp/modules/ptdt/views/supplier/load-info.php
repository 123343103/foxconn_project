<?php
/**
 *
 * F3859386
 *  2016/11/24
 */
?>
<div class="space-10"></div>
<a class="width-150 span-height message-goods  " id="info">供应商资料</a>
<a class="width-150 span-height message-firm" id="goods">销售商品信息</a>
<div class="space-10"></div>
<div class="table-content border-table clear firm-message">
    <div class="overflow-auto" >
        <table class="product-list">
            <tr>
                <th colspan="2">申请信息</th>
                <th colspan="3">商品预计账务信息</th>
                <th colspan="4">主营产品</th>
                <th colspan="4">谈判事项</th>
            </tr>
            <tr class="main-title">
                <td>申请编号</td>
                <td>申请日期</td>
                <td>预计年销售额(USD)</td>
                <td>预计年销售利润(USD)</td>
                <td>Source类别</td>
                <td>主营项目</td>
                <td>年销售量(单位)</td>
                <td>市场份额(%)</td>
                <td>是否代理(Y/N)</td>
                <td>代理等级</td>
                <td>授权区域范围</td>
                <td>授权日期</td>
                <td>结算方式</td>
            </tr>
            <tbody>
            <tr>
                <td><?= $dataProvider->supplier_code ?></td>
                <td><?= $dataProvider->create_at ?></td>
                <td><?= $dataProvider->supplier_pre_annual_sales ?></td>
                <td><?= $dataProvider->supplier_pre_annual_profit ?></td>
                <td><?= $dataProvider->sourceType ?></td>
                <?php  if(isset($dataProvider->mainProduct)){ ?>
                <td><?= $dataProvider->mainProduct->vmainl_product ?></td>
                <td><?= $dataProvider->mainProduct->vmainl_yqty ?></td>
                <td><?= $dataProvider->mainProduct->vmainl_marketshare ?></td>
                <td><?= $dataProvider->mainProduct->vmainl_isagent==1?'Y':'N' ?></td>
                <?php   }else{?>
                    <td>/</td>
                    <td>/</td>
                    <td>/</td>
                    <td>/</td>
                <?php  }  ?>
                <?php if(isset($dataProvider->authorize)){  ?>
                <td><?= $dataProvider->authorize->pdaa_agents_grade ?></td>
                <td><?= $dataProvider->authorize->pdaa_authorize_area ?></td>
                <td><?= $dataProvider->authorize->pdaa_date ?></td>
                <td><?= $dataProvider->authorize->pdaa_settlement ?></td>
                <?php }else{?>
                    <td>/</td>
                    <td>/</td>
                    <td>/</td>
                    <td>/</td>
                <?php } ?>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="table-content border-table clear goods-message display-none">
    <div class="overflow-auto">
        <table class="product-list">
            <thead>
            <tr >
                <th>序号</th>
                <th>品名</th>
                <th>规格</th>
<!--                <th>商品定位</th>-->
<!--                <th>定价上限</th>-->
<!--                <th>定价下限</th>-->
<!--                <th>市场行情价</th>-->
<!--                <th>利润率上限</th>-->
<!--                <th>利润率下限</th>-->
                <th>一阶</th>
                <th>二阶</th>
                <th>三阶</th>
                <th>四阶</th>
                <th>五阶</th>
                <th>六阶</th>
            </tr>
            </thead>
            <tbody>
                        <?php foreach ($dataMaterial as $key =>$val){ ?>
                            <tr >
                                <td><?= $key+=1 ?></td>
                                <td><?= $val->pro_name ?></td>
                                <td><?= $val->pro_size ?></td>
                                <td><?= $val->typeName[0] ?></td>
                                <td><?= $val->typeName[1] ?></td>
                                <td><?= $val->typeName[2] ?></td>
                                <td><?= $val->typeName[3] ?></td>
                                <td><?= $val->typeName[4] ?></td>
                                <td><?= $val->typeName[5] ?></td>
                            </tr>
                            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(function(){
        $("#goods").click(function(){
            $("#info").removeClass("message-goods").addClass("message-firm");
            $("#goods").removeClass("message-firm").addClass("message-goods");
            $(".firm-message").hide();
            $(".goods-message").show();
        });
        $("#info").click(function(){
            $("#goods").removeClass("message-goods").addClass("message-firm");
            $("#info").removeClass("message-firm").addClass("message-goods");
            $(".goods-message").hide();
            $(".firm-message").show();
        });
    })
</script>