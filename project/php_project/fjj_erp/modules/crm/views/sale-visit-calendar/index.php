<?php
/**
 * User: F1677929
 * Date: 2017/9/30
 */
use yii\helpers\Url;
use app\classes\Menu;
\app\assets\FullCalendarAsset::register($this);
\app\assets\JeDateAsset::register($this);
$this->title='行程管理';
$this->params['homeLike']=['label'=>'客户关系管理'];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <h1 class="head-first"><?=$this->title?></h1>
    <div style="margin-bottom:15px;">
        <label>跳转至：</label>
        <input id="jump_date" type="text" style="width:150px;" readonly="readonly" class="Wdate">
<!--        <span style="margin-left:15px;color:red;">说明：浅绿色表示拜访计划，浅蓝色表示拜访记录。</span>-->
        <button type="button" class="search-btn-blue" style="width:100px;margin-left:15px;float:right;" onclick="window.location.href='<?=Url::to(['/crm/crm-visit-record/add'])?>'">新增拜访记录</button>
        <button type="button" class="search-btn-blue" style="width:100px;float:right;" onclick="window.location.href='<?=Url::to(['/crm/crm-visit-plan/create'])?>'">新增拜访计划</button>
    </div>
    <div id="plan_calendar"></div>
</div>
<script>
    $(function(){
        $("#plan_calendar").fullCalendar({
            header:{
                left:'agendaDay,agendaWeek,month',
                center:'title',
                right:'prev,next,today'
            },
            events:function(start,end,timezone,callback){
                $.ajax({
                    url:"<?=Url::to(['index'])?>",
                    dataType:"json",
                    data:{
                        start:start.unix(),
                        end:end.unix()
                    },
                    success:function(data){
                        var events=[];
                        if(data.length > 0){
                            var company="";
                            var color="";
                            var url="";
                            $.each(data,function(i,n){
                                //处理没有简称用全称
                                company=n.cust_shortname;
                                if(n.cust_shortname==null || n.cust_shortname==''){
                                    company=n.cust_sname;
                                }
                                //处理记录计划不同点
                                if(n.svp_id !== undefined){
                                    color="rgba(159,216,249,0.71)";
                                    url="<?=Url::to(['/crm/crm-visit-plan/view'])?>?id="+n.svp_id;
                                }
//                                if(n.sil_id !== undefined){
//                                    color="rgba(169,249,159,0.71)";
//                                    url="<?//=Url::to(['/crm/crm-visit-record/view-record'])?>//?childId="+n.sil_id;
//                                }
                                //处理时间
                                n.start=n.start.substr(0,16);
                                n.end=n.end.substr(0,16);
                                //处理title
                                var title="<div style='white-space:nowrap;overflow:hidden;text-overflow:ellipsis;'>公司："+company+"；拜访时间："+n.start+"~"+n.end+"</div>";
                                if(n.start.substr(0,10) == n.end.substr(0,10)){
                                    title="<div style='white-space:nowrap;overflow:hidden;text-overflow:ellipsis;'>公司："+company+"</div><div>ST : "+n.start+"</div><div>ET : "+n.end+"</div>";
                                }
                                //添加数据
                                events.push({
                                    start:n.start,
                                    end:n.end,
//                                    title:"<div style='white-space:nowrap;overflow:hidden;text-overflow:ellipsis;'>公司："+company+"</div><div>ST : "+n.start+"</div><div>ET : "+n.end+"</div>",
                                    title:title,
                                    color:color,
                                    url:url
                                });
                            });
                        }
                        callback(events);
                    }
                });
            },
            eventRender:function(event,element){ //处理title
                element.html(event.title);
                $(element).mouseover(function(){
                    this.title=$(element).find("div:first").text();
                });
            }
        });

        //跳转至某天
//        $("#jump_date").jeDate({
//            format: "YYYY-MM-DD",
//            zIndex: 5,
//            okfun: function(obj) {
//                $("#plan_calendar").fullCalendar('gotoDate',obj.val);
//            }
//        });
        $("#jump_date").click(function(){
            WdatePicker({
                skin:"whyGreen",
                onpicked:function(){
                    $("#plan_calendar").fullCalendar('gotoDate',$(this).val());
                }
            });
        });
        $(".content button").click(function(){
            $("#jump_date").val('');
        });
    })
</script>