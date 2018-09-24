<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\classes\Menu;
$this->title='活动统计列表';
$this->params['homeLike']=['label'=>'客户关系管理','url'=>Url::to(['/index/index'])];
$this->params['breadcrumbs'][]=$this->title;
?>
<style>
    #deal_child .datagrid-header{
        height: 52px !important;
    }
    #deal_child .datagrid-htable{
        height: 54px !important;
    }
</style>
<div class="content">
    <?=$this->render('_search',['data'=>$data]);?>
    <div class="table-head" style="margin-bottom:5px;">
        <p>线上/线下活动信息</p>
        <ul class="datagrid-menu">
            <?=Menu::isAction('/crm/crm-active-count/add')?"<li id='add_btn'><a>新增</a></li>":""?>
            <?=Menu::isAction('/crm/crm-active-count/edit')?"<li id='edit_btn'><a>修改</a></li>":""?>
            <?=Menu::isAction('/crm/crm-active-count/view')?"<li id='view_btn'><a>详情</a></li>":""?>
            <?=Menu::isAction('/crm/crm-active-count/delete')?"<li id='delete_btn'><a>删除</a></li>":""?>
            <li><a href="<?=Url::to(['/index/index'])?>">返回</a></li>
        </ul>
    </div>
    <div id="count_main" style="width:100%;"></div>
    <div id="count_child_title"></div>
    <div id="deal_child">
        <div id="count_child" style="width:100%;"></div>
    </div>
