<?php
/**
 * Created by PhpStorm.
 * User: F1677929
 * Date: 2016/9/19
 * Time: 上午 09:20
 */
?>
<style>
    /* 进度条样式-start- */
    .darkgrey {
        color: darkgrey;
    }

    .step {
        width: 30px;
        height: 30px;
        background-color: aliceblue;
        border-radius: 100%;
        text-align: center;
        line-height: 30px;
        border: solid 1px darkgrey;
    }

    .progress-bar {
        margin: 0 auto;
        width: 880px;
        border: solid 4px darkgrey;
        border-radius: 10px;
        position: relative;
        top: 20px;
    }

    .progress {
        overflow: hidden;
        width: 950px;
        margin-left: auto;
        margin-right: auto;
        position: relative;
        z-index: 1;
    }

    .width-190 {
        width: 190px;
    }
    /* 进度条样式-end- */
    /* 进度条弹出框样式-start- */
    #fullbg {
        background-color:lightgrey;
        position:absolute;
        left:0;
        top:0;
        z-index:3;
        opacity: 0.5;
    }

    #dialog {
        background-color:#fff;
        width:800px;
        z-index:5;
        display:none;
        position:fixed !important;
        position:absolute;
        left:50%;
        top:50%;
        margin-left: -400px;
        margin-top: -200px;
    }
    /* 进度条弹出框样式-end- */
