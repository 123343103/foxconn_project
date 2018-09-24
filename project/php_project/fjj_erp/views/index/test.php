<?php

/**
 * 计划管理视图
 * User: F3859386
 * Date: 2016/11/3
 */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
\app\assets\FullcalendarAsset::register($this);
?>
<style>
    .fancybox-inner{
        width:700px !important;
    }

</style>
<div class="content">

    <h1 class="head-first">
        计划管理
    </h1>
    <div id='mycalendar'></div>
</div>
<script type=text/javascript>
    $(function(){
//        $("#startdate").on("change",function(){
//            alert("the paragraph was clicked.");
//        });
//        $("#visit-info-form").on("click",function(){
//            ajaxSubmitForm($("#visit-info-form"));
//        })


        //初始化FullCalendar
        $('#mycalendar').fullCalendar({
            //日历初始化默认视图，可选agendaWeek、agendaDay、month
            defaultView: 'month',
            lang: "zh-cn",
            /*
             设置日历头部信息
             头部信息包括left、center、right三个位置，分别对应头部左边、头部中间和头部右边。
             头部信息每个位置可以对应以下配置：
             title: 显示当前月份/周/日信息
             prev: 用于切换到上一月/周/日视图的按钮
             next: 用于切换到下一月/周/日视图的按钮
             prevYear: 用于切换到上一年视图的按钮
             nextYear: 用于切换到下一年视图的按钮
             如果不想显示头部信息，可以设置header为false
             */
            header: {
                right: 'prev,next today',
                center: 'title',
                left: 'agendaDay,agendaWeek,month'
            },

            //设置日历头部的日期格式
            titleFormat: {
                month: 'YYYY年MM月',
                week: 'YYYY年MM月DD日',
                day: 'YYYY年MM月DD日 dddd'
            },
            //日历高度
            height: 620,
            editable:true,
            allDaySlot:false,
            //显示周末，设为false则不显示周六和周日。
            weekends: true,



            //拖动事件时触发
            eventDrop: function( event, delta, revertFunc, jsEvent, ui, view ) {
                if (!confirm("你确认要将**"+event.title+"**移到"+event.start.format("YYYY-MM-DD H:mm")+"这个时间上吗？")) {
                    revertFunc();
                }else{
                    var days = delta._days;
                    var ms = delta._milliseconds;
                    var id = event.id
                    var data = {
                        "days":days,
                        "ms":ms,
                        "id":id,
                    };
//                    $.post("{:U('Schedule/edit')}",data,function(msg){
//                        if(msg !="succ"){
//                            alert(msg);
//                            revertFunc();
//                        }
//                    });
                };
            },
            /*
             在月视图里显示周的模式，因为每月周数可能不同，所以月视图高度不一定。
             fixed：固定显示6周高，日历高度保持不变
             liquid：不固定周数，高度随周数变化
             variable：不固定周数，但高度固定
             */
            weekMode: 'liquid',
            //起始时间
            minTime:'08:00',
            //允许用户通过单击或拖动选择日历中的对象，包括天和时间。
            selectable: true,
            //是否随浏览器窗口大小变化而自动变化。
            handleWindowResize:false,
            //当点击或拖动选择时间时，显示默认加载的提示信息，该属性只在周/天视图里可用。
            selectHelper: true,
            //当点击页面日历以外的位置时，自动取消当前的选中状态。
            unselectAuto: true,
            unselectCancel:".fancy",
            //文本字体顏色
            eventTextColor:"black",
            //数据源
            events: {
                url: "<?=Url::to(['plan-data'])?>"
            },
            //拖动时间长度时触发
            eventResize:function( event, delta, revertFunc, jsEvent, ui, view ) {

                var days = delta._days;
                var ms = delta._milliseconds;
                var id = event.id
                var data = {
                    "days":days,
                    "ms":ms,
                    "id":id,
                };
//                $.post("{:U('Schedule/dropEdit')}",data,function(msg){
//                    if(msg != "succ"){
//                    }
//                });
            },

            /*
             添加日程事件
             start: 被选中区域的开始时间
             end: 被选中区域的结束时间
             jsEvent: jascript对象
             view: 当前视图对象
             */
            select: function(start, end, jsEvent, view){
                var startDate =moment(start).format("YYYY-MM-DD@HH:mm");
                var endDate = moment(end).format("YYYY-MM-DD@HH:mm");

//                //调用fancybox弹出层
                $.fancybox({
                    padding: [],
                    width: 700,
                    autoSize: true,
                    type: 'iframe',
                    href:"<?= Url::to(['create-visit'])?>?start="+startDate+"&end="+endDate
                });
            },

            /*
             修改日程事件
             当点击日历中的某一日程时，触发此事件
             data: 日程信息
             jsEvent: jascript对象
             view: 当前视图对象
             */
            eventClick:  function(Event, jsEvent, view) {
                var id=Event.svp_id;
                var cid=Event.sil_id;
                var type=Event.type;
                var url=''
                if(type == 10) {
                    url = "<?= Url::to(['/crm/crm-visit-plan/edit-plan'])?>?id=" + id;
                }else if(type == 20){
                    url= "<?= Url::to(['/crm/crm-visit-info/edit-info'])?>?id="+cid
                }else if(type == 30){
                    url= "<?= Url::to(['/crm/crm-visit-info/edit-temp'])?>?id="+cid
                }
                if(Event.editable != false){
                    $.fancybox({
                        padding: [],
                        width: 700,
                        autoSize: true,
                        type: 'iframe',
                        'href':url
                    });
                }
            }
    });

    $(function () {
        $("[data-toggle='tooltip']").tooltip();
        $("#addScheduleInfo").click(function(){
            $.fancybox({
                'type':'ajax',
//                'href':"{:U('Schedule/addScheduleInfo')}",
            });
        })

    });
    //    //加入到行程
    //    function subScheduleInfo(id){
    //        $.fancybox({
    //            'type':'ajax',
    //            'href':"{:U('Schedule/addScheduleDate')}?id="+id
    //        });
    //    }
    //    function createPlan(){
    //
    //    }
    //删除事项
    //    function delScheduleInfo(id){
    //        bootbox.confirm("确定删除事项?", function(result){
    //            if (result) {
    //                $.get("{:U('Schedule/delSche')}",{"id":id},function(msg){
    //                    if( msg =="succ"){
    //                        bootbox.alert('完成',function(){
    //                            location.reload();
    //                        });
    //                    }else{
    //                        alert('发生错误');
    //                    }
    //                })
    //            }
    //        });
    //    }

</script>