<?php
/**
 * User: F1677929
 * Date: 2017/6/23
 */
use yii\helpers\Url;
use app\classes\Menu;
\app\assets\FullCalendarAsset::register($this);
$this->title='活动行事历';
$this->params['homeLike']=['label'=>'客户关系管理','url'=>Url::to(['/index/index'])];
$this->params['breadcrumbs'][]=$this->title;
?>
<style>
    .add_active:hover{
        color:#1e7fd0;
        cursor:pointer;
    }
</style>
<div class="content">
    <h1 class="head-first"><?=$this->title?></h1>
    <div class="mb-10">
        <label class="width-100">方式选择</label>
        <select id="active_way" class="width-100">
            <option value="">全部</option>
            <?php foreach($data as $key=>$val){?>
                <option value="<?=$key?>"><?=$val?></option>
            <?php }?>
        </select>
        <?php if(Menu::isAction('/crm/crm-active-name/add')){?>
        <i class="icon-plus add_active" style="font-size:14px;margin-left:50px;" onclick="location.href='<?=Url::to(['/crm/crm-active-name/add'])?>?flag=calendar'"><span class="add_active" style="padding-left:2px;">新增活动</span></i>
        <?php }?>
        <label class="width-100">跳转至</label>
        <input id="jump_date" type="text" class="width-100" placeholder="例如:2017-01">
    </div>
    <div class="mb-15 ml-40">
        <div id="all_total" style="color:red;">活动数量统计：总数量：</div>
        <div id="online_total" style="color:red;">线上：</div>
        <div id="offline_total" style="color:red;">线下：</div>
    </div>
    <div id="active_calendar"></div>
</div>
<script>
    //获取当前时间，格式YYYY-MM-DD
