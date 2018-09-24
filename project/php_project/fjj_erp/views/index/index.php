<?php
/**
 * Created by PhpStorm.
 * User: F3858995
 * Date: 2016/9/14
 * Time: 下午 02:38
 */
use yii\helpers\Url;
\app\assets\FullCalendarAsset::register($this);

$this->title = '富金机ERP系统';
?>
<style>
    .desktop-icon {
        overflow: hidden;
        cursor: pointer;
    }
    .page {
        margin: 0 40px;
        position: absolute;
        display: none;
        width: 900px;
    }
    .page1 {
        margin: 0 40px;
        display: block;
    }

    #my-calendar {
        background: #ffffff;
    }
    #my-calendar .fc-widget-header{
        background: #3791E1;
    }
    #my-calendar button:hover{
        border:none  ;
    }
    #my-calendar button{
        margin-right: 3px  ;
        width: 40px;
    }

    .fc-widget-header>table>thead>tr>th:nth-child(6),.fc-widget-header>table>thead>tr>th:nth-child(7){
            color: #F96F48;
    }
    .fc-widget-header>table>thead>tr>th{
            color: #FFFFFF;
    }
    .fc-center>h2{
            color: #1F7ED0;
            font-weight:normal;
    }
    .fc-day-grid-container{
        height:306px !important;
    }

    .today {
        background: #FF8762;
    }
    .future {
        background: #FF8762;
    }
    .past {
        background: #3791E1;
    }
    .today, .future, .past {
        width:15px;
        height: 15px;
        border-radius: 0;
        border: none ;
        text-align: center !important;

    }

    .today .fc-title, .future .fc-title, .past .fc-title {
        color: white;
        text-align: center;
    }
    .today .fc-time, .future .fc-time, .past .fc-time {
             display: none;
     }

    .remove-right {
        position: absolute;
        /*width: 30px;*/
        /*height: 30px;*/
        right: 19px;
        top: 170px;
    }
    .remove-left {
        position: absolute;
        /*width: 30px;*/
        /*height: 30px;*/
        left: 17px;
        top: 170px;
    }

    input, select, textarea, tr, td, th, table {
        border: 0;
    }

    .fc-event-container{
        height:25px;
    }
    .fc-event-container>a{
        margin-top:17px;
    }
    /*.fc-day-number .fc-thu .fc-past{*/
    /*}*/
    .fc-ltr .fc-basic-view .fc-day-number {
        /*margin-top: 20px;*/
        line-height: 20px;
        /*height:20px;*/
        text-align: center;
    }
    .fc-basic-view .fc-body .fc-row {
        min-height: 4.2em;
    }
    .fc-state-default.fc-corner-left,.fc-state-default.fc-corner-right{
         border-top-left-radius: 0;
         border-bottom-left-radius: 0;
         border-top-right-radius: 0;
         border-bottom-right-radius: 0;
    }
    .main-content {
        min-height: 1300px;
    }
    .mt-40{ margin-top: 40px;}
    .mt-30{margin-top:30px;}
    .mt-20{margin-top:20px;}
    .mt-15{margin-top: 15px;}
    .ml-110{margin-left:110px;}
    .ml-10{margin-left:10px;}
    .width-80{width:80px;}
    .width-90{width:90px;}
    .width-100{width:100px;}
</style>
<ul class="breadcrumb">
    <div class="index-head"><p><i class="icon-home icon-large"></i>主页</p></div>
