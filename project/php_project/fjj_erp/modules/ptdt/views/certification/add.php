<?php
/**
 * Created by PhpStorm.
 * User: F1677929
 * Date: 2016/9/12
 * Time: 上午 11:48
 */
$this->params['homeLike'] = ['label'=>'验证中心管理', 'url'=>''];
$this->params['breadcrumbs'][] = ['label'=>'商品认证', 'url'=>''];
$this->params['breadcrumbs'][] = ['label'=>'认证需求', 'url'=>''];
?>
<div class="content">
    <h1 class="head-first">新增认证需求</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="ml-10 mb-10">
            <input class="vertical-center" type="radio" name="radio" checked="checked">
            <span class="width-50 vertical-center">普通认证</span>
            <input class="vertical-center ml-20" type="radio" name="radio">
            <span class="width-50 vertical-center">免认证</span>
        </div>
        <div class="line-height">
            <span class="width-130 text-center float-left cursor-pointer border-1f7ed0 no-border-bottom color-1f7ed0" id="certify1">普通认证</span>
            <span class="width-130 text-center float-left cursor-pointer border-1f7ed0 no-border-top no-border-right no-border-left" id="certify2">相似产品认证项目</span>
            <span class="border-1f7ed0 no-border-top no-border-right no-border-left" style="width: 728px;">&nbsp;</span>
        </div>
        <div class="border-1f7ed0 no-border-top pt-20">
            <div class="mb-40">
                <h2 class="head-second ml-20 mr-20 text-center no-text-indent">供应商基本信息</h2>
                <div class="mb-10">
                    <label class="width-100"><span class="red">*</span>供应商名称：</label>
                    <input class="width-200">
                    <label class="width-150"><span class="red">*</span>供应商联系人/电话：</label>
                    <input class="width-200">
                    <label class="width-100">邮箱：</label>
                    <input class="width-200">
                </div>
                <div class="mb-10">
                    <label class="width-100"><span class="red">*</span>供应商性质：</label>
                    <input class="width-200">
                    <label class="width-150">申请人/联系方式：</label>
                    <input class="width-200">
                    <label class="width-100">当前厂区：</label>
                    <input class="width-200">
                </div>
                <div class="mb-10">
                    <label class="width-100"><span class="red">*</span>供应商地址：</label>
                    <select class="width-100">
                        <option>省</option>
                    </select>
                    <select class="width-100">
                        <option>市</option>
                    </select>
                    <select class="width-100">
                        <option>县区</option>
                    </select>
                    <input style="width: 553px;">
                </div>
                <div>
                    <label class="width-100 vertical-top">特殊说明：</label>
                    <textarea rows="2" style="width: 864px;"></textarea>
                </div>
            </div>
            <div class="mb-40">
                <h2 class="head-second ml-20 mr-20 text-center no-text-indent">商品基本信息</h2>
                <table class="table-small" style="width: 96%;">
                    <tr>
                        <th>序号</th>
                        <th>商品编号</th>
                        <th>商品名称</th>
                        <th>品牌</th>
                        <th>商品类别</th>
                        <th>商品规格/型号</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td><input class="text-center table-content no-border"></td>
                        <td><input class="text-center table-content no-border"></td>
                        <td><input class="text-center table-content no-border"></td>
                        <td><input class="text-center table-content no-border"></td>
                        <td><input class="text-center table-content no-border"></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><input class="text-center table-content no-border"></td>
                        <td><input class="text-center table-content no-border"></td>
                        <td><input class="text-center table-content no-border"></td>
                        <td><input class="text-center table-content no-border"></td>
                        <td><input class="text-center table-content no-border"></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td><input class="text-center table-content no-border"></td>
                        <td><input class="text-center table-content no-border"></td>
                        <td><input class="text-center table-content no-border"></td>
                        <td><input class="text-center table-content no-border"></td>
                        <td><input class="text-center table-content no-border"></td>
                    </tr>
                </table>
            </div>
            <div class="mb-40">
                <h2 class="head-second ml-20 mr-20 text-center no-text-indent">供应商已提供资料</h2>
                <div class="mb-10">
                    <label class="width-130"><span class="red">*</span>产品说明书：</label>
                    <span class="width-130 text-center"><span>12333.pdf</span><span class="red ml-10 cursor-pointer" href="#">x</span></span>
                    <span class="color-1f7ed0">+</span><a href="#">添加附件</a>
                    <input type="file" hidden="hidden" class="width-500">
                </div>
                <div class="mb-10">
                    <label class="width-130"><span class="red">*</span>出货检测报告：</label>
                    <span class="width-130 text-center"><span>12333.pdf</span><span class="red ml-10 cursor-pointer" href="#">x</span></span>
                    <span class="color-1f7ed0">+</span><a href="#">添加附件</a>
                    <input type="file" hidden="hidden" class="width-500">
                </div>
                <div class="mb-10">
                    <label class="width-130"><span class="red">*</span>第三方检测报告：</label>
                    <span class="width-130 text-center"><span>12333.pdf</span><span class="red ml-10 cursor-pointer" href="#">x</span></span>
                    <span class="color-1f7ed0">+</span><a href="#">添加附件</a>
                    <input type="file" hidden="hidden" class="width-500">
                </div>
                <div>
                    <label class="width-130"><span class="red">*</span>客制化商品图纸：</label>
                    <span class="width-130 text-center"><span>12333.pdf</span><span class="red ml-10 cursor-pointer" href="#">x</span></span>
                    <span class="color-1f7ed0">+</span><a href="#">添加附件</a>
                    <input type="file" hidden="hidden" class="width-500">
                </div>
            </div>
        </div>
        <div class="text-center mt-40 pb-40">
            <button class="button-blue-big mr-40">保&nbsp;&nbsp;&nbsp;&nbsp;存</button>
            <button class="button-white-big">重&nbsp;&nbsp;&nbsp;&nbsp;置</button>
        </div>
    </form>
</div>
<script>
    $(function () {
        $("#certify1").click(function () {
            $('#certify1').removeClass('no-border-top no-border-right no-border-left').addClass('no-border-bottom color-1f7ed0');
            $("#certify2").removeClass('no-border-bottom color-1f7ed0').addClass('no-border-top no-border-right no-border-left');
        })
        $("#certify2").click(function () {
            $("#certify1").removeClass('no-border-bottom color-1f7ed0').addClass('no-border-top no-border-right no-border-left');
            $('#certify2').removeClass('no-border-top no-border-right no-border-left').addClass('no-border-bottom color-1f7ed0');
        })
    })
</script>