//    function getNowFormatDate() {
//        var date = new Date();
//        var seperator1 = "-";
//        var year = date.getFullYear();
//        var month = date.getMonth() + 1;
//        var strDate = date.getDate();
//        if (month >= 1 && month <= 9) {
//            month = "0" + month;
//        }
//        if (strDate >= 0 && strDate <= 9) {
//            strDate = "0" + strDate;
//        }
//        var currentdate = year + seperator1 + month + seperator1 + strDate;
//        return currentdate;
//    }

    //js 判断数组重复元素以及重复的个数
    function dealArr(_arr){
        _arr.sort();
        var _res = [];
        for (var i = 0; i < _arr.length;) {
            var count = 0;
            for (var j = i; j < _arr.length; j++) {
                if (_arr[i] == _arr[j]) {
                    count++;
                }
            }
            _res.push([_arr[i], count]);
            i += count;
        }
        var _newArr = [];
        for (var m = 0; m < _res.length; m++) {
            _newArr.push(_res[m][0] + '：' + _res[m][1]);
        }
        return _newArr;
    }

    //活动数量统计
    function getActiveInfo(wayId){
        var moment=$activeCalendar.fullCalendar('getDate');
        var month=moment.format().substr(0,7);
        month=new Date(month);
        var start=(Date.parse(month)/1000)-8*3600;
        month.setMonth(parseInt(month.getMonth())+1);
        var end=(Date.parse(month)/1000)-8*3600;
        $.ajax({
            url:"<?=Url::to(['index'])?>",
            data:{
                start:start,
                end:end,
                wayId:wayId
            },
            dataType:"json",
            success:function(data){
                var str1='';
                var str2='';
                if(data.length>0){
                    var online=[];
                    var offline=[];
                    $.each(data,function(i,n){
                        if(n.activeWay=='线上'){
                            online.push(n.acttype_name);
                        }
                        if(n.activeWay=='线下'){
                            offline.push(n.acttype_name);
                        }
                    });
                    if(online.length>0){
                        var arr1=dealArr(online);
                        str1=arr1.join("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
                    }
                    if(offline.length>0){
                        var arr2=dealArr(offline);
                        str2=arr2.join("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
                    }
                }
                var wayVal=$("#active_way").find("option:selected").text();
                if(wayVal=='全部'){
                    $("#all_total").html('活动数量统计：总数量：'+data.length);
                    $("#online_total").show().html('线上：'+str1);
                    $("#offline_total").show().html('线下：'+str2);
                }
                if(wayVal=='线上'){
                    $("#all_total").html('活动数量统计：总数量：'+data.length+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+str1);
                    $("#online_total").hide();
                    $("#offline_total").hide();
                }
                if(wayVal=='线下'){
                    $("#all_total").html('活动数量统计：总数量：'+data.length+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+str2);
                    $("#online_total").hide();
                    $("#offline_total").hide();
                }
            }
        });
    }

    $(function(){
        $activeCalendar=$("#active_calendar");
        var wayId='';
        //活动行事历
        $activeCalendar.fullCalendar({
            header: {
                left: 'prev,next',
                center: 'title',
                right: 'today'
            },
            titleFormat: 'YYYY年MM月',
//            titleFormat: {
//                month: 'YYYY年MM月',
//                week: 'YYYY年MM月DD日',
//                day: 'YYYY年MM月DD日 dddd'
//            },
            height: 'auto',
//            contentHeight: 'auto',
//            eventLimit: true,
//            weekMode: 'liquid',
//            defaultDate: "2017-06-01",
            fixedWeekCount : false,//月视图下，显示6周（不够的显示下个月的）true；默认true
//            timezoneParam: 'Asia/Shanghai',
//            timezone: 'local',
            events: function(start, end, timezone, callback) {
//                if("<?//=Menu::isAction('/crm/crm-active-name/add')?>//"=="1"){
//                    var currentDate=getNowFormatDate();
//                    currentDate=currentDate.substr(0,10);
//                    currentDate=Date.parse(currentDate);
//                    $.each($(".fc-bg td"),function(i,n){
//                        var date=Date.parse($(n).data('date'));
//                        if(date>=currentDate){
//                            $(this).html("<span style='margin:127px 0 0 121px;font-size:25px;font-weight:900;'><a href='<?//=Url::to(['/crm/crm-active-name/add'])?>//?start="+$(this).data('date')+"'>+</a></span>");
//                        }
//                    });
//                }
                $.ajax({
                    url: '<?=Url::to(['index'])?>',
                    dataType: 'json',
                    data: {
                        start: (start.unix()-8*3600),
                        end: (end.unix()-8*3600),
                        wayId: wayId
                    },
                    success: function(data) {
                        //权限处理
                        var editAuth="<?=Menu::isAction('/crm/crm-active-name/edit')?>";
                        var viewAuth="<?=Menu::isAction('/crm/crm-active-name/view')?>";
                        var arr=[];
                        if (data.length > 0) {
                            //处理颜色
                            var moment=$activeCalendar.fullCalendar('getDate');
                            var month=moment.format().substr(0,7);
                            month=new Date(month);
                            var month_start=(Date.parse(month)/1000)-8*3600;
                            month.setMonth(parseInt(month.getMonth())+1);
                            var month_end=(Date.parse(month)/1000)-8*3600;
                            var color;
                            //遍历
                            $.each(data,function(i,n){
                                //处理时间
                                var start=n.actbs_start_time.substr(0,10);
                                var startTime=n.actbs_start_time.substr(0,16).replace(/-/g,'/');
                                var endTime=n.actbs_end_time.substr(0,16).replace(/-/g,'/');
                                //处理颜色
                                var ST=Date.parse(n.actbs_start_time)/1000;
                                if(ST>=month_start&&ST<month_end){
                                    color='#87bef3';
                                }else{
                                    color='#c5bdbd';
                                }
                                //线上线下判断
                                var difVal="";
                                if(n.activeWay=="线上"){
                                    difVal="活动状态 :"+n.activeStatus;
                                }
                                if(n.activeWay=="线下"){
                                    if(n.activeAddress==null){
                                        n.activeAddress='';
                                    }
                                    difVal="地点 :"+n.activeAddress;
                                }
                                //权限处理
                                var editStr='';
                                var viewStr='';
                                if(editAuth=='1'){
                                    editStr="<a class='icon-edit' style='font-size:15px;position:absolute;right:0;' href='<?=Url::to(['/crm/crm-active-name/edit'])?>?nameId="+n.actbs_id+"&flag=calendar'></a>"
                                }
                                if(viewAuth=='1'){
                                    viewStr="<?=Url::to(['/crm/crm-active-name/view'])?>?nameId="+n.actbs_id+"&from=calendar";
                                }
                                //添加数据
                                arr.push({
                                    start:start,
                                    title:"<div style='word-break:break-all;'>("+n.acttype_name+")"+n.actbs_name+"</div><div>ST : "+startTime+"</div><div>ET : "+endTime+"</div><div style='position:relative;'>"+difVal+editStr+"</div>",
                                    color:color,
                                    url:viewStr
                                });
                            });
                        }
                        callback(arr);
                        //活动数量统计
                        getActiveInfo(wayId);
                        //设置菜单栏高度
                        setMenuHeight();
                        //权限处理
                        if(viewAuth!='1'){
                            $(".fc-event-container > a").css("cursor","default");
                        }
                    }
                });
            },
            //处理title内的div
            eventRender: function (event, element) {
                element.html(event.title);
            }
        });

        //活动方式
        $("#active_way").change(function(){
            wayId=$(this).val();
            $activeCalendar.fullCalendar('refetchEvents');
        });

        //跳转至
        var flag=0;
        $(document).keydown(function(even){
            if(even.keyCode==13){
                if(flag>0){
                    layer.closeAll();
                    flag=0;
                    return false;
                }
                var jumpDate=$("#jump_date").val();
                if(jumpDate==''){
                    return false;
                }
                if(/^[1-9]\d{3}-(0[1-9]|1[0-2])$/.test(jumpDate)){
                    $activeCalendar.fullCalendar('gotoDate',jumpDate);
                }else{
                    flag=1;
                    layer.alert("请输入正确的日期！",{icon:2},function(){
                        layer.closeAll();
                        flag=0;
                    });
                }
            }
        });
        $(".content button").click(function(){
            $("#jump_date").val('');
        });
    })
</script>