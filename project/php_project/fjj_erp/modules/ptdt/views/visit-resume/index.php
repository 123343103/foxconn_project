<?php
/**
 * User: F1677929
 * Date: 2016/11/11
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\classes\Menu;
use yii\helpers\Html;
$this->title='厂商拜访履历列表';
$this->params['homeLike'] = ['label'=>'商品开发管理','url'=>''];
$this->params['breadcrumbs'][] = ['label' => '商品开发进程', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '厂商拜访履历', 'url' => ""];
?>
<div class="content">
    <?=$this->render('_search',[
        'params'=>$params,
        'firmType'=>$data['firmType'],
        'oneCategory'=>$data['oneCategory'],
        'visitStatus'=>$data['visitStatus'],
    ])?>
    <div class="table-head" style="margin-bottom:5px;">
        <p class="head">厂商拜访信息列表</p>
        <p class="float-right">
            <?=Menu::isAction('/ptdt/visit-resume/add')?Html::a("<span class='ml--5'>新增</span>",null,['id'=>'add_btn']):''?>
            <?=Menu::isAction('/ptdt/visit-resume/edit')?Html::a("<span class='ml--5'>修改</span>",null,['id'=>'edit_btn']):''?>
            <?=Menu::isAction('/ptdt/visit-resume/view')?Html::a("<span class='ml--5'>详情</span>",null,['id'=>'view_btn']):''?>
            <?=Menu::isAction('/ptdt/visit-resume/delete')?Html::a("<span class='ml--5'>刪除</span>",null,['id'=>'delete_btn']):''?>
            <?=Menu::isAction('/ptdt/visit-resume/visit-complete')?Html::a("<span class='ml--5 width-80'>拜訪完成</span>",null,['id'=>'visit_complete_btn']):''?>
            <?=Menu::isAction('/ptdt/visit-resume/add-negotiation')?Html::a("<span class='ml--5 width-80'>新增谈判</span>",null,['id'=>'add_negotiation_btn']):''?>
            <?=Html::a("<span class='ml--5'>返回</span>",Url::to(['/index/index']))?>
        </p>
    </div>
    <div id="resume_main" style="width:100%;"></div>
    <div id="resume_child_title"></div>
    <div id="resume_child" style="width:100%;"></div>
</div>
<script>
    $(function(){
        var flag='';//履历子表选中标志
        $("#resume_main").datagrid({
            url:"<?=Yii::$app->request->getHostInfo().Yii::$app->request->url?>",
            rownumbers:true,
            method:"get",
            idField:"vih_id",
            singleSelect:true,
            pagination:true,
            pageSize:10,
            pageList:[10,20,30],
            columns:[[
                <?=$data['mainTable']?>
            ]],
            onLoadSuccess:function(data){
                showEmpty($(this),data.total,0);
                setMenuHeight();
                datagridTip("#resume_main");
            },
            onSelect:function(rowIndex,rowData){
                if(rowData.vih_status == '20'){
                    $("#visit_complete_btn").hide();
                }else{
                    $("#visit_complete_btn").show();
                }
                $("#resume_child_title").addClass("table-head mb-5 mt-20").html("<p class='head'>厂商拜访履历表</p>");
                $("#resume_child").datagrid({
                    url:"<?=Yii::$app->request->getHostInfo().Url::to(['load-resume']) ?>?mainId="+rowData.vih_id,
                    rownumbers:true,
                    method:"get",
                    idField:"vil_id",
                    singleSelect:true,
                    pagination:true,
                    pageSize:10,
                    pageList:[10,20,30],
                    columns:[[
                        <?=$data['childTable']?>
                    ]],
                    onLoadSuccess:function(data){
                        showEmpty($(this),data.total,0);
                        setMenuHeight();
                        $("#resume_child").datagrid('clearSelections');
                        datagridTip("#resume_child");
                    },
                    onSelect:function(rowIndex,rowData){
                        if(rowData.vil_id==flag){
                            $("#resume_child").datagrid('clearSelections');
                            flag='';
                        }else{
                            flag=rowData.vil_id;
                        }
                    }
                });
            }
        });

        //新增拜访履历
        $("#add_btn").click(function(){
            var mainObj=$("#resume_main").datagrid('getSelected');
            if(mainObj==null){
                window.location.href="<?=Url::to(['add'])?>";
            }else{
                window.location.href="<?=Url::to(['add'])?>?firmId="+mainObj.firm_id;
            }
        });

        //修改拜访履历
        $("#edit_btn").click(function(){
            var mainObj=$("#resume_main").datagrid('getSelected');
            if(mainObj==null){
                layer.alert("请选择厂商！",{icon:2,time:5000});
                return false;
            }
            if(mainObj.visitStatus=='拜访完成'){
                layer.alert("该厂商已拜访完成，不可修改！",{icon:2,time:5000});
                return false;
            }
            var childObj=$("#resume_child").datagrid('getSelected');
            if(childObj==null){
                layer.alert("请选择履历！",{icon:2,time:5000});
                return false;
            }
            var currPage=$("#resume_child").datagrid('getPager').data('pagination').options.pageNumber;//当前页
            if(currPage!=1){
                layer.alert("该履历不是最新履历，不可修改！",{icon:2,time:5000});
                return false;
            }
            var newRow=$("#resume_child").datagrid('getRowIndex',childObj);//最新的一条履历
            if(newRow!=0){
                layer.alert("该履历不是最新履历，不可修改！",{icon:2,time:5000});
                return false;
            }
            window.location.href="<?=Url::to(['edit'])?>?childId="+childObj.vil_id;
        });

        //查看拜访履历
        $("#view_btn").click(function(){
            var mainObj=$("#resume_main").datagrid('getSelected');
            if(mainObj==null){
                layer.alert("请选择厂商！",{icon:2,time:5000});
                return false;
            }
            var childObj=$("#resume_child").datagrid('getSelected');
            if(childObj==null){
                window.location.href="<?=Url::to(['view-resumes'])?>?mainId="+mainObj.vih_id;
                return false;
            }
            window.location.href="<?=Url::to(['view-resume'])?>?childId="+childObj.vil_id;
        });

        //删除拜访履历
        $("#delete_btn").click(function(){
            var mainObj=$("#resume_main").datagrid('getSelected');
            if(mainObj==null){
                layer.alert("请选择厂商！",{icon:2,time:5000});
                return false;
            }
            var childObj=$("#resume_child").datagrid('getSelected');
            if(childObj==null){
                layer.alert("请选择履历！",{icon:2,time:5000});
                return false;
            }
            layer.confirm("确定删除吗?",{icon:2},
                function(){
                    $.ajax({
                        url:"<?=Url::to(['delete-child'])?>",
                        data:{"childId":childObj.vil_id},
                        dataType:"json",
                        success:function(data){
                            if(data.flag==1){
                                layer.alert(data.msg,{icon:1},function(){
                                    layer.closeAll();
                                    if(data.total==1){
                                        $("#resume_main").datagrid('reload').datagrid('clearSelections');
                                    }
                                    $("#resume_child").datagrid('reload').datagrid('clearSelections');
                                });
                            }else{
                                layer.alert(data.msg,{icon:2});
                            }
                        }
                    })
                },
                layer.closeAll()
            );
        });

        //拜访完成
        $("#visit_complete_btn").click(function(){
            var mainObj=$("#resume_main").datagrid('getSelected');
            if(mainObj==null){
                layer.alert("请选择厂商！",{icon:2,time:5000});
                return false;
            }
            if(mainObj.visitStatus=='拜访完成'){
                layer.alert("该厂商已拜访完成！",{icon:2,time:5000});
                return false;
            }
            layer.confirm('确定执行拜访完成吗？',{icon:2},
                function(){
                    $.ajax({
                        url:"<?=Url::to(['visit-complete'])?>",
                        data:{"mainId":mainObj.vih_id},
                        dataType:'json',
                        success:function(data){
                            if(data.flag==1){
                                layer.alert(data.msg,{icon:1},function(){
                                    layer.closeAll();
                                    $("#resume_main").datagrid('reload');
                                });
                            }else{
                                layer.alert(data.msg,{icon:2});
                            }
                        }
                    })
                },
                layer.closeAll()
            );
        });

        //新增谈判
        $("#add_negotiation_btn").click(function(){
            var mainObj=$("#resume_main").datagrid('getSelected');
            if(mainObj==null){
                window.location.href="<?=Url::to(['/ptdt/firm-negotiation/create'])?>";
                return false;
            }
            window.location.href="<?=Url::to(['/ptdt/firm-negotiation/create'])?>?firmId="+mainObj.firm_id;
        });
    })
</script>