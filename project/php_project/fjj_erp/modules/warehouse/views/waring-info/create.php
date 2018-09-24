<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/6/26
 * Time: 上午 10:11
 */
use yii\helpers\Url;

$this->params['homeLike'] = ['label' => '仓储物流管理'];
$this->params['breadcrumbs'][] = ['label' => '仓库预警信息查询', 'url' => 'index'];
$this->params['breadcrumbs'][] = ['label' => '新增库存预警申请'];
$this->title = '新增库存预警信息';
?>


<div class="content">

    <h1 class="head-first">
        新增库存预警申请
    </h1>
    <div class="mb-30">
        <h2 class="head-second">
            库存预警设置
            <img src="../../img/icon/u992.png"
                 style="float: right;width: 20px;height: 20px;margin-top: 5px;margin-right: 10px;" id="delete"
                 title="删除商品">
            <img src="../../img/icon/u994.png" style="float: right;margin-right: 5px;margin-top: 5px;" id="add"
                 title="批量添加商品">
        </h2>
        <div id="wh">
            <input type="text" id="wh_id" style="display: none" value=""/>
        </div>
        <div style="width: 100%;overflow-x:auto ">
            <table class="product-list" STYLE="font-size:12PX;width: 1500px;">

                <tr>
                    <th style="width: 4%;">序号</th>
                    <th style="width: 3%"><input type="checkbox" id="ckAll" name="ckAll"></th>
                    <th style="width: 10%">仓库名称</th>
                    <th style="width: 10%">商品名称</th>
                    <th style="width: 15%">商品料号</th>
                    <th style="width: 8%;">规格型号</th>
                    <th style="width: 10%">库存下限</th>
                    <th style="width: 10%">安全库存</th>
                    <th style="width: 10%">现有库存</th>
                    <th style="width: 10%">库存上限</th>
                    <th style="width: 15%">备注</th>
                    <th style="width: 10%">操作</th>
                </tr>
                <tbody id="table_body">
                </tbody>
            </table>
        </div>
    </div>
    <div class="text-center" style="margin-top: 10px;">
        <input style="display: none" id="biw_h_pkid" value="">
        <button class="button-blue-big" type="button" id="save">保存</button>
        <button class="button-blue-big ml-20 close" type="button" id="submit" style="margin-left: 40px;">提交</button>
        <button class="button-white-big ml-20 close" type="button" id="back">取消</button>
    </div>


    <script>
        $(function () {
            $("#save").attr("disabled", true);
            $("#submit").attr("disabled", true);
        })
        function inputOnkey(obj){
            if(obj.value.length==1){obj.value=obj.value.replace(/[^1-9]/g,'')}else{obj.value=obj.value.replace(/\D/g,'')}
        }
        //添加
        $("#add").on("click", function () {
            $.fancybox({
                type: "iframe",
                width: 800,
                height: 500,
                autoSize: false,
                href: "<?=Url::to(['add'])?>",
                padding: 0
            });
        });
        $("#ckAll").on("click", function () {
            $('input:checkbox[name=chk]').each(function () {
                $(this).prop("checked", $("input[name='ckAll']").prop("checked"));
            });
        });
        $("#save").click(function () {
            if (check()) {
                var s = save("update");
                if (s.status == 1) {
                    layer.alert(s.msg, {icon: 2});
                    window.location = "view?biw_h_pkid=" + s.data;
                } else {
                    layer.alert(s.msg, {icon: 2});
                }
            }

        });

        $("#submit").on("click", function () {
            if (check()) {
                var wh_id = $("#table_body").children("tr").first().find('td').eq(2).attr('data');//仓库id
                var down_nums = new Array();//库存下限
                var part_no1 = new Array();//料号
                var save_nums = new Array();//安全库存
                var up_nums = new Array();//库存上限
                var remarks = new Array();//备注
                var trList = $("#table_body").children("tr");
                for (var i = 0; i < trList.length; i++) {
                    var tdArr = trList.eq(i).find("td");
                    part_no1[i] = tdArr[4].firstChild.textContent;//料号
                    down_nums[i] = tdArr.eq(6).find("input").val();//库存下限
                    save_nums[i] = tdArr.eq(7).find("input").val();//安全庫存
                    up_nums[i] = tdArr.eq(9).find("input").val();//库存上限
                    remarks[i] = tdArr.eq(10).find("input").val();//備註
                }
                $.ajax({
                    type:"post",
                    url:"<?=Url::to(['createsave'])?>",
                    data:{
                        wh_id: wh_id,
                        part_no1: part_no1,
                        down_nums: down_nums,
                        save_num: save_nums,
                        up_nums: up_nums,
                        remarks: remarks,
                        action:1
                    },
                    success:function (data) {
                        var data=eval('(' + data + ')');
                        if(data.status==1)
                        {
                            var type=<?=$typeId?>;
                            var id=data.data;
                            var url="<?=Url::to(['view'])?>?biw_h_pkid=" + id;
                            $.fancybox({
                                href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
                                type:"iframe",
                                padding:0,
                                autoSize:false,
                                width:750,
                                height:480,
                                afterClose:function(){
                                    location.href="<?=Url::to(['view'])?>?biw_h_pkid="+id;
                                }
                            });
                        }

                    }
                })

                var id = $("#biw_h_pkid").val();
                var url = "<?=Url::to(['view'])?>?biw_h_pkid=" + id;
                var type = 20;
                $.fancybox({
                    href: "<?=Url::to(['new-reviewer'])?>?id=" + id + "&url=" + url + "&arrayNum=" + arrayNum,
                    type: "iframe",
                    padding: 0,
                    autoSize: false,
                    width: 750,
                    height: 480
                });
            }
        });
        $("#back").on("click", function () {
            window.location.href = "<?=Url::to(['index'])?>";
        });

        //删除
        function deleteAccompanyPerson(obj) {
            $(obj).parents("tr").remove();
        }

        //删除选中tr
        $("#delete").on("click", function () {
            $('input:checkbox[name=chk]').each(function () {
                if (this.checked) {
                    $(this).parents("tr").remove();
                }
            });
        });

        //重置
        function resetAccompanyPerson(obj) {
            $(obj).parents("tr").find("input").val("");
        }

        function save(action) {
            var tf = {};
            var wh_id = $("#table_body").children("tr").first().find('td').eq(2).attr('data');//仓库id
            var down_nums = new Array();//库存下限
            var part_no1 = new Array();//料号
            var save_nums = new Array();//安全库存
            var up_nums = new Array();//库存上限
            var remarks = new Array();//备注
            var trList = $("#table_body").children("tr");
            for (var i = 0; i < trList.length; i++) {
                var tdArr = trList.eq(i).find("td");
                part_no1[i] = tdArr[4].firstChild.textContent;//料号
                down_nums[i] = tdArr.eq(6).find("input").val();//库存下限
                save_nums[i] = tdArr.eq(7).find("input").val();//安全庫存
                up_nums[i] = tdArr.eq(9).find("input").val();//库存上限
                remarks[i] = tdArr.eq(10).find("input").val();//備註
            }
            $.ajax({
                type: 'POST',
                url: "<?= Url::to(["createsave"])?>",
                async: false,
                data: {
                    wh_id: wh_id,
                    part_no1: part_no1,
                    down_nums: down_nums,
                    save_num: save_nums,
                    up_nums: up_nums,
                    action: 0,
                    remarks: remarks
                },
                success: function (data) {
                    var data = eval('(' + data + ')');
                    if (data.status == 0) {
                        tf = {"status": "0", "msg": data.msg};
                    } else {
//                        $("#biw_h_pkid").val(data);
                        tf = {"status": "1", "msg": "保存成功","data":data.data};
                    }


                },
                error: function (xhr, type) {
                    tf = {"status": 0, "msg": xhr.error};
                }
            });
            return tf;

        }

        //查询该仓库有没有在审核中的预警
        function getTypeByWhID(wh_id) {
            var tf = false
            $.ajax({
                type: 'POST',
                url: "<?= Url::to(["gettypebywhid"])?>",
                async: false,
                data: {
                    wh_id: wh_id
                },
                success: function (data) {
                    tf = data;
                },
                error: function (xhr, type) {
                    alert("出现异常!");
                }
            });
            return tf;
        }

        function check() {
            var wh_id = $("#wh_id").val();//仓库id
            var down_nums = new Array();//库存下限
            var part_no1 = new Array();//料号
            var save_nums = new Array();//安全库存
            var up_nums = new Array();//库存上限
            var remarks = new Array();//备注
            var trList = $("#table_body").children("tr");
            for (var i = 0; i < trList.length; i++) {
                var j=i+1;
                var tdArr = trList.eq(i).find("td");
                part_no1[i] = tdArr[4].firstChild.textContent;//料号
                down_nums[i] = tdArr.eq(6).find("input").val();//库存下限
                save_nums[i] = tdArr.eq(7).find("input").val();//安全库存
                up_nums[i] = tdArr.eq(9).find("input").val();//库存上限
                remarks[i] = tdArr.eq(10).find("input").val();//备注
                if (down_nums[i] == "" || down_nums[i] == null) {
                    layer.alert("第" + j + "行库存下限不能为空!", {icon: 2});
                    return false;
                    break;
                }
                if (save_nums[i] == "" || save_nums[i] == null) {
                    layer.alert("第" + j + "行安全库存不能为空!", {icon: 2});
                    return false;
                    break;
                }
                if (up_nums[i] == "" || up_nums[i] == null) {
                    layer.alert("第" + j + "行库存上限不能为空!", {icon: 2});
                    return false;
                    break;
                }
                var j = i + 1;
                if (parseFloat(down_nums[i]) > parseFloat(save_nums[i])) {
                    layer.alert("第" + j + "行的安全库存必须大于库存下限!", {icon: 2});
                    return false;
                    break;
                }
                if (parseFloat(save_nums[i]) > parseFloat(up_nums[i])) {

                    layer.alert("第" + j + "行的库存上限必须大于安全库存!", {icon: 2});
                    return false;
                    break;
                }
            }
            return true;
        }
    </script>

