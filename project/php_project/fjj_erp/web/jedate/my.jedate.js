/**
 * Created by F1677929 on 2017/9/30.
 */
$(function(){
    //日期选择
    $(".select-date").click(function () {
        $(this).jeDate({
            format:"YYYY-MM-DD",
            zIndex:5 //菜单栏弹出层的层级为7(myMenu.css)
        });
    }).click();

    //时间选择
    $(".select-time").click(function () {
        $(this).jeDate({
            format:"hh:mm",
            zIndex:5
        });
    }).click();

    //日期时间选择
    $(".select-date-time").click(function () {
        $(this).jeDate({
            format:"YYYY-MM-DD hh:mm",
            zIndex:5
        });
    }).click();

    //年月选择
    $(".select-month").click(function () {
        $(this).jeDate({
            format:"YYYY-MM",
            zIndex:5
        });
    }).click();
});