</div>
<script>
    $(function(){
        //jquery变量
        var $countMain=$("#count_main");
        var $countChildTitle=$("#count_child_title");
        var $countChild=$("#count_child");
        var flag='';//记录子表选中标志

        //datagrid表格
        $countMain.datagrid({
            url:"<?=Yii::$app->request->getHostInfo().Yii::$app->request->url;?>",
            rownumbers:true,
            method:"get",
            idField:"actch_id",
            singleSelect:true,
            pagination:true,
            columns:[[
                <?=$data['mainTable']?>
            ]],
            onLoadSuccess:function(data){
                datagridTip($countMain);
                showEmpty($(this),data.total,0);
                setMenuHeight();
            },
            onSelect:function(index,row){
                $("#count_child_title").show().next().show();
                $countChildTitle.addClass("table-head mb-5 mt-20").html("<p>活动统计信息</p>");
                var columns=[];
                if(row.activeWay=='线上'){
                    columns=[[
                        {field:"actc_datetime",title:"统计时间",width:115,rowspan:2,formatter:function(value){
                            return value?value.substr(0,16):'';
                        }},
                        {title:'活动推广',colspan:5},
                        {title:'活动效果',colspan:6},
                        {field:"actc_cost",title:"活动成本",width:50,rowspan:2},
                        {field:"actc_roi",title:"ROI",width:50,rowspan:2},
                        {field:"actc_remark",title:"备注",width:150,rowspan:2}
                    ],[
                        {field:"actc_SEM",title:"SEM",width:50},
                        {field:"mediaType",title:"媒体",width:50},
                        {field:"actc_vqty",title:"大V",width:50},
                        {field:"actc_watch",title:"微信",width:100},
                        {field:"actc_emailqty",title:"邮件",width:150},
                        {field:"actc_UV",title:"UV",width:50},
                        {field:"actc_PV",title:"PV",width:50},
                        {field:"actc_memqty",title:"会员注册数",width:70},
                        {field:"actc_custqty",title:"成交客户",width:60},
                        {field:"actc_ordersqty",title:"订单数量",width:60},
                        {field:"actc_ordcountqyt",title:"订单总额",width:60}
                    ]];
                }
                if(row.activeWay=='线下'){
                    columns=[[
                        {field:"actc_datetime",title:"统计时间",width:115,rowspan:2,formatter:function(value){
                            return value?value.substr(0,16):'';
                        }},
                        {field:"actc_extent",title:"活动规模(参加人数)",width:70,rowspan:2},
                        {field:"actc_peopleqty",title:"参与人次",width:70,rowspan:2},
                        {field:"actc_dwqty",title:"发放DW/宣传册数",width:70,rowspan:2},
                        {title:'发放礼品数',colspan:3},
                        {field:"actc_travelqty",title:"差旅费用",width:70,rowspan:2},
                        {field:"actc_countqty",title:"总费用",width:70,rowspan:2},
                        {field:"actc_memqty",title:"获得会员数",width:70,rowspan:2},
                        {field:"actc_cpa",title:"CPA",width:70,rowspan:2},
                        {field:"actc_artqty",title:"收集名片数",width:70,rowspan:2},
                        {field:"actc_watqyt",title:"当天微信关注数",width:70,rowspan:2},
                        {field:"actc_bUV",title:"活动前一工作日UV",width:70,rowspan:2},
                        {field:"actc_UV",title:"当天UV数",width:70,rowspan:2},
                        {field:"actc_UVadd",title:"UV增量",width:70,rowspan:2}
                    ],[
                        {field:"actc_sjkqty",title:"手机壳",width:50},
                        {field:"actc_boxqty",title:"名片盒",width:50},
                        {field:"actc_ldqty",title:"领带",width:50}
                    ]];
                }
                $countChild.datagrid({
                    url:"<?=Url::to(['load-count'])?>",
                    queryParams:{"mainId":row.actch_id},
                    rownumbers:true,
                    method:"get",
                    idField:"actc_iid",
                    singleSelect:true,
//                    pagination:true,
//                    showFooter:row.activeWay=='线下',
                    columns:columns,
                    onLoadSuccess:function(data){
                        setMenuHeight();
                        datagridTip($countChild);
                        console.log(data);
                        console.log(row);
                        if(data.rows.length>0&&row.activeWay=='线下'){
                            $countChild.datagrid('appendRow',data.footer[0]);
                        }
                    },
                    onSelect:function(index,row) {
                        if(row.actc_iid==flag) {
                            $countChild.datagrid('clearSelections');
                            flag='';
                        }else{
                            flag=row.actc_iid;
                        }
                    }
                });
            }
        });

        //新增
        $('#add_btn').click(function(){
            var mainObj=$countMain.datagrid('getSelected');
            if(mainObj==null){
                location.href="<?=Url::to(['add'])?>";
            }else{
                location.href="<?=Url::to(['add'])?>?nameId="+mainObj.actbs_id;
            }
        });

        //修改
        $("#edit_btn").click(function(){
            var mainObj=$countMain.datagrid('getSelected');
            if(mainObj==null){
                layer.alert('请选择活动信息！',{icon:2,time:5000});
                return false;
            }
            var childObj=$countChild.datagrid('getSelected');
            if(childObj==null){
                layer.alert('请选择统计信息！',{icon:2,time:5000});
                return false;
            }
            location.href="<?=Url::to(['edit'])?>?childId="+childObj.actc_iid;
        });

        //查看
//        $("#view_btn").click(function(){
//            var mainObj=$countMain.datagrid('getSelected');
//            if(mainObj==null){
//                layer.alert('请选择活动信息！',{icon:2,time:5000});
//                return false;
//            }
//            location.href="<?//=Url::to(['view'])?>//?mainId="+mainObj.actch_id;
//        });
        $("#view_btn").click(function(){
            var mainObj=$countMain.datagrid('getSelected');
            if(mainObj==null){
                layer.alert('请选择活动信息！',{icon:2,time:5000});
                return false;
            }
            var childObj=$countChild.datagrid('getSelected');
            if(childObj==null){
                location.href="<?=Url::to(['view-counts'])?>?mainId="+mainObj.actch_id;
                return false;
            }
            location.href="<?=Url::to(['view-count'])?>?childId="+childObj.actc_iid;
        });

        //删除
        $("#delete_btn").click(function(){
            var mainObj=$countMain.datagrid('getSelected');
            if(mainObj==null){
                layer.alert('请选择活动信息！',{icon:2,time:5000});
                return false;
            }
            var childObj=$countChild.datagrid('getSelected');
            if(childObj!=null){
                layer.confirm('确定删除吗？',{icon:2},
                    function(){
                        $.ajax({
                            url:"<?=Url::to(['delete-child'])?>",
                            data:{"childId":childObj.actc_iid},
                            dataType:"json",
                            success:function(data){
                                if(data.flag==1){
                                    if(data.last==1){
                                        layer.alert(data.msg,{icon:1},function(){
                                            $countMain.datagrid('reload').datagrid('clearSelections');
                                            layer.closeAll();
                                            $countChildTitle.hide();
                                            $countChildTitle.next().hide();
                                        });
                                    }else{
                                        layer.alert(data.msg,{icon:1},function(){
                                            $countChild.datagrid('reload').datagrid('clearSelections');
                                            layer.closeAll();
                                        });
                                    }
                                }else{
                                    layer.alert(data.msg,{icon:2},function(){
                                        layer.closeAll();
                                    });
                                }
                            }
                        });
                    },
                    function(){
                        layer.closeAll();
                    }
                );
                return false;
            }
            if("<?=Yii::$app->user->identity->is_supper?>"=="1"){
                layer.confirm('确定删除吗？',{icon:2},
                    function(){
                        $.ajax({
                            url:"<?=Url::to(['delete-main'])?>",
                            data:{"mainId":mainObj.actch_id},
                            dataType:"json",
                            success:function(data){
                                if(data.flag==1){
                                    layer.alert(data.msg,{icon:1},function(){
                                        $countMain.datagrid('reload').datagrid('clearSelections');
                                        layer.closeAll();
                                        $countChildTitle.hide();
                                        $countChildTitle.next().hide();
                                    });
                                }else{
                                    layer.alert(data.msg,{icon:2},function(){
                                        layer.closeAll();
                                    });
                                }
                            }
                        });
                    },
                    function(){
                        layer.closeAll();
                    }
                );
                return false;
            }
            layer.alert('请选择统计信息！',{icon:2,time:5000});
        });
    })
</script>