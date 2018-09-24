<?php
/**
 * User: F1677929
 * Date: 2017/11/30
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
$this->title='核价资料列表';
$this->params['homeLike']=['label'=>'商品开发管理'];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <?=$this->render('_search')?>
    <?=$this->render('_action')?>
    <div id="datagrid1" style="width:100%;"></div>
</div>
<script>
    //终止
    function stopFun(id){
        $.fancybox({
            href:"<?=Url::to(['stop'])?>?id="+id,
            type:"iframe",
            padding:0,
            autoSize:false,
            width:430,
            height:300
        });
    }

    $(function(){
        $("#datagrid1").datagrid({
            url:"<?=Url::to(['index'])?>",
            queryParams:{"flag":"a"},
            rownumbers:true,
            method:"get",
            singleSelect:true,
            pagination:true,
            columns:[[
                {field:"part_no",title:"料号",width:150},
                {field:"pdt_name",title:"品名",width:150},
                {field:"supplier_code",title:"供应商代码",width:150},
                {field:"supplier_name",title:"供应商名称",width:150},
                {field:"payment_terms",title:"付款条件",width:80},
                {field:"trading_terms",title:"交货条件",width:60},
                {field:"currency",title:"交易币别",width:60},
                {field:"effective_date",title:"生效日期",width:80},
                {field:"expiration_date",title:"有效期至",width:80},
                {field:"num_area",title:"量价区间",width:150},
                {field:"buy_price",title:"采购价",width:100},
                {field:"remarks",title:"备注",width:200},
                {field:"status",title:"状态",width:60},
                {field:"pk_id",title:"操作",width:60,formatter:function(value,rowData){
                    var str="<i>";
                    if(rowData.status=="待提交" || rowData.status=="驳回"){
                        str+="<a class='icon-edit icon-large' title='修改' onclick='event.stopPropagation();location.href=\"<?=Url::to(['edit'])?>?id="+value+"\"'></a>";
                    }
                    if(rowData.status=="审核完成"){
                        str+="<a class='icon-minus-sign icon-large' title='终止' onclick='event.stopPropagation();stopFun("+value+");'></a>";
                    }
                    str+="</i>";
                    return str;
                }}
            ]],
            onLoadSuccess:function(data){
                datagridTip("#datagrid1");
                showEmpty($(this),data.total,0);
                setMenuHeight();
                $("#edit_btn").hide().next().hide();
                $("#check_btn").hide().next().hide();
                $("#stop_btn").hide().next().hide();
                autoRowSpan();
            },
            onSelect:function(index,row){
                $("#edit_btn").hide().next().hide();
                $("#check_btn").hide().next().hide();
                $("#stop_btn").hide().next().hide();
                if(row.status=="审核完成"){
                    $("#stop_btn").show().next().show();
                }
                if(row.status=="待提交" || row.status=="驳回"){
                    $("#edit_btn").show().next().show();
                    $("#check_btn").show().next().show();
                }
            }
        });

        //datagrid合并
        function autoRowSpan(){
            var colNum1=2;
            var colNum2=5;
            var colNum3=9;
            var val1="";
            var val2="";
            var val3="";
            var $oneLevel;
            var $twoLevel;
            var $threeLevel;
            $(".datagrid-btable:last tr").each(function(){
                if($(this).find("td:first div").text()==val1){
                    //一级合并
                    var rowspan1=$oneLevel.find("td:first").attr("rowspan");
                    if(rowspan1==undefined){
                        rowspan1=1;
                    }else{
                        rowspan1=parseFloat(rowspan1);
                    }
                    $oneLevel.find("td:lt("+colNum1+")").attr("rowspan",rowspan1+1);
                    $(this).find("td:lt("+colNum1+")").hide();

                    /*----------特殊处理最后两列开始----------*/
                    $oneLevel.find("td:gt(-3)").attr("rowspan",rowspan1+1);
                    $(this).find("td:gt(-3)").hide();
                    /*----------特殊处理最后两列结束----------*/

                    //二级合并
                    if($(this).find("td:eq("+colNum1+") div").text()==val2){
                        var rowspan2=$twoLevel.find("td:eq("+colNum1+")").attr("rowspan");
                        if(rowspan2==undefined){
                            rowspan2=1;
                        }else{
                            rowspan2=parseFloat(rowspan2);
                        }
                        $twoLevel.find("td:lt("+colNum2+"):gt("+(colNum1-1)+")").attr("rowspan",rowspan2+1);
                        $(this).find("td:lt("+colNum2+")").hide();
                        //三级合并
                        if($(this).find("td:eq("+colNum2+") div").text()==val3){
                            var rowspan3=$threeLevel.find("td:eq("+colNum2+")").attr("rowspan");
                            if(rowspan3==undefined){
                                rowspan3=1;
                            }else{
                                rowspan3=parseFloat(rowspan3);
                            }
                            $threeLevel.find("td:lt("+colNum3+"):gt("+(colNum2-1)+")").attr("rowspan",rowspan3+1);
                            $(this).find("td:lt("+colNum3+")").hide();
                        }else{
                            $threeLevel=$(this);
                            val3=$(this).find("td:eq("+colNum2+") div").text();
                        }
                    }else{
                        $twoLevel=$(this);
                        $threeLevel=$(this);
                        val2=$(this).find("td:eq("+colNum1+") div").text();
                        val3=$(this).find("td:eq("+colNum2+") div").text();
                    }
                }else{
                    $oneLevel=$(this);
                    $twoLevel=$(this);
                    $threeLevel=$(this);
                    val1=$(this).find("td:first div").text();
                    val2=$(this).find("td:eq("+colNum1+") div").text();
                    val3=$(this).find("td:eq("+colNum2+") div").text();
                }
            });
        }
    })
</script>