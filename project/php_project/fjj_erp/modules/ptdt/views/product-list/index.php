<?php
$this->params['homeLike'] = ['label' => '商品开发与管理'];
$this->params['breadcrumbs'][] = ['label' => '商品列表'];
$this->title = '商品列表';/*BUG修正 增加title*/
?>
<style type="text/css">
    #tab_content {
        width: 800px;
        height: 40px;
    }

    #tab_bar {
        width: 1000px;
        height: 30px;
        float: left;
    }
    #tab_bar ul {
        padding: 0px;
        margin: 0px;
        height: 20px;
        text-align: center;
    }
    #tab_bar li {
        margin-right: 5px;
        list-style-type: none;
        float: left;
        width: 75px;
        height: 25px;
        background-color:#c9c9c9;
        line-height: 25px;
    }

    #tab_bar li.active{
        background:#ddd;
    }

    .tab_css {
        width: 1000px;
        height: 400px;
        background-color: #c9c9c9;
        display: none;
        float: left;
    }

    .datagrid-row .datagrid-cell{
        white-space: normal;
    }
</style>
<div class="content">
    <?php echo $this->render('_search', [
        'search'=>$queryParam,
        'options'=>$options
    ]); ?>
    <div class="space-20"></div>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data">
            <div id="tab_content">
                <div id="tab_bar">
                    <ul style="float: left">
                        <li class="_tabli active" id="tab1">销售中商品</li>
                        <li class="_tabli" id="tab2">未上架商品</li>
                        <li class="_tabli" id="tab3">审核中商品</li>
                        <li class="_tabli" id="tab4">已下架商品</li>
                    </ul>
                    <?php echo $this->render('_action',['queryParam' => $queryParam]); ?>
                </div>
            </div>
            <div style="width:100%;" id="data-area"></div>
        </div>
        <div id="load-content_title"> </div>
        <div id="load-content" class="overflow-auto"></div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $("#data-area").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "prt_pkid",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            singleSelect: true,
            checkOnSelect: true,
            selectOnCheck: false,
            queryParams:{
                status:"selling"
            },
            columns:[[
                <?=$columns?>
                {field:"upshelf_person",title:'上架人员',width:60},
                {field:"upshelf_date",title:'上架时间',width:100,hidden:true},//销售中

                {field:"create_date",title:'创建时间',width:100,hidden:true},//未上架
                {field:"reject_reason",title:'驳回原因',width:100,hidden:true},//未上架
                {field:"check_date",title:'提交时间',width:100,hidden:true},//审核中
                {field:"downshelf_reason",title:'下架原因',width:100,hidden:true},//下架
                {field:"downshelf_date",title:'下架时间',width:100,hidden:true},//下架
                {field:"downshelf_attachment",title:'附件',width:100,hidden:true},//下架
                {field:"action",title:"操作",width:200,formatter:function(value,row,index){
                    if(params.status=="notupshelf"){//未上架
                        return "&nbsp;&nbsp;<a onclick='upshelf("+row.l_prt_pkid+")'>提交商品</a>&nbsp;&nbsp;<a onclick='modifyPrice("+row.prt_pkid+")'>修改价格</a>&nbsp;&nbsp;<a onclick='viewPrice("+row.prt_pkid+")'>查看价格</a>&nbsp;&nbsp;";
                    }

                    if(params.status=="selling"){//上架完成
                        if(row.part_status==4 && row.check_status!=2){
                            return "&nbsp;&nbsp;<a href='javascript:void(0)' style='color:gray;'>下架商品</a>&nbsp;&nbsp;<a href='javascript:void(0)' onclick='checkProgress("+row.l_prt_pkid+")'>审核中</a>&nbsp;&nbsp;<a onclick='viewPrice("+row.prt_pkid+")'>查看价格</a>&nbsp;&nbsp;";
                        }else{
                            return "&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"downshelf("+row.prt_pkid+",'"+row.part_no+"')\">下架商品</a>&nbsp;&nbsp;<a onclick='editPdt("+row.prt_pkid+")'>编辑商品</a>&nbsp;&nbsp;<a onclick='viewPrice("+row.prt_pkid+")'>查看价格</a>&nbsp;&nbsp;";
                        }
                    }

                    if(params.status=="downshelf"){//下架完成
                        return "&nbsp;&nbsp;<a onclick='redoUpshelf("+row.prt_pkid+")'>重新上架</a>&nbsp;&nbsp;<a onclick='modifyPrice("+row.prt_pkid+")'>修改价格</a>&nbsp;&nbsp;<a onclick='viewPrice("+row.prt_pkid+")'>查看价格</a>&nbsp;&nbsp;";
                    }

                    if(params.status=="checking"){//审核中
                        return "&nbsp;&nbsp;<a onclick='checkProgress("+row.l_prt_pkid+")' href='javascript:void(0)'>进度查看</a>&nbsp;&nbsp;<a onclick='viewPrice("+row.prt_pkid+")'>查看价格</a>&nbsp;&nbsp;";
                    }

                }}
            ]],
            onLoadSuccess:function(data){
                showEmpty($(this),data.total,0);
                datagridTip($("#data-area"));
                $(this).datagrid("autoMergeCells", ['pdt_img','pdt_name','pdt_no']);
                $("#data-area").datagrid("hideColumn","create_date");
                $("#data-area").datagrid("hideColumn","check_date");
                $("#data-area").datagrid("hideColumn","reject_reason");
                $("#data-area").datagrid("hideColumn","upshelf_date");
                $("#data-area").datagrid("hideColumn","downshelf_reason");
                $("#data-area").datagrid("hideColumn","downshelf_date");
                $("#data-area").datagrid("hideColumn","downshelf_attachment");

                if(params.status=="notupshelf"){//未上架
                    $("#data-area").datagrid("showColumn","create_date");
                    $("#data-area").datagrid("showColumn","reject_reason");
                }

                if(params.status=="selling"){//上架完成
                    $("#data-area").datagrid("showColumn","upshelf_date");
                }

                if(params.status=="downshelf"){//下架完成
                    $("#data-area").datagrid("showColumn","downshelf_reason");
                    $("#data-area").datagrid("showColumn","downshelf_date");
                    $("#data-area").datagrid("showColumn","downshelf_attachment");
                }

                if(params.status=="checking"){//审核中
                    $("#data-area").datagrid("showColumn","check_date");
                }

                $(".datagrid-view2 tr").each(function(index){
                    var h=$(this).outerHeight();
                    $(".datagrid-view1 tr").eq(index).css("height",h+"px");
                });
                $("#data-area").datagrid("resize");
            }
        });



        $("._tabli").click(function(){
            if($(this).hasClass("active")) return ;
            var index=$(this).index();
            $(this).addClass("active").siblings().removeClass("active");
            switch(index){
                case 0:
                    params.status="selling";
                    break;
                case 1:
                    params.status="notupshelf";
                    break;
                case 2:
                    params.status="checking";
                    break;
                case 3:
                    params.status="downshelf";
                    break;
            }
            $("#data-area").datagrid("load",params);
        });
    });


    //编辑商品
    function editPdt(id){
        window.location.href="<?=\yii\helpers\Url::to(['edit-partno'])?>?id="+id+"&status="+params.status;
    }
    //商品下架
    function downshelf(id,partno){
        $.fancybox({
            padding:[],
            autoSize: false,
            fitToView: false,
            height: 450,
            width: 540,
            closeClick: true,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= \yii\helpers\Url::to(['down-shelf'])?>?id="+id+"&partno="+partno
        });
    }

    //上架
    function upshelf(id){
        $.ajax({
            type:"get",
            url:"<?=\yii\helpers\Url::to(['get-bus-type','code'=>'pdtsel'])?>",
            success:function(data){
                var type = data;
                var url="<?=\yii\helpers\Url::to(['index'])?>";
                $.fancybox({
                    href: "<?=\yii\helpers\Url::to(['reviewer'])?>?type=" + type + "&id=" + id+"&url="+url,
                    type: "iframe",
                    padding: 0,
                    autoSize: false,
                    width: 750,
                    height: 480
                });
            }

        });
    }

    //重新上架
    function redoUpshelf(id){
        $.ajax({
            type:"get",
            url:"<?=\yii\helpers\Url::to(['get-bus-type','code'=>'pdtreupshelf'])?>",
            success:function(data){
                var type =data;
                $.ajax({
                    type:"get",
                    url:"<?=\yii\helpers\Url::to(['redo-upshelf'])?>?id="+id,
                    dataType:"json",
                    success:function(data){
                        var id=data.l_prt_pkid;
                        var url="<?=\yii\helpers\Url::to(['index'])?>";
                        $.fancybox({
                            href: "<?=\yii\helpers\Url::to(['reviewer'])?>?type=" + type + "&id=" + id+"&url="+url,
                            type: "iframe",
                            padding: 0,
                            autoSize: false,
                            width: 750,
                            height: 480
                        });
                    }
                });
            }
        });
    }

    //查看价格
    function viewPrice(id){
        $.fancybox({
            padding:[],
            href: "<?=\yii\helpers\Url::to(['view-price'])?>?id="+id,
            type: "ajax"
        });
    }

    //修改价格
    function modifyPrice(id){
        $.fancybox({
            padding:[],
            href: "<?=\yii\helpers\Url::to(['modify-price'])?>?id="+id,
            type: "ajax"
        });
    }


    //审核进度
    function checkProgress(id){
        $.fancybox({
            padding:[],
            href: "<?=\yii\helpers\Url::to(['check-info-popup'])?>?id="+id,
            type: "ajax"
        });
    }
</script>
