<?php
/**
 * User: f1677929
 * Date: 2017/2/25
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Json;
use app\classes\Menu;
$this->title='活动报名详情';
$this->params['homeLike']=['label'=>'客户关系管理','url'=>Url::to(['/index/index'])];
$this->params['breadcrumbs'][]=['label'=>'报名列表','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <h1 class="head-first"><?=$this->title?><span style="font-size:12px;color:white;float:right;margin-right:15px;"><?='编号：'.$data['acth_code']?></span></h1>
    <div style="margin-bottom:5px;">
        <?=Menu::isAction('/crm/crm-active-apply/edit')?"<button class='button-blue' onclick='window.location.href=\"".Url::to(['edit','id'=>$data['acth_id']])."\"'>修改</button>":""?>
<!--       <button id='delete_btn' class='button-blue'>删除</button>-->
        <?=Menu::isAction('/crm/crm-active-apply/index')?"<button class='button-blue width-80' onclick='window.location.href=\"".Url::to(['index'])."\"'>切换列表</button>":""?>
        <?=Menu::isAction('/crm/crm-active-apply/check-in')?"<button id='check_in_btn' class='button-blue'>签到</button>":""?>
        <?=Menu::isAction('/crm/crm-active-apply/pay')?"<button id='pay_btn' class='button-blue'>缴费</button>":""?>
    </div>
    <div style="height:2px;background-color:#9acfea;margin-bottom:10px;"></div>
    <h2 class="head-second">
        <i class="icon-caret-down icon" style="vertical-align:middle;font-size:25px;"></i>
        <i class="icon-caret-right icon" style="vertical-align:middle;font-size:25px;display:none;"></i>
        报名信息
    </h2>
    <div>
        <div class="mb-20">
            <span class="width-80 ml-30">活动类型：</span>
            <span class="width-300"><?=$data['acttype_name']?></span>
            <span class="width-80">活动名称：</span>
            <span class="width-300"><?=$data['actbs_name']?></span>
        </div>
        <div class="mb-20">
            <span class="width-80 ml-30">开始时间：</span>
            <span class="width-300"><?=empty($data['actbs_start_time'])?'':date('Y-m-d H:i',strtotime($data['actbs_start_time']))?></span>
            <span class="width-80">结束时间：</span>
            <span class="width-300"><?=empty($data['actbs_end_time'])?'':date('Y-m-d H:i',strtotime($data['actbs_end_time']))?></span>
        </div>
        <div class="mb-20">
            <span class="width-80 ml-30">报名日期：</span>
            <span class="width-300"><?=$data['acth_date']?></span>
            <span class="width-80">姓名：</span>
            <span class="width-300"><?=$data['acth_name'].'&nbsp;&nbsp;'.$data['acth_position']?></span>
        </div>
        <div class="mb-20">
            <span class="width-80 ml-30">参会身份：</span>
            <span class="width-300"><?=$data['joinIdentity']?></span>
            <span class="width-80">手机号码：</span>
            <span class="width-300"><?=$data['acth_phone']?></span>
        </div>
        <div class="mb-20">
            <span class="width-80 ml-30">邮箱：</span>
            <span class="width-300"><?=$data['acth_email']?></span>
            <span class="width-80">用餐信息：</span>
            <span class="width-300"><?=$data['acth_ismeal']?></span>
        </div>
        <div class="mb-20">
            <span class="width-80 ml-30">是否要缴费：</span>
            <span class="width-300"><?=$data['acth_ispay']?></span>
            <span class="width-80">需缴费金额：</span>
            <span class="width-300"><?=$data['acth_payamount']?></span>
        </div>
        <div class="mb-20">
            <span class="width-80 ml-30">是否要开票：</span>
            <span class="width-300"><?=$data['acth_isbill']?></span>
        </div>
        <div class="mb-30">
            <span class="width-80 ml-30">备注：</span>
            <span style="width:813px;vertical-align:middle;"><?=$data['acth_remark']?></span>
        </div>
    </div>
    <h2 class="head-second">
        <i class="icon-caret-down icon" style="vertical-align:middle;font-size:25px;"></i>
        <i class="icon-caret-right icon" style="vertical-align:middle;font-size:25px;display:none;"></i>
        公司基本信息
    </h2>
    <div>
        <div class="mb-20">
            <span class="width-80 ml-30">公司名称：</span>
            <span class="width-300"><?=$data['cust_sname']?></span>
            <span class="width-80">公司简称：</span>
            <span class="width-300"><?=$data['cust_shortname']?></span>
        </div>
        <div class="mb-20">
            <span class="width-80 ml-30">公司电话：</span>
            <span class="width-300"><?=$data['cust_tel1']?></span>
            <span class="width-80">邮编：</span>
            <span class="width-300"><?=$data['member_compzipcode']?></span>
        </div>
        <div class="mb-30">
            <span class="width-80 ml-30">详细地址：</span>
            <span style="width:686px;"><?=$data['customerAddress']?></span>
        </div>
    </div>
    <h2 class="head-second">
        <i class="icon-caret-down icon" style="vertical-align:middle;font-size:25px;display:none;"></i>
        <i class="icon-caret-right icon" style="vertical-align:middle;font-size:25px;"></i>
        公司详细信息
    </h2>
    <div style="display:none;">
        <div class="mb-20">
            <span class="width-80 ml-30">法人代表：</span>
            <span class="width-300"><?=$data['cust_inchargeperson']?></span>
            <span class="width-80">注册时间：</span>
            <span class="width-300"><?=$data['cust_regdate']?></span>
        </div>
        <div class="mb-20">
            <span class="width-80 ml-30">注册货币：</span>
            <span class="width-300"><?=$data['registerCurrency']?></span>
            <span class="width-80">注册资金：</span>
            <span class="width-300"><?=$data['cust_regfunds']?></span>
        </div>
        <div class="mb-20">
            <span class="width-80 ml-30">公司类型：</span>
            <span class="width-300"><?=$data['customerType']?></span>
            <span class="width-80">客户来源：</span>
            <span class="width-300"><?=$data['customerSource']?></span>
        </div>
        <div class="mb-20">
            <span class="width-80 ml-30">经营模式：</span>
            <span class="width-300"><?=$data['manageModel']?></span>
            <span class="width-80">交易币种：</span>
            <span class="width-300"><?=$data['member_curr']?></span>
        </div>
        <div class="mb-20">
            <span class="width-80 ml-30">年营业额：</span>
            <span class="width-300"><?=$data['member_compsum']?></span>
            <span class="width-80">年采购额：</span>
            <span class="width-300"><?=$data['cust_pruchaseqty']?></span>
        </div>
        <div class="mb-20">
            <span class="width-80 ml-30">员工人数：</span>
            <span class="width-300"><?=$data['cust_personqty']?></span>
            <span class="width-80">发票需求：</span>
            <span class="width-300"><?=$data['member_compreq']?></span>
        </div>
        <div class="mb-20">
            <span class="width-80 ml-30">潜在需求：</span>
            <span class="width-300"><?=$data['potentialRequired']?></span>
            <span class="width-80">需求类目：</span>
            <span class="width-300"><?=$data['requiredType']?></span>
        </div>
        <div class="mb-20">
            <span class="width-80 ml-30">需求类别：</span>
            <span class="width-300"><?=$data['member_reqdesription']?></span>
            <span class="width-80">主要市场：</span>
            <span class="width-300"><?=$data['member_marketing']?></span>
        </div>
        <div class="mb-20">
            <span class="width-80 ml-30">主要客户：</span>
            <span class="width-300"><?=$data['member_compcust']?></span>
            <span class="width-80">主页：</span>
            <span class="width-300"><?=$data['member_compwebside']?></span>
        </div>
        <div class="mb-20">
            <span class="width-80 ml-30">经营范围：</span>
            <span style="width:686px;"><?=$data['member_businessarea']?></span>
        </div>
    </div>
</div>
<script>
    $(function(){
        //删除
        $("#delete_btn").click(function(){
            if("<?=$data['acth_ischeckin']?>"=="1"){
                layer.alert('该客户已签到，不可删除！',{icon:2,time:5000});
                return false;
            }
            var dateObj=new Date();
            var activeEndDate=new Date("<?=$data['actbs_end_time']?>".replace(/\-/g, "\/"));
            var currentDate=new Date(dateObj.toLocaleDateString().replace(/\-/g, "\/"));
            if(activeEndDate<currentDate){
                layer.alert('活动已结束，不可删除！',{icon:2,time:5000});
                return false;
            }
            layer.confirm('确定删除吗？',{icon:2},
                function(){
                    $.ajax({
                        url:"<?=Url::to(['delete-apply']);?>",
                        data:{"arrId":"<?=$data['acth_id']?>"},
                        dataType:"json",
                        success:function(data){
                            if(data.flag==1){
                                layer.alert(data.msg,{icon:1},function(){
                                    window.location.href="<?=Url::to(['index'])?>";
                                });
                                setTimeout(function(){window.location.href="<?=Url::to(['index'])?>";},5000);
                            }else{
                                layer.alert(data.msg,{icon:2});
                            }
                        }
                    })
                },
                layer.closeAll()
            )
        });

        //签到
        $('#check_in_btn').click(function(){
            $.fancybox({
                href:"<?=Url::to(['check-in','id'=>$data['acth_id']])?>",
                type:'iframe',
                padding:0,
                autoSize:false,
                width:800,
                height:500,
                fitToView:false,
            });
        });

        //缴费
        $('#pay_btn').click(function(){
            if("<?=$data['acth_ispay']?>"=="否"){
                layer.alert('该客户不需要缴费！',{icon:2,time:5000});
                return false;
            }
            $.fancybox({
                href:"<?=Url::to(['pay','id'=>$data['acth_id']])?>",
                type:'iframe',
                padding:0,
                autoSize:false,
                width:700,
                height:450,
                fitToView:false,
            });
        });

        //导出
        $('#export_btn').click(function(){
//            var doc=new jsPDF();
//            doc.text(20, 20, 'Hello world!');
//            doc.text(20, 30, 'This is client-side Javascript, pumping out a PDF.');
//            doc.addPage();
//            doc.text(20, 20, 'Do you like that?');
//
//            doc.save('Test.pdf');

//            var pdf = new jsPDF('p','pt','a4');
//            pdf.addHTML(document.body,function() {
//                pdf.save('web.pdf');
//            });


            html2canvas($(".content"), {
                onrendered: function(canvas) {

                    //通过html2canvas将html渲染成canvas，然后获取图片数据
                    var imgData = canvas.toDataURL('image/jpeg');

                    //初始化pdf，设置相应格式
                    var doc = new jsPDF("p", "mm", "a4");

                    //这里设置的是a4纸张尺寸
                    doc.addImage(imgData, 'JPEG', 0, 0,210,297);

                    //输出保存命名为content的pdf
                    doc.save('活动报名.pdf');
                },
                background:"white",
            });
        });

        //显示隐藏模块
        $(".head-second").hover(
            function(){$(this).css("cursor","pointer")},
            function(){$(this).css("cursor","default")}
        ).click(function(){
            $(this).next().toggle();
            $(this).children(".icon").toggle();
            setMenuHeight();
        });
    })
</script>