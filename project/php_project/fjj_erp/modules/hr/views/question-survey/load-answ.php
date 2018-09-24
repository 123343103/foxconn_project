<?php
/**
 * Created by PhpStorm.
 * User: F1669561
 * Date: 2017/10/28
 * Time: 下午 02:03
 */
use app\assets\JqueryUIAsset;  //ajax引用jQuery样式
JqueryUIAsset::register($this);
?>
<style>
    .content{position: relative}
    ._list{width: 600px; font-size: 12px;color: #fff;table-layout: fixed}
    ._list_th{background: #1f7ed0}
    ._list td{white-space: nowrap;overflow: hidden;text-overflow: ellipsis;}
    .m-20{position: absolute;
        left: 60px;
        bottom: 30px;}
</style>

<div class="content" style="padding: 0px;">
    <h1 class="head-first">
        答案详情
    </h1>
    <span>
        <?php echo $c?>.<?php echo $d?>
    </span>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data">
            <table class="_list">
                <thead class="_list_th">
                    <th style="height: 35px;color: #fff;width: 56px;">序号</th>
                    <th style="color: #fff;width: 100px;">工号</th>
                    <th style="color: #fff;width: 100px;">姓名</th>
                    <th style="color: #fff;width: 320px;">回答</th>
                </thead>
                <?php for ($i=0;$i<count($model['rows']);$i++){;?>
                <tbody>
                    <tr style="height:35px;">
                        <td style="text-align: center"><?php echo $i+1?></td>
                        <td style="text-align: center"><?php echo $model['rows'][$i]['staff_code'] ?></td>
                        <td style="text-align: center"><?php echo $model['rows'][$i]['staff_name'] ?></td>
                        <td><?php echo $model['rows'][$i]['answs'] ?></td>
                    </tr>
                </tbody>
                <?php }?>
            </table>

        </div>

    </div>
</div>
<div class="m-20" style="margin-bottom: 10px;position: absolute">
    <!--            <span class="_len" ></span>-->
    <a   id="pageSizeSet"></a>&nbsp;
    <a   id="btn0"></a>
    <a   id="btn1">首页</a>
    <a   id="btn2">上一页</a>
    <a   id="btn3">下一页</a>
    <a   id="btn4">尾页</a>&nbsp;
    <a>转到&nbsp;</a>
    <input id="changePage" type="text" size="1" maxlength="4"/>
    <a>页&nbsp;</a>
    <a  id="btn5">确定</a>
</div>
<script>
    var pageSize=5;
    var current=0;
    var lastpage;
    var direct=0;
    var len;
    var page;
    var begin;
    var end;
    $(function () {
        len=$("._list tr").length-1;
        page=len % pageSize==0 ? len/pageSize :Math.floor(len/pageSize)+1;
        current=1;
//        $("._len").text("记录条数："+len+"条==当前"+ current +"/"+ page +"页   每页"+pageSize+"条");
        document.getElementById("btn0").innerHTML="记录条数："+len+"条   当前 " + current + "/" + page + " 页  " ;
        _pageload();
        $("#btn1").click(function firstPage(){
            current=1;
            direct = 0;
            displayPage();
        });
        $("#btn2").click(function frontPage(){    // 上一页
          direct=-1;
          displayPage();
        });
        $("#btn3").click(function nextPage(){    // 下一页
          direct=1;
          displayPage();
        });
        $("#btn4").click(function lastPage(){    // 尾页
            current=page;
            direct = 0;
            displayPage();
        });
        $("#btn5").click(function changePage(){    // 转页
            current=document.getElementById("changePage").value * 1;
            if (!/^[1-9]\d*$/.test(current)) {
                    alert("请输入正整数");
                    return ;
                }
            if (current > page) {
                    alert("超出数据页面");
                    return ;
                }
            direct = 0;
            displayPage();
        });
        $("#pageSizeSet").click(function setPageSize(){    // 设置每页显示多少条记录
            pageSize = document.getElementById("pageSize").value;    //每页显示的记录条数
            if (!/^[1-9]\d*$/.test(pageSize)) {
                    alert("请输入正整数");
                    return ;
                }
            len =$("._list tr").length - 1;
            page=len % pageSize==0 ? len/pageSize : Math.floor(len/pageSize)+1;//根据记录条数，计算页数
            current=1;        //当前页
             direct=0;        //方向
             firstPage();
        });
    });
    function displayPage(){
         if(current <=1 && direct==-1){
                 direct=0;
                 alert("已经是第一页了");
                 return false;
             } else if (current >= page && direct==1) {
                 direct=0;
                 alert("已经是最后一页了");
                 return false;
             }
          lastpage = current;
         // 修复当len=1时，curPage计算得0的bug
         if (len > pageSize) {
             current = ((current + direct + len) % len);
             } else {
             current = 1;
             }
            document.getElementById("btn0").innerHTML="记录条数："+len+"条   当前 " + current + "/" + page + " 页   ";        // 显示当前多少页
            _pageload();

       }
       function _pageload() {
           begin=(current-1)*pageSize + 1;// 起始记录号
           end = begin + 1*pageSize - 1;    // 末尾记录号
           if(end > len ) end=len;
           $("._list tr").hide();    // 首先，设置这行为隐藏
           $("._list tr").each(function(i){    // 然后，通过条件判断决定本行是否恢复显示
               if((i>=begin && i<=end) || i==0 )//显示begin<=x<=end的记录
                   $(this).show();
               $("._list tr").css("border","#cccccc");
           });
       }
</script>