</style>
<div class="content">
    <div class="border-lightgrey mb-20 pt-10 pb-10">
        <div>
            <label class="width-80">状态</label>
            <span class="red ml-10">认证中</span>
        </div>
        <div class="mb-10">
            <label class="width-80">备注</label>
            <span class="ml-10">有审核并且驳回时，原因显示在此</span>
        </div>
        <!-- 进度条-start- 有问题需要修改 -->
        <div class="text-center">
            <div class="progress-bar"></div>
            <ul class="progress">
                <li class="width-190 float-left">
                    <span class="step mb-10">1</span>
                    <div class="mb-10 darkgrey cursor-pointer" id="step1">收集资源</div>
                    <div class="mb-10"></div>
                </li>
                <li class="width-190 float-left">
                    <span class="step mb-10">2</span>
                    <div class="mb-10 darkgrey">代理谈判</div>
                    <div class="mb-10"></div>
                </li>
                <li class="width-190 float-left">
                    <span class="step mb-10">3</span>
                    <div class="mb-10 darkgrey">商品认证</div>
                    <div class="mb-10"></div>
                </li>
                <li class="width-190 float-left">
                    <span class="step mb-10">4</span>
                    <div class="mb-10 darkgrey">呈报</div>
                    <div class="mb-10"></div>
                </li>
                <li class="width-190 float-left">
                    <span class="step mb-10">5</span>
                    <div class="mb-10 darkgrey">完成</div>
                    <div class="mb-10"></div>
                </li>
            </ul>
        </div>
        <!-- 进度条-end- -->
        <!-- 进度条弹出框-start- -->
        <div class="text-center">
            <div id="fullbg"></div>
            <div class="text-center" id="dialog">
                <div class="mb-30">
                    <h1 class="head-first no-text-indent mb-40">验证履历</h1>
                </div>
                <div class="mb-40">
                    <table class="table-small">
                        <tr>
                            <th>内容</th>
                            <th>对象</th>
                            <th>操作时间</th>
                            <th>操作人员</th>
                        </tr>
                        <tr>
                            <td>验证计划书生成</td>
                            <td>商品1</td>
                            <td>2015-10-10&nbsp;10:10:10</td>
                            <td>李四</td>
                        </tr>
                        <tr>
                            <td>新增样品需求</td>
                            <td>商品1</td>
                            <td>2015-10-10&nbsp;10:10:10</td>
                            <td>李四</td>
                        </tr>
                        <tr>
                            <td>新增需求表</td>
                            <td>商品1</td>
                            <td>2015-10-10&nbsp;10:10:10</td>
                            <td>李美茹</td>
                        </tr>
                    </table>
                </div>
                <div class="mb-30">
                    <button class="button-white-big" id="close">关闭</button>
                </div>
            </div>
        </div>
        <!-- 进度条弹出框-end- -->
    </div>
    <h1 class="head-first">
        认证详情
        <a href="" class="white float-right pr-20">导出Excel</a>
    </h1>
    <div class="border-lightgrey mb-40 pt-10">
        <div class="mb-10">
            <label class="width-100">申请单位：</label>
            <span>化学品开发</span>
            <label class="width-100">申请人：</label>
            <span>王菲</span>
            <label class="width-100">日期：</label>
            <span>2015-11-11</span>
        </div>
        <div class="mb-40">
            <h2 class="head-second no-text-indent text-center">供应商基本信息</h2>
            <div class="mb-10">
                <label class="width-100">商品类别：</label>
                <span class="width-200">化学产品</span>
                <label class="width-100">商品名称：</label>
                <span class="width-200">广州生熊外贸有限公司</span>
                <label class="width-100">厂商电话：</label>
                <span class="width-200">13589562301</span>
            </div>
            <div class="mb-10">
                <label class="width-100">主管范围：</label>
                <span class="width-200">金属制品、化学产品等</span>
                <label class="width-100">厂商地址：</label>
                <span class="width-200">广东省深圳市龙岗区季华路13号</span>
                <label class="width-100">注册资本：</label>
                <span class="width-200">1000万</span>
            </div>
            <div class="mb-10">
                <label class="width-100">员工总数：</label>
                <span class="width-200">599人</span>
                <label class="width-100">负责人：</label>
                <span class="width-200">张国建</span>
                <label class="width-100">建厂日期：</label>
                <span class="width-200">2003-7</span>
            </div>
            <div class="mb-10">
                <label class="width-100">厂商类型：</label>
                <input type="radio" name="radio" class="vertical-top">
                <span>制造商</span>
                <input type="radio" name="radio" class="vertical-top ml-20">
                <span>代理商</span>
                <input type="radio" name="radio" class="vertical-top ml-20">
                <span>其他(请填写)</span>
            </div>
        </div>
        <div class="mb-40">
            <h2 class="head-second no-text-indent text-center">商品免验证项目</h2>
            <table class="table-small">
                <tr>
                    <th>品名</th>
                    <th>规格型号</th>
                    <th>第一阶</th>
                    <th>第二阶</th>
                    <th>第三阶</th>
                    <th>第四阶</th>
                    <th>第五阶</th>
                    <th>第六阶</th>
                </tr>
                <tr>
                    <td>浓硫酸</td>
                    <td>99.99%</td>
                    <td>化工材料</td>
                    <td>特用化学品</td>
                    <td>胶黏用剂</td>
                    <td>硅(矽)胶</td>
                    <td>硅(矽)溶胶</td>
                    <td>硅(矽)溶胶</td>
                </tr>
                <tr>
                    <td>硝硫酸</td>
                    <td>sa4515</td>
                    <td>化工材料</td>
                    <td>特用化学品</td>
                    <td>合金铸造化学品</td>
                    <td>脱模剂</td>
                    <td>铝合金脱模剂</td>
                    <td>铝合金脱模剂</td>
                </tr>
            </table>
        </div>
        <div class="mb-40">
            <h2 class="head-second no-text-indent text-center">免验证说明</h2>
            <p class="ml-30 mr-30">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                Aenean euismod bibendum laoreet.
                Proin gravida dolor sit amet lacus accumsan et viverra justo commodo.
                Proin sodales pulvinar tempor.
                Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                Nam fermentum, nulla luctus pharetra vulputate, felis Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                Aenean euismod bibendum laoreet.
                Proin gravida dolor sit amet lacus accumsan et viverra justo commodo.
                Proin sodales pulvinar tempor.
                Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                Nam fermentum, nulla luctus pharetra vulputate, felis
            </p>
        </div>
    </div>
</div>

<script type="text/javascript">
    //显示灰色 jQuery 遮罩层
    function showBg() {
        var bh = $("body").height();
        var bw = $("body").width();
        $("#fullbg").css({ height:bh, width:bw });
        $("#fullbg, #dialog").show();
    }
    //关闭灰色 jQuery 遮罩
    function closeBg() {
        $("#fullbg, #dialog").hide();
    }

    $(function() {
        $("#step1").click(function () {
            showBg();
        });
        $("#close").click(function () {
            closeBg();
        });
    });

    /*$(document).ready(function () {
        $("#step1").click(function () {
            showBg();
        });
        $("#close").click(function () {
            closeBg();
        });
    });*/
</script>