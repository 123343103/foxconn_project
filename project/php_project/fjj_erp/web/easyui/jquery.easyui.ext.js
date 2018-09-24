
//函数作用:html实体解码
//使用方法:String.decode()
String.prototype.decode=function(){
    return $("<div>"+this+"</div>").text();
}
$.extend($.fn.validatebox.methods, {
    remove: function(jq){
        return jq.each(function(){
            $(this).removeClass("validatebox-text validatebox-invalid").unbind('focus').unbind('blur');
        });
    },
    reduce: function(jq){
        return jq.each(function(){
            var opt = $(this).data().validatebox.options;
            $(this).addClass("validatebox-text").validatebox(opt);
        });
    }
});

$.extend($.fn.validatebox.defaults.editors, {
    text:{
        init:function(container,options){
            var input = $('<input type="text" class="datagrid-editable-input">').appendTo(container);
            return input;
        }
    }
});
$.extend($.fn.validatebox.defaults.rules, {
    staff:{
        validator:function(value){
            return /^[A-Za-z]\d{7}$/.test($.trim(value));
        },
        message:'工号格式错误!'
    },
    checkCode: {
        validator: function (value, param) {
            var flag;
            var str = $.trim(value).split("--");
//                    console.log(str[0]);return false;
            $.ajax({
                url: $(this).data("url"),
                type: 'GET',
                data: {"code": str[0]},
                async: false,
                dataType: 'json',
                success: function (data, status) {
                    if (data.length === 0) {
                        // $.fn.validatebox.defaults.rules.check_staff.message= '员工表查无此人';
                        flag = false;
                    } else {
                        $('.staff_code').val(data.staff_code);
                        $(".staff_code_name").val(data.staff_code + '--' + data.staff_name);
                        flag = true;
                    }
                }
            });

            if (flag) {
                $('.staff_code_name').addClass('validatebox-invalid');
            }
            return flag;
        },
        message: '工号不存在'
    },
    maxLength: {
        validator: function(value, param){
            return param[0] >= $.trim(value).length;
        },
        message: '最多输入{0}个字'
    },
    check_staff: {
        validator: function (value, param) {
            var flag=true;
            // if(!/^[A-Za-z]\d{7}$/.test(value)){
            //     $.fn.validatebox.defaults.rules.check_staff.message ="工号格式错误";
            //     flag = false;
            // }
            $.ajax({
                url:$(this).data("url"),
                data:{"code":$.trim(value)},
                async:false,
                dataType:'json',
                success:function(data,status){
                    if(data.length === 0){
                        $.fn.validatebox.defaults.rules.check_staff.message= '员工表查无此人';
                        flag=false;
                    }
                }
            });
            return flag;
        },
        message: ''
    },
    number:{
        validator: function (value) {
            return /^[+]?[1-9]\d*$/.test($.trim(value));
        },
        message: '请输入有效数字'
    },
    price:{
        validator : function(value){
            return /^\d+(\.\d{1,3})?$/.test($.trim(value));
        },
        message : '请输入有效数字,且最多输入三位小数！'
    },
    int:{
        validator: function (value) {
            return /^[+]?[0-9]\d*$/.test($.trim(value));
        },
        message: '请输入有效整数'
    },
    intnum:{
        validator: function (value) {
            return /^(?!0+(?:\.0+)?$)(?:[1-9]\d*|0)(?:\.\d{1,2})?$/.test($.trim(value));
        },
        message: '输入数量大于0不能超过2位小数'
    },
    // numoren:{
    //     validator: function (value) {
    //         return /^(?![0-9]+$)(?![a-z]+$)(?![A-Z]+$)(?![,\.#!@&%￥'\+\*\-:;^_`]+$)[,\.#!@&%￥'\+\*\-:;^_`0-9A-Za-z]{2,20}$/.test($.trim(value));
    //     },
    //     message: '请输入数字,字母,符号至少两种组合的字符'
    // },
    numoren:{
        validator: function (value) {
            return /^(?!\d+$)[\da-zA-Z]{1}[-A-Za-z\d]*$/.test($.trim(value));
        },
        message: '不能输入纯数字或开头不可用"-"及其他特殊符号'
    },
    cknumoren:{
        validator: function (value) {
            return /^(?!\d+$)[\da-zA-Z]*$/.test($.trim(value));
        },
        message: '不能输入纯数字或其他特殊符号'
    },
    positive:{
        validator: function (value) {
            return /^[0-9]*[1-9][0-9]*$/.test($.trim(value));
        },
        message: '请输入正整数'
    },
    percent:{
        validator: function (value) {
            return /^((\d+\.?\d*)|(\d*\.\d+))\%$/.test($.trim(value));
        },
        message: '请输入有效百分比'
    },
    float:{
        validator : function(value){
            // return /^(\d?\d(\.\d{1,2})?|100)$/.test(value);
             return /^\d?\d(\.\d{1,2})?$/.test($.trim(value));
        },
        message : '请输入有效百分比,且最多输入两位小数！'
    },
    three_percent:{
        validator : function(value){
            // return /^(\d?\d(\.\d{1,2})?|100)$/.test(value);
            return /^\d?\d(\.\d{1,3})?$/.test($.trim(value));
        },
        message : '请输入有效百分比,且最多输入三位小数！'
    },
    two_percent:{
        validator : function(value){
            return /^(\d?\d(\.\d{1,2})?|100)$/.test(value);
            // return /^\d?\d(\.\d{1,2})?$/.test($.trim(value));
        },
        message : '请输入有效百分比,且最多输入两位小数！'
    },
    two_decimal:{
        validator : function(value){
            return /^\d+(\.\d{1,2})?$/.test($.trim(value));
        },
        message : '请输入数字,且最多输入两位小数！'
    },
    five_decimal:{
        validator : function(value){
            return /^[1-9]([0-9]{1,11})?([.][0-9]{1,5})?$/.test($.trim(value));
        },
        message : '请输入数字,且最多输入五位小数！'
    },
    six_decimal:{
        validator : function(value){
            return /^[0-9]{1,11}([.][0-9]{1,6})?$/.test($.trim(value));

        },
        message : '请输入正确数字,且最多输入六位小数！'
    },
    noZero: {
        validator: function (value) {
            if (value <= 0) {
                $.fn.validatebox.defaults.rules.noZero.message = '不能小于等于0';
            }
            return (value > 0);
        },
        message: '不能小于等于0'
    },
    //值不能重复
    same:{
        validator: function (value,param) {
            var ary=[];
            $(param).each(function(i,item){
                ary[i]=$(item).val();
            });

            var nary=ary.sort();
            for(var i=0;i<ary.length;i++){
                if (nary[i]==nary[i+1]){
                    return false;
                }
            }
            return true
        },
        message: '值不能重复'
    },

    accompanySame:{
        validator:function(value){
            var trs=$(this).parents("tr").siblings();
            $.each(trs,function(i,n){
                if($(n).find("input:first").val()==$.trim(value)){
                    trs='same';
                    return false;
                }
            });
            return trs!='same';
        },
        message:'工号重复'
    },

    credit_type:{
        validator:function(value){
            var trs=$(this).parents("tr").siblings();
            $.each(trs,function(i,n){
                if($(n).find("select:first").val()==$.trim(value)){
                    trs='same';
                    return false;
                }
            });
            return trs!='same';
        },
        message:'信用额度类型重复'
    },

    mobile:{
        validator: function(value){
            return $.trim(value).match(/^1[3|4|5|7|8][0-9]\d{8}$/);
        },
        message: '手机号码格式错误'
    },
    telphone:{
        validator: function(value){
            return $.trim(value).match(/^\d{3,4}[-]{1}?\d{7,8}$/);
        },
        message: '电话号码格式错误'
    },
    // fax:{
    //     validator: function(value){
    //         return $.trim(value).match(/^0\d{2,3}-?\d{7,8}$/);
    //     },
    //     message: '传真格式错误'
    // },
    //10/27 F1678086
    fax:{
        validator: function(value){
            return $.trim(value).match(/^\d{3,4}[-]{1}-?\d{7,8}$/);
        },
        message: '传真格式错误'
    },
    tel_mobile:{
        validator: function(value){
            return $.trim(value).match( /^((\+?86)|(\(\+86\)))?\d{3,4}-?\d{7,8}(-\d{3,4})?$|^((\+?86)|(\(\+86\)))?1\d{10}$/);
        },
        message: '电话或者手机号码格式错误'
    },
    tel_mobile_c:{
        validator: function(value){
            return $.trim(value).match(/^((\+?86)|(\(\+86\)))?\d{3,4}-?\d{7,8}(-\d{3,4})?$|^((\+?86)|(\(\+86\)))?1\d{10}?$|(\d{3}-\d{5}$)/);
        },
        message: '电话或者手机号码格式错误'
    },
    //唯一性验证
    unique:{
        validator:function(value,param){
            //验证属性
            var attr=$(this).data("attr");
            var scenario=typeof($(this).data("scenario"))=="undefined"?"default":$(this).data("scenario");
            //属性惟一标识
            var id=typeof($(this).data("id"))=="undefined"?"":$(this).data("id");
            //属性值
            var val=$.trim($(this).val());
            if(val==""){
                return false;
            }
            var flag=true;
            $.ajax({
                url:$(this).data("act"),
                data:{"id":id,'attr':attr,"val":val,"scenario":scenario},
                async:false,
                dataType:'json',
                success:function(data,status){
                    if(data){
                        $.fn.validatebox.defaults.rules.unique.message=data;
                        flag=false;
                    }
                }
            });
            return flag;
        },
        message:"该输入项为必填项"
    },
    //唯一性验证
    exist:{
        validator:function(value,param){
            //验证属性
            var attr=$(this).data("attr");
            var scenario=typeof($(this).data("scenario"))=="undefined"?"default":$(this).data("scenario");
            //属性惟一标识
            var id=typeof($(this).data("id"))=="undefined"?"":$(this).data("id");
            //属性值
            var val=$.trim($(this).val());
            if(val==""){
                return false;
            }
            var flag=true;
            $.ajax({
                url:$(this).data("act"),
                data:{"id":id,'attr':attr,"val":val,"scenario":scenario},
                async:false,
                dataType:'json',
                success:function(data,status){
                    if(data){
                        $.fn.validatebox.defaults.rules.exist.message=data;
                        flag=false;
                    }
                }
            });
            return flag;
        },
        message:"该输入项为必填项"
    },
    //邮编验证
    postcode:{
        validator:function(value){
            return $.trim(value).match(/[1-9]\d{5}(?!\d)/);
        },
        message:'邮编格式错误'
    },
    //域名www验证
    www:{
        validator:function(value){
            return /^(?=^.{3,255}$)(http(s)?:\/\/)?[www.]{4}[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+(:\d+)*(\/\w+\.\w+)*$/.test($.trim(value));
        },
        message:"请输入有效网址"
    },
    //职员工号验证并赋值-郭文聪
    staffCode:{
        validator:function(value){
            value=$.trim(value).split('--');
            var str=$.ajax({
                        url:$(this).data().url,
                        data:{"code":value[0]},
                        async:false,//必须同步
                        cache:false
                    }).responseText;
            if(str==''){
                return false;
            }
            staffObj=JSON.parse(str);
            //找到当前元素的父元素
            var $parentElem=$(this).parent();
            if($parentElem[0].tagName=='TD'){
                $parentElem=$parentElem.parent();
            }else{
                $(this).val(value[0]+'--'+staffObj.staff_name);
                $parentElem.find(".staff_code").val(value[0]);
            }
            //职员id
            $staff_id=$parentElem.find(".staff_id");
            if($staff_id.length>0&&$staff_id[0].tagName=='INPUT'){
                $staff_id.val(staffObj.staff_id);
            }else{
                $staff_id.text(staffObj.staff_id);
            }
            //职员姓名
            $staff_name=$parentElem.find(".staff_name");
            if($staff_name.length>0&&$staff_name[0].tagName=='INPUT'){
                $staff_name.val(staffObj.staff_name);
            }else{
                $staff_name.text(staffObj.staff_name);
            }
            //职员职位
            $job_task=$parentElem.find(".job_task");
            if($job_task.length>0&&$job_task[0].tagName=='INPUT'){
                $job_task.val(staffObj.position);
            }else{
                $job_task.text(staffObj.position);
            }
            //职员电话
            $staff_mobile=$parentElem.find(".staff_mobile");
            if($staff_mobile.length>0&&$staff_mobile[0].tagName=='INPUT'){
                if($staff_mobile.val() == ''){
                    $staff_mobile.val(staffObj.staff_mobile);
                }
            }else{
                $staff_mobile.text(staffObj.staff_mobile);
            }
            //职员部门
            $staff_organization=$parentElem.find(".staff_organization");
            if($staff_organization.length>0&&$staff_organization[0].tagName=='INPUT'){
                $staff_organization.val(staffObj.organization);
            }else{
                $staff_organization.text(staffObj.staff_mobile);
            }
            //职员邮箱
            $staff_email=$parentElem.find(".staff_email");
            if($staff_email.length>0&&$staff_email[0].tagName=='INPUT'){
                $staff_email.val(staffObj.staff_email);
            }else{
                $staff_email.text(staffObj.staff_email);
            }
            return true;
        },
        message:'工号不存在'
    },
    //行td值重复验证-郭文聪
    tdSame:{
        validator:function(value){
            var trs=$(this).parents("tr").siblings();
            $.each(trs,function(i,n){
                if($(n).find("input[type='text']:first").val().toUpperCase()==$.trim(value).toUpperCase()){
                    trs='same';
                    return false;
                }
            });
            return trs!='same';
        },
        message:'已存在'
    },
    startSize:{
        validator:function(value,param){
            return param[0]>0;
        },
        message:'开始时间不能小于当前时间'
    },
    reminders:{
        validator:function(value,param){
            return (param[0]>0 && (new Date(value.replace(/-/g,"/")).getTime()+59999) > new Date().getTime());
        },
        message:'开始时间不能小于当前时间,结束时间不能小于开始时间'
    },
    timeCompare:{
        validator:function(value,param){
            var time1=$(this).val();
            var time2=$($(this).data("target")).val();
            if(time1=="" || time2==""){
                return true;
            }
            $($(this).data("target")).removeClass("validatebox-text validatebox-invalid").unbind('focus').unbind('blur');
            var diff=Date.parse(time1)-Date.parse(time2);
            var type=$(this).data("type")=="undefined"?"le":$(this).data("type");
            switch(type){
                case "le":
                    $.fn.validatebox.defaults.rules.timeCompare.message='开始时间必须小于等于结束时间';
                    return Date.parse(time1)-Date.parse(time2)<=0;
                    break;
                case "ge":
                    $.fn.validatebox.defaults.rules.timeCompare.message='结束时间必须大于等于开始时间';
                    return Date.parse(time1)-Date.parse(time2)>=0;
                    break;
            }
        },
        message:'结束时间必须大于等于开始时间'
    },
    //ip验证
    ip:{
        validator:function(value){
            return $.trim(value).match(/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/);
        },
        message:'ip错误'
    },
    import:{
        validator:function(value){
            var extensions=[".xls",".xlsx"];
            var ext=$(this).val();
            var message="允许如下文件类型:<br />"+extensions.join(",");
            var flag=false;
            for(var x=0;x<extensions.length;x++){
                if(ext.substring(ext.lastIndexOf("."))==extensions[x]){
                    flag=true;
                    break;
                }
            }
            $.fn.validatebox.defaults.rules.import.message=message;
            return flag;
        },
    },
    //仓库代码验证--add by jh//
    wareHouse:{
        validator:function(value){
            var str=$.ajax({
                url:$(this).data().url,
                data:{"id":$.trim(value)},
                async:false,//必须同步
                cache:false
            }).responseText;
            str=$.trim(str);
            var $parentElem=$(this).parent();
            if($parentElem[0].tagName=='TD'){
                $parentElem=$parentElem.parent();
            }
            if(str==''){
                return true;
            }
            return false;
        },
        message:'此仓库代码已经存在，请重新输入！'
    },
    //---end仓库代码验证--//
    gt:{
        validator:function(value,param){
            return param[0]>=0;
        },
        message:'不能大于剩余未出数量'
    },
    tel_fzz:{
        validator:function (value) {
            return  /^(400)-?\d{7}$/.test($.trim(value));
        },
        message:'400电话格式错误'
    },
    decimal: {
        validator: function(value, param){
            var a=parseInt(param[0]);
            var b=parseInt(param[1]);
            if(!$.isNumeric(value.trim())){
                $.fn.validatebox.defaults.rules.decimal.message='请输入有效数字';
                return false;
            }
            var pattern=new RegExp("^[0-9]{1,"+a+"}([.][0-9]{0,"+b+"})?$");
            if(!pattern.test(value.trim())){
                console.log(pattern);
                $.fn.validatebox.defaults.rules.decimal.message='整数位最大长度为'+a+',且最多输入'+b+'位小数！';
                return false;
            }
            return true;
        }
    },
    //邮箱验证
    email: {
        validator: function (value) {
            return /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test($.trim(value));
        }, message: "Please enter a valid email address."
    },
    qq:{
        validator:function(value){
            return /^[1-9]*[1-9][0-9]*$/.test($.trim(value));
        },
        message:"格式错误"
    },
    checksql:{
        validator: function (value) {
            // return /^(?!.*(update |delete|drop|insert|create|alert|truncate|exec)).*$/.test($.trim(value));
            return /^(?!.*([Uu][Pp][Dd][Aa][Tt][Ee] |[Dd][Ee][Ll][Ee][Tt][Ee] |[Dd][Rr][Oo][Pp] |[Ii][Nn][Ss][Ee][Rr][Tt] |[Cc][Rr][Ee][Aa][Tt][Ee] |[Aa][Ll][Ee][Rr][Tt] |[Tt][Rr][Uu][Nn][Cc][Aa][Tt][Ee] |[Ee][Xx][Ee][Cc] )).*$/.test($.trim(value.replace(/[\r\n]/g,'')));
        },
        message: '输入SQL只能包含关键字SELECT'
    },
    confirmEnding:{
        validator: function (value) {
            // return /^(?!.*(update |delete|drop|insert|create|alert|truncate|exec)).*$/.test($.trim(value));
            if(!$.trim(value.replace(/[\r\n]/g,'')).endsWith(';')){
                $.fn.validatebox.defaults.rules.confirmEnding.message='请以;结尾';
                return false;
            }
            return true;
        }
    },
});
