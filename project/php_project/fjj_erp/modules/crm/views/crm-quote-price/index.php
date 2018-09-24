<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/1/11
 * Time: 下午 02:13
 */
$this->title="新增报价";
$this->params['homeLike'] = ['label'=>'客户关系系统'];
$this->params['breadcrumbs'][] = ['label' => '报价单'];
$this->params['breadcrumbs'][] = ['label' => '报价单列表'];
?>
<div class="content">

    <div class="mt-20">
        <label for="" class="width-80">客户名称</label>
        <input type="text" class="width-120">
        <label for="" class="width-80">状态</label>
        <select name="" id="" class="width-120">
            <option value=""></option>
            <option value=""></option>
            <option value=""></option>
        </select>
        <label for="" class="width-100">报价试算编号</label>
        <input type="text" class="width-120">
        <label for="" class="width-80">联系人</label>
        <input type="text" class="width-120">
    </div>

    <div class="mt-20">
        <label for="" class="width-80">交易法人</label>
        <select name="" id="" class="width-120">
            <option value=""></option>
            <option value=""></option>
            <option value=""></option>
        </select>
        <label for="" class="width-80">交易模式</label>
        <select name="" id="" class="width-120">
            <option value=""></option>
            <option value=""></option>
            <option value=""></option>
        </select>
        <label for="" class="width-100">报价人</label>
        <input type="text" class="width-120">
    </div>

    <div class="mt-20 mb-20">
        <label for="" class="width-80">报价日期</label>
        <input type="text" class="width-120 select-date"> ~ <input type="text" class="width-120 select-date">

        <button class="button-blue ml-90">查询</button>
        <button class="button-blue">重置</button>
        <button class="button-blue">高级</button>
    </div>



    <div class="table-head mb-10">
        <p class="float-left">报价列表</p>
        <p class="float-right">
            <a><span>新增</span></a>
            <a><span>编辑</span></a>
            <a><span>详情</a>
            <a><span>送审</a>
            <a><span>删除</a>
            <a><span>导出</a>
        </p>
        <div style="clear: both;"></div>
    </div>
    <div class="table-content">
        <div id="data" style="width: 100%;"></div>
        <div id="load-content"></div>
    </div>
</div>
<script>
    $(function(){
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            singleSelect: true,
            checkOnSelect: true,
            selectOnCheck: false,
            fitColumns:true,
            columns:[[
                {field:"quotedprice_code",title:"报价试算编号",width:150},
                {field:"cust_sname",title:"客户名称",width:150},
                {field:"cust_contacts",title:"客户联系人",width:150},
                {field:"cust_inchargeperson",title:"交易法人",width:150},
                {field:"applicant",title:"申请人",width:150},
                {field:"saph_date",title:"报价日期",width:150},
                {field:"cust_risk",title:"客户风险",width:150},
                {field:"status",title:"状态",width:150,formatter:function(value,row,index){
                    return "未审核";
                }}
            ]]
        });
    });
</script>