</ul>
<div class="content">
    <div class="mb-10">
        <div class="my-tools">
            <div class="tools-head" ><i class=" icon-briefcase icon-large"></i> 我的工作 </div>
            <div class="mt-15">
                <div class="tools-border ml-110">
                    <div class="width-80 text-center"><img src="<?= Url::to("@web/img/icon/avatar.png") ?>"></div>
                    <div class="width-90 text-center tools-border-line"><span >待审核</span></div>
                    <a class="text-center"  style="display: table-row" href="<?= Url::to(['/system/verify-record/index']) ?>">
                        <span class="notice-num" id="verify_count" ><?= $count ?></span>
                    </a>
                </div>
            </div>
            <div class="mt-20">
                <div class="tools-border ml-110">
                    <div class="width-80 text-center"><img src="<?= Url::to("@web/img/icon/job.png") ?>"></div>
                    <div class="width-90 text-center tools-border-line"><span >我的通知</span></div>
                    <a class="text-center width-100" href="<?= Url::to(['/system/inform/index']) ?>" id="inform">
                        <span class="notice-num" id="myInform"><?= $informCount ?></span>
                    </a>
                </div>
            </div>
            <div class="mt-20">
                <div class="tools-border ml-110">
                    <div class="width-80 text-center"><img src="<?= Url::to("@web/img/icon/job.png") ?>"></div>
                    <div class="width-90 text-center tools-border-line"><span >问卷调查</span></div>
                    <a class="text-center width-100" href="<?= Url::to(['/hr/question-survey/survey-show']) ?>?index=<?='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>" id="question-survey">
                        <span class="notice-num" id="mySurvey"><?=$SurveyCount?></span>
                    </a>
                </div>
            </div>
            <div class="mt-20 text-center">
                <div class="tools-border ml-110">
                    <div class="width-80 text-center"><img src="<?= Url::to("@web/img/icon/list.png") ?>"></div>
                    <div class="width-90 text-center tools-border-line"><span>我的报表</span></div>
                    <a class="text-center width-100" href="<?=Url::to(['/rpt/my-rpt/index'])?>">
                        <span class="notice-num"><?=$rptCount?></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="my-tools ml-10">
            <div class="tools-head" ><i class="icon-calendar icon-large"></i> 行程</div>
            <div id="my-calendar"></div>
        </div>
    </div>
    <div class="mb-10">
        <div class="my-desktop mt-20">
        <div class="tools-head"><i class="icon-desktop icon-large"></i> 我的桌面</div>
        <?php foreach($available as $k=>$v) { ?>
        <div class="page page<?= $k ?>">
            <?php foreach($v as $kk=>$vv) { ?>
                <a class="desktop-icon " href="<?= Url::to([$vv[action_url]]) ?>">
                    <img src="<?= Url::to("@web/img/desktop-icon/$vv[action_icon]") ?>">
                    <span><?= $vv['action_title'] ?></span>
                </a>
            <?php } ?>
            <a class="desktop-icon desktop-add" href="<?= Url::to(['/index/desktop']) ?>">
                <img src="<?= Url::to("@web/img/icon/icon_16.png") ?>">
                <span>添加</span>
            </a>
        </div>
        <?php } ?>
            <img src="<?= Url::to("@web/img/icon/arrow.png") ?>" class="remove-left" style="display: none; -moz-transform:rotate(-180deg);
      -webkit-transform:rotate(-180deg);
       filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);     " alt="左滾動">
            <img src="<?= Url::to("@web/img/icon/arrow.png") ?>" class="remove-right" alt="右滾動">
        </div>
    </div>
    <div class="space-20"></div>
    <div class="mb-10">
        <div class="my-info mt-20">
            <div class="tools-head"><i class="icon-envelope icon-large"></i> 信息公告</div>
        </div>
    </div>
