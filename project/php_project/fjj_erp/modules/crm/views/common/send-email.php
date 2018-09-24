    <?php
    /**
     * Created by PhpStorm.
     * User: F1678688
     * Date: 2017/2/16
     * Time: 下午 03:44
     */
    use yii\helpers\Url;
    use app\assets\JqueryUIAsset;
    JqueryUIAsset::register($this);
    ?>
    <style>
        .button-white, .button-white-small {
            width: 70px;
            height: 25px;
            background-color: #ffffff;
            color: #1f7ed0;
            border: 1px solid #1f7ed0;
            margin-left: 10px;
        }
    </style>
    <div id="emailbox">
        <h2 class="head-first" style="margin-bottom: 10px;">发邮件</h2>
        <p class="red" style="margin:0 10px;font-size: 1.17em;">无邮箱或邮箱格式错误的客户将会被过滤！</p>
        <div style="margin:0 10px;">
                <div class="has-add-wrap">
                        <div class="mb-5" style="width:780px;">
                            <span class="pull-left" style="line-height: 25px;">已选择的客户信息</span>
                            <span class="pull-right">
                                <button type="button" class="add-btn button-blue">继续添加</button>
                                <button type="button" class="del-btn button-white">删除行</button>
                            </span>
                            <div style="clear: both;"></div>
                    </div>
                    <div style="width:100%;" class="has-add-data"></div>
                    <?php $form=\yii\widgets\ActiveForm::begin([
                        'id'=>'email-form',
                        'action'=>Url::to(['send-message','type'=>2]),
                        'options'=>[
                            'target'=>'feedback',
                            'enctype'=>'multipart/form-data'
                        ]
                    ]);?>
                    <input id="customers" type="hidden" name="customers">
                    <input  type="hidden" name="CrmActImessage[imesg_sentman]" value="<?=\Yii::$app->user->identity->staff->staff_id;?>">
                    <input  type="hidden" name="CrmActImessage[imesg_type]" value="2">
                    <p class="mb-10">已选择客户的邮箱：</p>
                    <textarea style="height:50px;" name="Select" class="easyui-validatebox has-add-email" data-options="required:true,tipPosition:'top'" readonly></textarea>
                    <p class="mb-10">可录入其它需要发送信息的邮箱：</p>
                    <textarea name="Other" class="easyui-validatebox" data-options="validType:'multi_email',tipPosition:'bottom'"></textarea>
                    <div class="mb-10">
                        <label class="width-60 vertical-top">邮件主题</label>
                        <textarea style="height:50px;" name="CrmActImessage[imesg_subject]" class="easyui-validatebox" data-options="tipPosition:'top',validType:'maxLength[200]'" maxlength="200"></textarea>
                    </div>
                    <div class="mb-10">
                        <label class="width-60 vertical-top"><span style="color: red">*</span>邮件内容</label>
                        <textarea style="height:50px;" name="CrmActImessage[imesg_notes]" class="easyui-validatebox" data-options="required:true,tipPosition:'top',validType:'maxLength[200]'" maxlength="200"></textarea>
                    </div>
                    <div class="text-center mb-10">
                        <button type="submit" class="button-blue send">发送</button>
                        <button type="button" class="button-white cancel">取消</button>
                    </div>
                    <?php $form->end(); ?>
                </div>
                <div class="add-wrap" style="display: none;">
                    <div class="mb-10" style="width:780px;">
                        <span class="pull-left">
                            <span>请选择客户信息</span>
                            <input style="text-indent: 1em;" class="search-kwd" type="text" placeholder="公司名称或姓名">
                            <button type="button" class="search-btn button-blue">搜索</button>
                            <button type="button"  class="search-reset button-white">重置</button>
                        </span>
                        <span class="pull-right">
                            <button type="button" class="add-ensure button-blue">确定</button>
                            <button type="button" class="add-cancel button-white">返回</button>
                        </span>
                        <div style="clear: both;"></div>
                    </div>
                    <div class="add-data"></div>
                </div>
                <div style="display: none;" class="feedback-wrap">
                    <div class="mb-10">
                        <button type="button" class="button-white return">确定</button>
                    </div>
                    <h3>结果反馈:</h3>
                    <iframe name="feedback" frameborder="0" style="width:100%;height: 400px;overflow: scroll;"></iframe>
                </div>
        </div>
    </div>
    <script>
        $(function(){
            var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            $.extend($.fn.validatebox.defaults.rules, {
                multi_email:{
                    validator:function(value){
                        var flag=true;
                        var pattern=/^[a-zA-Z0-9!#$%&'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/;
                        var arr=value.replace(/\s+/g,"").replace(/(^,)|(,$)/g,"").split(",");
                        for(var n=0;n<arr.length;n++){
                            if(!pattern.test(arr[n])){
                                flag=false;
                                break;
                            }
                        }
                        return flag;
                    },
                    message:"邮箱格式错误"
                },
            });
            $("form").click(function(){
                $("button").prop("disabled",false);
            });

            $("form").submit(function(){
                if(!$(this).form('validate')){
                    return false;
                }
                $(".has-add-wrap").slideUp();
                $(".feedback-wrap").slideDown();
            });
            $(".feedback-wrap .return").click(function(){
                parent.$.fancybox.close();
                parent.$('#data').datagrid('reload');
//                $(".feedback-wrap").slideUp();
//                $(".has-add-wrap").slideDown();
//                $("button").prop("disabled",false);
            });

            $("#emailbox .has-add-data").datagrid({
                rownumbers: true,
                singleSelect: false,
                checkOnSelect: true,
                selectOnCheck: true,
                columns:[[
                    {field:"ck",checkbox:true,width:20},
                    {field:"cust_sname",title:"公司名称",width:196},
                    {field:"cust_contacts",title:"姓名",width:180},
                    {field:"cust_position",title:"职位",width:160},
                    {field:"cust_email",title:"邮箱",width:180}
                ]],
                onLoadSuccess: function (data) {
                    $("#emailbox .has-add-data").datagrid("resize");
                    showEmpty($(this),data.total,0);
                }
            });
            var rows=parent.$(".main-table").datagrid("getChecked");
            for(var x=0;x<rows.length;x++) {
                re.test(rows[x].cust_email) && $("#emailbox .has-add-data").datagrid("insertRow",{index:1, row:rows[x]});
            }
            datagridTip($("#emailbox .has-add-data"));
            updateCustomer();


            $("#emailbox .add-data").datagrid({
                url: "<?=$url?$url:Url::to(['index'])?>",
                rownumbers: true,
                method: "get",
                idField: "cust_id",
                loadMsg: "加载数据请稍候。。。",
                pagination: true,
                pageSize:5,
                pageList:[5,10,15],
                singleSelect: false,
                checkOnSelect:true,
                selectOnCheck: true,
                columns:[[
                    {field:"ck",checkbox:true,width:20},
                    {field:"cust_sname",title:"公司名称",width:200},
                    {field:"cust_contacts",title:"姓名",width:180},
                    {field:"cust_position",title:"职位",width:180},
                    {field:"cust_email",title:"邮箱",width:180}
                ]],
                onLoadSuccess: function (data) {
                    $("#emailbox .add-data").datagrid("resize");
                    datagridTip($("#emailbox .add-data"));
                    showEmpty($(this),data.total,0);
                }
            });

            $("#emailbox .add-btn").click(function(){
                $("#emailbox .has-add-wrap").slideUp();
                $("#emailbox .add-wrap").slideDown();
                $("#emailbox .add-data").datagrid("clearSelections");
                $("#emailbox .add-data").datagrid("reload",{
                    'customers':$('#customers').val()
                });
                $("#emailbox .add-data").datagrid("resize");
            });


            $("#emailbox .add-ensure").click(function(){
                var newRows=$("#emailbox .add-data").datagrid("getSelections");
                var oldRows=$("#emailbox .has-add-data").datagrid("getRows");
                $("#emailbox .has-add-wrap").slideDown();
                $("#emailbox .add-wrap").slideUp();
                for(var x=0;x<newRows.length;x++){
                    var flag=true;
                    for(var y=0;y<oldRows.length;y++){
                        if(oldRows[y].cust_id==newRows[x].cust_id || !re.test(newRows[x].cust_email)){
                            flag=false;
                            break;
                        }
                    }
                    if(flag==true){
                        $("#emailbox .has-add-data").datagrid("insertRow",{
                            index:0,
                            row:newRows[x]
                        });
                    }
                }
                datagridTip($("#emailbox .has-add-data"));
                updateCustomer();
            });

            $("#emailbox .search-btn").click(function(){
                var kwd=$("#emailbox .search-kwd").val();
                $("#emailbox .add-data").datagrid("reload",{
                    keywords:kwd
                });
            });

            $("#emailbox .search-reset").click(function(){
                $("#emailbox .search-kwd").val("");
                $("#emailbox .add-data").datagrid("reload",{
                    customers:$('#customers').val(),
                    cust_contacts:""
                });
            });


            $("#emailbox .del-btn").click(function(){
                var rows=$("#emailbox .has-add-data").datagrid("getSelections");
                for(var x=0;x<rows.length;x++){
                    $("#emailbox .has-add-data").datagrid("deleteRow",$("#emailbox .has-add-data").datagrid("getRowIndex",rows[x]));
                }
                updateCustomer();
                $("#emailbox .has-add-data").datagrid("resize");
            });

            $("#emailbox .add-cancel").click(function(){
                $("#emailbox .has-add-wrap").slideDown();
                $("#emailbox .add-wrap").slideUp();
            });


            $(".cancel,.exit").click(function(){
                parent.$.fancybox.close();
            });

            function updateCustomer(){
                var rows=$("#emailbox .has-add-data").datagrid("getRows");
                var tmp=new Array();
                for(var x=0;x<rows.length;x++){
                    re.test(rows[x].cust_email) && tmp.push(rows[x].cust_email);
                }
                $("#emailbox .has-add-email").val(tmp.join(",")).validatebox();

                var tmp=new Array();
                for(var x=0;x<rows.length;x++){
                    re.test(rows[x].cust_email) && tmp.push(rows[x].cust_id);
                }
                $("#customers").val(tmp.join(","));
            }

        });
    </script>


    <style type="text/css">
        textarea{
            width:780px;
        }
        button{
            font-size: 12px;
        }
        button:hover {
            cursor: pointer;
            border: 1px solid #0e0e0e;
        }
        .has-add-wrap .datagrid-wrap{
            width: 780px !important;
        }
        .has-add-wrap .datagrid-view{
            max-height: 180px !important;
        }
        .has-add-wrap .datagrid-view2{
            width:750px !important;
            left:30px;
            right:0px;
        }
        .has-add-wrap .datagrid-body{
            width:750px !important;
            max-height: 150px !important;
            overflow-x: hidden !important;
        }
    </style>