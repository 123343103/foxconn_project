<?php
/**
 * User: F1677929
 * Date: 2016/10/27
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\classes\Menu;
use yii\helpers\Html;
$this->title='系统公共字典设置';
$this->params['homeLike']=['label'=>'系统平台设置'];
$this->params['breadcrumbs'][]='系统公用参数设置';
?>
<div class="content">
    <?=$this->render('_search',['params'=>$params])?>
    <div class="table-head" style="margin-bottom:5px;">
        <p>公共参数列表信息</p>
    </div>
    <div id="pub_data" style="width:100%;"></div>
</div>
<script>
    $(function(){
        $("#pub_data").datagrid({
            url:"<?=Yii::$app->request->getHostInfo().Yii::$app->request->url;?>",
            rownumbers:true,
            method:"get",
            idField:"bsp_id",
            pagination:true,
            singleSelect:true,
            pageSize:10,
            pageList:[10,20,30],
            columns:[[
                {field:"bsp_sname",title:"参数名称",width:495},
                {field:"bsp_stype",title:"参数类型",width:300,hidden:true},
                {field:"bsp_styp",title:"操作",width:440,formatter:function(value,row,index){
                    return '<a onclick="view(\''+ row.bsp_stype +'\')">查看</a>';
                }}
            ]],
            onLoadSuccess:function(data){
                showEmpty($(this),data.total,0);
                setMenuHeight();
            }
        });


    })
    //查看
    function view(data) {
//            $("#view_btn").click(function(){
//                var obj=$("#pub_data").datagrid('getSelected');
//                if(obj==null){
//                    layer.alert('请选择一条数据！',{icon:2,time:5000});
//                    return false;
//                }
        window.location.href="<?=Url::to(['view'])?>?val="+data;
//            });
    }
</script>