</div>
<script>
    $(function () {
//        var verify_count="<?//= $count ?>//";
//        var myInform="<?//= $informCount ?>//";
//        var mySurvey="<?//= $SurveyCount ?>//";
//        $("#verify_count").html(infinite(verify_count));
//        $("#myInform").html(infinite(myInform));
//        $("#mySurvey").html(infinite(mySurvey));

        var pageTotal = <?= $pageTotal ?>;
        var lastPageCount = <?= $lastPageCount ?>;
        var currentPageIndex = 1;
        displayArrow();
        if (currentPageIndex == pageTotal) {
//            alert('eq')
        }
        // 是否显示箭头标
        function displayArrow() {
            if (pageTotal == 1) { // 只有一页不显示箭头
                $('.remove-right').css('display','none');
                if (lastPageCount>0 && lastPageCount<=5) {
                    $('.remove-left').css('top','100px');
                    $('.my-desktop').css('height','200px');
                }
            } else if (currentPageIndex==pageTotal) {
                $('.remove-left').css('display','block');
                $('.remove-right').css('display','none');
                if (lastPageCount>0 && lastPageCount<=5) {
                    $('.remove-left').css('top','100px');
                    $('.my-desktop').css('height','200px');
                }
            } else if (currentPageIndex==1) {
                $('.remove-left').css('display','none');
                $('.remove-right').css('display','block');
                $('.my-desktop').css('height','350px');
            } else {
                $('.remove-left,.remove-right').css('display','block');
                $('.remove-left,.remove-right').css('top','170px');
                $('.my-desktop').css('height','350px');
            }
        }

        // 向左翻页
        $('.remove-left').click(function () {
            var page = '.page'+currentPageIndex;
            var newPage = '.page'+(currentPageIndex-1);
            currentPageIndex -= 1;
            $(page).animate({marginLeft:"-=150px"},function () {
                $(page).css('margin','0 40px');
                $(page).css('display','none');
                $(newPage).css('display','inline-block');
            });
            displayArrow();
            setMenuHeight();
        });
        // 向右翻页
        $('.remove-right').click(function () {
            var page = '.page'+currentPageIndex;
            var newPage = '.page'+(currentPageIndex+1);
            currentPageIndex += 1;
            $(page).animate({marginLeft:"+=150px"},function () {
                $(page).css('margin','0 40px');
                $(page).css('display','none');
                $(newPage).css('display','inline-block');
            });
            displayArrow();
            setMenuHeight();
        });

        $("#inform").fancybox({
        padding: [],
        fitToView: false,
        width: 500,
        height: 385,
        autoSize: false,
        closeClick: false,
        openEffect: 'none',
        closeEffect: 'none',
        type: 'iframe'
    });
        $("#question-survey").fancybox({
            type:"iframe",
            padding:0,
            autoSize:false,
            width:500,
            height:400
        });
    setInterval("refresh()",5*60*1000);
    })

    function refresh(){
        $.ajax({
            type: "get",
            dataType: "json",
            async: false,
            data: {"id": <?= Yii::$app->user->identity->staff_id ?>},
            url: "<?=Url::to(['/system/verify-record/inform-count']) ?>",
            success: function (msg) {
                $("#myInform").html(msg);
            },
        })
    }

    //超过99显示99+
    function infinite(number){
        if(number>99){
            return "99<sup style=\"display: inline-flex; font-size: 22px; line-height: 10px\">+</sup>";
        }
        return number;
    }

    $(function(){

        //初始化FullCalendar
        $('#my-calendar').fullCalendar({
            defaultView: 'month',
            lang: "zh-cn",
            header: {
                right: 'month,agendaWeek,agendaDay',
                center: 'title',
                left: 'prev,next today'
            },
            titleFormat: 'YYYY年MM月',
//            titleFormat: {
//                month: 'YYYY年MM月',
//                week: 'YYYY年MM月DD日',
//                day: 'YYYY年MM月DD日 dddd'
//            },
            height: 335,
            editable:true,
            allDaySlot:false,
            weekends: true,
            weekMode: 'liquid',
            minTime:'08:00',
            selectable: true,
            handleWindowResize:false,
            selectHelper: true,
            unselectAuto: true,
            unselectCancel:".fancy",
//            eventTextColor:"white",
//            eventColor:'#000',
//            eventBackgroundColor:'#000',
            eventBorderColor:'#FFF',
//            eventTextColor:'#000',
            //数据源  会自动传递本视图开始start和结束日期end
            events: {
                url: "<?= Url::to(['crm/crm-plan-manage/plan-count'])?>",
                type: 'get',
                data: {staffCode: '<?= yii::$app->user->identity->staff->staff_code ?>'},
                error: function (data) {
//                    alert('日历数据源出错！');
                    console.log(data)
                },
            },
            // 鼠标划过tips
            eventMouseover: function (calEvent, jsEvent, view) {
                $(this).attr('title', '共有'+calEvent.title+'条计划');
                $(this).css('font-weight', 'normal');
            },
            /*
             添加日程事件
             start: 被选中区域的开始时间
             end: 被选中区域的结束时间
             jsEvent: jascript对象
             view: 当前视图对象
             */
//            select: function(start, end, jsEvent, view){
//                var startDate =moment(start).format("YYYY-MM-DD@HH:mm");
//                var endDate = moment(end).format("YYYY-MM-DD@HH:mm");
//                var selectDate = moment(end).format("YYYY-MM-DD");
//                var today = view.calendar.getDate().format('YYYY-MM-DD');
//
//                //调用fancybox弹出层
//                $.fancybox({
//                    padding: [],
//                    width: 700,
//                    autoSize: true,
//                    type: 'iframe',
//                    href:"<?//= Url::to(['crm/crm-plan-manage/create-visit'])?>//?start="+startDate+"&end="+endDate+"&from=home"
//                });
//            }
        });


        $(".fc-month-button").click(function(){
            showHide($(this))
        });
        $(".fc-agendaWeek-button").click(function(){
            $(".fc-widget-header>table>thead>tr>th:nth-child(6)").css('color','#FFFFFF');
            $(".fc-widget-header>table>thead>tr>th:nth-child(8)").css('color','#F96F48');
            showHide($(this))
        });
        $(".fc-agendaDay-button").click(function(){
            showHide($(this))
        });

//        $(".second-menu").mouseover(function (event) {
//            console.log(111)
////            event.stopPropagation();
//        });
//        $(".second-menu").mouseout(function (event) {
//            console.log(222)
//            event.stopPropagation();
//        });
    });
    function showHide(hideA){
        $(".fc-month-button,.fc-agendaDay-button,.fc-agendaWeek-button").show();
        hideA.hide()
    }
</script>