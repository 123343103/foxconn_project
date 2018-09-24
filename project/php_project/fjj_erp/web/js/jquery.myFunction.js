/**
 * Created by F3858995 on 2016/10/12.
 */
$(function () {
    $("button[type='submit']").on("click", function () {
        if (!$(this).prop("disabled")) {
            $(this).parents("form").submit();
            $("button[type='submit']").prop("disabled", true);
        }
        return false;
    });
    // 输入框点击隐藏提示
    var placeholder="";
    var isFocus = false;  //解决input滚动出界面 触发的blur 事件
    $(document).on("focus", "input[placeholder],textarea[placeholder]", function () {
        if($(this).prop("disabled") || $(this).prop("readonly")){
            return;
        }
        isFocus = true;
        placeholder = this.placeholder;
        this.placeholder = '';
    });
    $(document).on("blur", "input[placeholder],textarea[placeholder]", function () {
        if($(this).prop("disabled") || $(this).prop("readonly")){
            return;
        }
        if(isFocus){
            this.placeholder = placeholder;}
    });
    //兼容placeholder ---------------------------------------------
    // function isPlaceholer(){
    //     var input = document.createElement("input");
    //     return "placeholder" in input;
    // }
    //
    // if(!isPlaceholer()){ // 如果不支持placeholder属性
    //     //创建一个类
    //
    //
    //     function Placeholder(obj){
    //         this.input = obj; // obj为添加了placeholder属性的input|textarea
    //         this.label = document.createElement('label'); // 创建label标签
    //         // label标签的innerHTML设为input|textarea 的placeholder属性值。
    //         this.label.innerHTML = obj.getAttribute('placeholder');
    //         this.label.style.cssText = 'position:absolute; text-indent:4px;color:#999999; font-size:14px;';
    //         if(obj.value != ""){
    //             this.label.style.display = 'none';
    //         };
    //         this.init();
    //     }
    //     Placeholder.prototype = {
    //         //获取input|textarea的位置，以便相应的label定位
    //
    //
    //         getxy : function(obj){
    //             var left, top;
    //             if(document.documentElement.getBoundingClientRect){
    //                 var html = document.documentElement,
    //                     body = document.body,
    //                     pos = obj.getBoundingClientRect(),
    //                     st = html.scrollTop || body.scrollTop,
    //                     sl = html.scrollLeft || body.scrollLeft,
    //                     ct = html.clientTop || body.clientTop,
    //                     cl = html.clientLeft || body.clientLeft;
    //                 left = pos.left + sl - cl;
    //                 top = pos.top + st - ct;
    //             }else{
    //                 while(obj){
    //                     left += obj.offsetLeft;
    //                     top += obj.offsetTop;
    //                     obj = obj.offsetParent;
    //                 }
    //             }
    //             return{
    //                 left: left,
    //                 top : top
    //             }
    //         },
    //         //取input|textarea的宽高，将label设为相同的宽高
    //         getwh : function(obj){
    //             return {
    //                 w : obj.offsetWidth,
    //                 h : obj.offsetHeight
    //             }
    //         },
    //         //添加宽高值方法
    //         setStyles : function(obj,styles){
    //             for(var p in styles){
    //                 obj.style[p] = styles[p]+'px';
    //             }
    //         },
    //         init : function(){
    //             var label = this.label,
    //                 input = this.input,
    //                 getXY = this.getxy,
    //                 xy = this.getxy(input),
    //                 wh = this.getwh(input);
    //             this.setStyles(label, {'height':wh.h, 'lineHeight':25, 'left':xy.left, 'top':xy.top-1});
    //             document.body.appendChild(label);
    //             label.onclick = function(){
    //                 this.style.display = "none";
    //                 input.focus();
    //             }
    //             input.onfocus = function(){
    //                 label.style.display = "none";
    //             };
    //             input.onblur = function(){
    //                 if(this.value == ""){
    //                     label.style.display = "block";
    //                 }
    //             };
    //             if(window.attachEvent){
    //                 window.attachEvent("onresize",function(){// 因为label标签添加到body上，以body为绝对定位，所以当页面
    //                     var xy = getXY(input);
    //                     Placeholder.prototype.setStyles(label, {'left':xy.left+8, 'top':xy.top});
    //                 })}else{
    //                 window.addEventListener("resize",function(){
    //                     var xy = getXY(input);
    //                     Placeholder.prototype.setStyles(label, {'left':xy.left+8, 'top':xy.top});
    //                 },false);
    //             }
    //         }
    //     }
    //     var inpColl = document.getElementsByTagName('input'),
    //         textColl = document.getElementsByTagName('textarea');
    //     //html集合转化为数组
    //     function toArray(coll){
    //         for(var i = 0, a = [], len = coll.length; i < len; i++){
    //             a[i] = coll[i];
    //         }
    //         return a;
    //     }
    //     var inpArr = toArray(inpColl),
    //         textArr = toArray(textColl),
    //         placeholderArr = inpArr.concat(textArr);
    //     for (var i = 0; i < placeholderArr.length; i++){ // 分别为其添加替代placeholder的label
    //         if (placeholderArr[i].getAttribute('placeholder')){
    //             new Placeholder(placeholderArr[i]);
    //         }
    //     }
    // }
    //---------------------------------------------------------
});
function ajaxSubmitForm($form) {
    var fun = arguments[1];//表单提交之前额外验证回调
    var fun2 = arguments[2];//ajax成功回调函数
    $($form).on("beforeSubmit", function () {
        //表单额外验证回调注入
        if (typeof(fun) == "function" && !fun()) {
            $("button[type='submit']").prop("disabled", false);
            return false;
        }

        if (!$(this).form('validate')) {
            $("button[type='submit']").prop("disabled", false);
            return false;
        }

        // var id=$($form).attr('id');
        // $("button[type='submit']").prop("disabled", true);
        var options = {
            dataType: 'json',
            success: function (data) {
                if (data.flag == 1) {
                    layer.alert(data.msg, {
                        icon: 1,
                        end: function () {
                            if (data.url != undefined) {
                                parent.location.href = data.url;
                            }
                        }
                    });
                }
                if (data.flag == 0) {
                    if ((typeof data.msg) == 'object') {
                        layer.alert(JSON.stringify(data.msg), {icon: 2});
                    } else {
                        layer.alert(data.msg, {icon: 2});
                    }
                    $("button[type='submit']").prop("disabled", false);
                }
            },
            error: function (data) {
                layer.alert(data.responseText, {
                    icon: 2
                });
            }
        };

        if (typeof(fun2) == "function") {
            options.success = fun2;
        }
        $($form).ajaxSubmit(options);
        return false;
    })
}
//使左边菜单栏跟右边内容栏同步
function setMenuHeight() {
    var a = $('#menu>li').length;
    if (a < 6) {
        var height = $(document).scrollTop();
        $(".menu-list").height($('.main-content').height() - height - 102);
        $('.my-menu').css('z-index', '10');
        $('.my-menu').css('position', 'fixed');
    } else {
        $(".menu-list").height($('.main-content').height() - 102);
    }
}
//当无数据时显示无数据
function showEmpty(target, sum, n, w) {
    var w = arguments[3] == 1 ? 1 : 0;
    var opts = $(target).datagrid('options').columns;
    $(".datagrid-view2>.datagrid-body").css("overflow-x", "auto");
    var opt = opts[0];
    var code = opt[n].field;
    var num = opt.length - n;
    if (sum == 0) {
        var str = "$(target).datagrid('appendRow', {\'";
        str += code + '\':' + '\'<div style="float:left;margin-left:300px;color:red">没有相关记录！</div>\'' + "}).datagrid('mergeCells', { index: 0, field: \'" + code + "\', colspan: num })";
        eval(str);
        if (w == 1) {
            var width1 = target.parent().find('.datagrid-view2 .datagrid-htable').width();
            target.parent().find('.datagrid-body>table').width(width1);
        } else {
            target.parent().find('.datagrid-body>table').css('width', '100%');
        }

        $(target).closest('div.datagrid-wrap').find('div.datagrid-pager').hide();
        //无记录时取消点击
        $(".datagrid-td-merged,.datagrid-td-rownumber").click(function () {
            return false;
        });
    } else {
        $(target).closest('div.datagrid-wrap').find('div.datagrid-pager').show();
    }
}
//获取当前时间 格式 2017-05-20 10:20:05
function getNowFormatDate() {
    var date = new Date();
    var seperator1 = "-";
    var seperator2 = ":";
    var month = date.getMonth() + 1;
    var strDate = date.getDate();
    if (month >= 1 && month <= 9) {
        month = "0" + month;
    }
    if (strDate >= 0 && strDate <= 9) {
        strDate = "0" + strDate;
    }
    var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate
        + " " + date.getHours() + seperator2 + date.getMinutes()
        + seperator2 + date.getSeconds();
    return currentdate;
}

// /**
//  *鼠标选择事件
//  */
// function selectable(isSelect,loadUrl,load){
//     $("table:first tbody > tr").mousedown(function(e){
//         //按住ctrl同时点击鼠标左键进入
//         if(isSelect && e.which==1 && e.ctrlKey){
//             //点击选中,若已选中则删除
//             if($(this).attr('class')=='table-click-tr'){
//                 $(this).removeClass('table-click-tr');
//                 selectIdArr.splice($.inArray($(this).attr('data-key'),selectIdArr),1);
//             }else{
//                 $(this).addClass('table-click-tr');
//                 selectIdArr.push($(this).attr("data-key"));
//             }
//             //选中行不大于1时selectId有值
//             if(selectIdArr.length<1){
//                 selectId=null;
//             }else{
//                 selectId=$(".table-click-tr").attr("data-key");
//             }
//         }else{
//             //清空数组
//             $.each(selectIdArr,function(index,item){
//                 selectIdArr.splice($.inArray(item,selectIdArr),1);
//             });
//             $("table:first tbody > tr").removeClass('table-click-tr');
//             $(this).addClass('table-click-tr');
//             selectId=$(this).attr("data-key");
//             selectIdArr.push(selectId);
//         }
//             //加载子表
//             if(loadUrl &&　selectIdArr.length==1){
//                 $.ajax({
//                     type: "POST",
//                     data: {"id":selectId},
//                     url: loadUrl,
//                     success: function (data) {
//                         load.html(data);
//                     }
//                 });
//             }else{
//                 load.html('');
//             }
//
//     });
// }
/**
 * 新增
 * @param createButton
 * @param url
 * @param option
 */
function createButton(select, url, val) {
    select.on("click", function () {
        if (val != null) {
            window.location.href = url + '?id=' + val;
        } else {
            window.location.href = url;
        }
    })
}
/**
 * 删除
 * @param $delButton
 * @param url
 * @param option
 */
function deleteById($delButton, url, val, option) {
    if (option == null) {
        option = {
            alert: "请点击选择一条信息",
            confirm: "确定要删除这个吗?"
        }
    }
    $delButton.on("click", function () {
        if (val == null) {
            layer.alert(option.alert, {icon: 2, time: 5000});
        } else {
            layer.confirm(option.confirm,
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        data: {"id": val},
                        url: url,
                        success: function (msg) {
                            if (msg.flag === 1) {
                                layer.alert(msg.msg, {
                                    icon: 1, end: function () {
                                        location.reload();
                                    }
                                });
                            } else {
                                layer.alert(msg.msg, {icon: 2})
                            }
                        },
                        error: function (msg) {
                            layer.alert(msg.msg, {icon: 2})
                        }
                    })
                },
                function () {
                    layer.closeAll();
                }
            )
        }
    });
}

/**
 * 更新
 * @param $updateButton
 * @param url
 * @param option
 */
function updateById($updateButton, url, val) {
    $updateButton.on("click", function () {
        if (val != null) {
            window.location.href = url + '?id=' + val
        } else {
            return layer.alert("请点击选择一条信息", {icon: 2, time: 5000});
        }
    })
}
/**
 * 查看
 * @param $viewButton
 * @param url
 * @param option
 */
function viewById($viewButton, url, val) {
    $viewButton.on("click", function () {
        if (val != null) {
            window.location.href = url + '?id=' + val
        } else {
            return layer.alert("请点击选择一条信息", {icon: 2, time: 5000});
        }
    })
}
//级联清除选项
function clearOption($select) {
    if ($select == null) {
        $select = $("#type_1")
    }
    if ($select.next().length != 0) {
        $select.next().html('<option value=>请选择...</option>');
        clearOption($select.next());
    }
}
function clearOptionType($select) {
    var select = $select.attr('id');
    var arrNub = select.split("_");
    var Nub = parseInt(arrNub[1]) + 1;
    var nextSelect = "type_" + Nub;
    var next = $("#" + nextSelect)
    if (Nub < 7 && select != 'type_1') {
        $select.html('<option value=>请选择</option>');
        $("#type_6").html('<option value=>请选择</option>');
        clearOptionType(next);
    }
}
/*组织机构级联清除*/
function clearOptionOrg($select) {
    var select = $select.attr('id');
    var arrNub = select.split("_");
    var Nub = parseInt(arrNub[1]) + 1;
    var nextSelect = "type_" + Nub;
    var next = $("#" + nextSelect)
    if (Nub < 4 && select != 'type_1') {
        $select.html('<option value=>请选择</option>');
        $("#type_3").html('<option value=>请选择</option>');
        clearOptionOrg(next);
    }
}

function getNextTypeClass($select, url) {
    var id = $select.val();
    if (id == "") {
        return;
    }
    $.ajax({
        type: "get",
        dataType: "json",
        async: false,
        data: {"id": id},
        url: url,
        success: function (data) {
            var select = $select.attr('id');
            var arrNub = select.split("_");
            var Nub = parseInt(arrNub[1]) + 1;
            if (Nub == 7) {
                return
            }
            var nextSelect = "type_" + Nub;
            var next = $("#" + nextSelect)
            clearOptionType(next);
            next.html('<option value>请选择</option>')
            for (var x in data) {
                next.append('<option value="' + data[x].category_id + '" >' + data[x].category_sname + '</option>')
            }
        },
        error: function (data) {
            layer.alert(data.msg, {icon: 2})
        }
    })
}
/**
 * 分类级联
 * @param $select  //第一个select
 * @param url     // ,
 */
function getNextType($select, url, selectStr) {
    var id = $select.val();
    if (id == "") {
        clearOption($select);
        return;
    }
    $.ajax({
        type: "get",
        dataType: "json",
        async: false,
        data: {"id": id},
        url: url,
        success: function (data) {
            var $nextSelect = $select.next(selectStr);
            clearOption($nextSelect);
            $nextSelect.html('<option value>请选择</option>');
            if ($nextSelect.length != 0) {
                for (var x in data) {
                    $nextSelect.append('<option value="' + data[x].category_id + '" >' + data[x].category_sname + '</option>')
                }
            }
        }

    })
}

/**
 * 分类级联
 * @param $select  //第一个select
 * @param url     // ,
 */
function getCategoryType($select, url, selectStr) {
    var id = $select.val();
    if (id == "") {
        clearOption($select);
        return;
    }
    $.ajax({
        type: "get",
        dataType: "json",
        async: false,
        data: {"id": id},
        url: url,
        success: function (data) {
            var $nextSelect = $select.next(selectStr);
            clearOption($nextSelect);
            $nextSelect.html('<option value>请选择...</option>');
            if ($nextSelect.length != 0) {
                for (var x in data) {
                    $nextSelect.append('<option value="' + data[x].catg_id + '" >' + data[x].catg_name + '</option>')
                }
            }
        }

    })
}
/*组织机构级联*/
function getNextOrg($select, url, selectStr) {
    var id = $select.val();
    if (id == "") {
        clearOptionOrg($select);
        return;
    }
    $.ajax({
        type: "get",
        dataType: "json",
        async: false,
        data: {"id": id},
        url: url,
        success: function (data) {
            var $nextSelect = $select.next(selectStr);
            clearOptionOrg($nextSelect);
            $nextSelect.html('<option value>请选择</option>');
            if ($nextSelect.length != 0) {
                for (var x in data) {
                    $nextSelect.append('<option value="' + data[x].organization_id + '" >' + data[x].organization_name + '</option>')
                }
            }
        }

    })
}

//级联清除地区选项
function clearDistrictOption($select) {
    if ($select == null) {
        $select = $("#disName_1")
    }
    $tagNmae = $select.next().prop("tagName");
    if ($select.next().length != 0 && $tagNmae == 'SELECT') {
        $select.next().html('<option value=>请选择...</option>');
        clearDistrictOption($select.next());
    }
}
/**
 * 地区分类级联
 * @param $select  //第一个select
 * @param url     // "<?=Url::to(['/ptdt/firm/get-district']) ?>",
 */
function getNextDistrict($select, url, selectStr) {
    var id = $select.val();
    if (id == "") {
        clearOption($select);
        return;
    }
    $.ajax({
        type: "get",
        dataType: "json",
        async: false,
        data: {"id": id},
        url: url,
        success: function (data) {
            var $nextSelect = $select.next(selectStr);
            clearDistrictOption($nextSelect);
            $nextSelect.html('<option value>请选择...</option>');
            if ($nextSelect.length != 0)
                for (var x in data) {
                    $nextSelect.append('<option value="' + data[x].district_id + '" >' + data[x].district_name + '</option>')
                }
        }

    })
}
//
function staffInfo(obj, url) {
    var code = $(obj).val();
    if (!code) {
        return false;
    }
    $.ajax({
        type: 'GET',
        dataType: 'json',
        data: {"code": code},
        url: url,
        success: function (data) {
            $(obj).val($(obj).val().toUpperCase())
            $(".staff_code").val(data.staff_code);
            $(".staff_code_name").val(data.staff_code + '--' + data.staff_name);
            $(".staff_name").html(data.staff_name);
            $(".staff_mobile").val(data.staff_mobile);
            $(".staff_title").val(data.staff_title);
            $(".job_task").val(data.job_task);
            $(".staff_id").val(data.staff_id);
            $(".organizationid").val(data.organizationid);
            $(".organization").val(data.organization);
        },
        error: function (data) {
            // layer.alert("未找到该工号!", {icon: 2})
            $('.staff_name').html('<p style="color:red">未找到该工号</p>');
            $('.staff_code').addClass('validatebox-invalid');
        }
    })
}
/*移除模块*/
function del_module(id, obj) {
    layer.confirm("确定要删除这个模块吗?",
        {
            btn: ['确定', '取消'],
            icon: 2
        },
        function () {
            $('#' + id).css("display", "none");
            layer.closeAll();
            if (typeof(bind_del_module) == 'function') {
                bind_del_module(obj);
            }
        },
        function () {
            layer.closeAll();
        }
    )
}
/*关闭弹出层*/
function close_select() {
    parent.$.fancybox.close();
}


/**
 * 多项列表同时加载
 * @param data
 * @returns {*}
 */
function pagerFilter(data) {
    if (typeof data.total == 'number' && typeof data.splice == 'function') {    // 判断数据是否是数组
        data = {
            total: data.total,
            rows: data
        }
    }
    var dg = $(this);
    var opts = dg.datagrid('options');
    var pager = dg.datagrid('getPager');
    pager.pagination({
        onSelectPage: function (pageNum, pageSize) {
            opts.pageNumber = pageNum;
            opts.pageSize = pageSize;
            pager.pagination('refresh', {
                pageNumber: pageNum,
                pageSize: pageSize
            });
            dg.datagrid('loadData', data);
        }
    });
    if (!data.originalRows) {
        data.originalRows = (data.rows);
    }
    var start = (opts.pageNumber - 1) * parseInt(opts.pageSize);
    var end = start + parseInt(opts.pageSize);
    data.rows = (data.originalRows.slice(start, end));
    return data;
}

/**
 * 数据处理
 * @param a
 * @param b
 * @param url
 * @param id
 */
function instant_message(a, b, url, id, type) {
    var str;
    if (b.length == 0 && a == null) {
        layer.alert("请点击选择一条数据!", {icon: 2, time: 5000});
    } else if (a != null) {
        str = a.cust_id;
        $(id).attr("href", url + "?str=" + str + "&type=" + type);
        $(id).fancybox({
            padding: [],
            fitToView: false,
            width: 700,
            height: 530,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe'
        });
    } else if (b.length >= 1) {
        var arr = [];
        $.each(b, function (index, val) {
            arr.push(val.cust_id);
        })
        str = arr.join(',');
        $(id).attr("href", url + "?str=" + str + "&type=" + type);
        $(id).fancybox({
            padding: [],
            fitToView: false,
            width: 700,
            height: 530,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe'
        });
    }

}

/**
 * 数据处理 [转销售/转招商]
 * @param a
 * @param b
 * @param url
 * @param c
 */
function data_process(a, b, url, c) {
    var str;
    if (b.length == 0 && a == null) {
        layer.alert("请点击选择一条数据!", {icon: 2, time: 5000});
        return false;
    } else if (a != null) {
        str = a.cust_id;
    } else if (b.length >= 1) {
        var arr = [];
        $.each(b, function (index, val) {
            arr.push(val.cust_id);
        })
        str = arr.join(',');
    }
    var index = layer.confirm("确定要" + c + "吗?",
        {
            btn: ['确定', '取消'],
            icon: 2
        },
        function () {
            $.ajax({
                type: "get",
                dataType: "json",
                async: false,
                data: {"str": str},
                url: url,
                success: function (msg) {
                    if (msg.flag === 1) {
                        layer.alert(msg.msg, {
                            icon: 1, end: function () {
                                location.reload();
                            }
                        });
                    } else {
                        layer.alert(msg.msg, {icon: 2})
                    }
                },
                error: function (msg) {
                    layer.alert(msg.msg, {icon: 2})
                }
            })
        },
        function () {
            layer.closeAll();
        }
    )
}


/**
 * 删除
 * @param a
 * @param b
 * @param url
 */
function data_delete(id, url) {
    layer.confirm('确定要删除吗?',
        {
            btn: ['确定', '取消'],
            icon: 2
        },
        function () {
            $.ajax({
                type: "get",
                dataType: "json",
                async: false,
                data: {"id": id},
                url: url,
                success: function (msg) {
                    if (msg.flag === 1) {
                        layer.alert(msg.msg, {
                            icon: 1, end: function () {
                                location.reload();
                            }
                        });
                    } else {
                        layer.alert(msg.msg, {icon: 2})
                    }
                },
                error: function (msg) {
                    layer.alert(msg.msg, {icon: 2})
                }
            })
        },
        function () {
            layer.closeAll();
        }
    )
}

/*html 转义 -- F1678086*/
function htmlEncodeJQ(str) {
    //1.首先动态创建一个容器标签元素，如DIV
    var temp = document.createElement("div");
    //2.然后将要转换的字符串设置为这个元素的innerText(ie支持)或者textContent(火狐，google支持)
    (temp.textContent != undefined ) ? (temp.textContent = str) : (temp.innerText = str);
    //3.最后返回这个元素的innerHTML，即得到经过HTML编码转换的字符串了
    var output = temp.innerHTML;
    temp = null;
    return output;
}

// datagrid 溢出显示省略号  鼠标悬浮提示
function datagridTip(select) {
    $(select).datagrid('getPanel').find('div.datagrid-body td div.datagrid-cell').find('i').closest('.datagrid-cell').addClass('no-tip');
    var cells = $(select).datagrid('getPanel').find('div.datagrid-body td div.datagrid-cell').not('.no-tip');
    cells.css({'overflow': 'hidden', 'text-overflow': 'ellipsis'});
    cells.tooltip({
        content: function () {
            // console.log(this);
            return $(this).html();
        }
    });
}
/* 鼠标经过显示全部 */
$('input,textarea').mouseover(function () {
    this.title = this.value
})
$('span,td,th').mouseover(function () {
    this.title = $(this).text();
})

/*提醒事项关闭*/
function reminderClose(id, url) {
    var index = layer.confirm("确定要关闭这条提醒?",
        {
            btn: ['确定', '取消'],
            icon: 2
        },
        function () {
            $.ajax({
                type: "get",
                dataType: "json",
                async: false,
                data: {"id": id},
                url: url,
                success: function (msg) {
                    if (msg.flag === 1) {
                        layer.alert(msg.msg, {
                            icon: 1, end: function () {
                                location.reload();
                            }
                        });
                    } else {
                        layer.alert(msg.msg, {icon: 2})
                    }
                },
                error: function (msg) {
                    layer.alert(msg.msg, {icon: 2})
                }
            })
        },
        function () {
            layer.closeAll();
        }
    )
}

// 统计字符数 用于输入框输入字符数实时提醒 （需求变化 暂时不用）
function characterCount(total, inputObj, countObj) {
    var inputObj = arguments[1] ? arguments[1] : $('.input-obj');
    var countObj = arguments[2] ? arguments[2] : $('.count-obj');
    if (inputObj.val().length <= total) {
        countObj.text(inputObj.val().length + '/' + total);
    } else {
        countObj.text(total + '/' + total);
        inputObj.val(inputObj.val().substr(0, total, inputObj.val()));
    }
}
/*客户基本信息模糊搜索带出数据*/
function searchCust(cust_sname, url, url2, callback) {
    var $cust_sname = $(cust_sname);
    //关闭浏览器提供的输入框自动补全功能
    $cust_sname.attr('autocomplete', 'off');
    //创建自动显示的下拉菜单,用于显示查询后服务器返回的数据
    var $autocomplete = $('<div class="auto"></div>').hide().insertAfter(cust_sname);
    //清空下拉菜单并隐藏
    var clear = function () {
        $autocomplete.empty().hide();
    };
    //注册事件,当输入框失去焦点时清空下拉框并隐藏
    $cust_sname.blur(function () {
        setTimeout(clear, 500);
    })
    //下拉列表中高亮的项目的索引，当显示下拉列表项的时候，移动鼠标或者键盘的上下键就会移动高亮的项目
    var selectedItem = null;
    //timeout的ID
    var timeoutid = null;
    //设置下拉菜单的高亮背景
    var setSelectedItem = function (item) {
        //更新索引变量
        selectedItem = item;
        //按上下键是循环显示、选中,小于0就置成最大的值,大于最大值就置成0
        if (selectedItem < 0) {
            selectedItem = $autocomplete.find('li').length - 1;
        } else if (selectedItem > $autocomplete.find('li').length - 1) {
            selectedItem = 0;
        }
        //首先移除其他列表项的高亮背景，然后再高亮当前索引的背景
        $autocomplete.find('li').removeClass('highlight').eq(selectedItem).addClass('highlight');
    };
    //ajax 查询数据库数据
    var ajax_check = function () {
        var data = $cust_sname.val();
        $.ajax({
            type: 'POST',
            url: url + '?data=' + data,
            dataType: 'json',
            success: function (data) {
                if (data.length) {
                    $.each(data, function (index, term) {
                        $('<li></li>').text(term.cust_sname).appendTo($autocomplete).hover(function () {
                            //下拉列表每一项的事件，鼠标移进去的操作
                            $(this).siblings().removeClass('highlight');
                            $(this).addClass('highlight');
                            selectedItem = index;
                        }, function () {
                            //下拉列表每一项的事件，鼠标离开的操作
                            $(this).removeClass('highlight');
                            //当鼠标离开时索引置-1，当作标记
                            selectedItem = -1;
                        }).click(function () {
                            //鼠标单击下拉列表的这一项的话，就将这一项的值添加到输入框中
                            callback(term);
                            //清空并隐藏下拉列表
                            $autocomplete.empty().hide();
                        })
                    })
                    setSelectedItem(0);
                    $autocomplete.show();
                }
            }
        })
    }
    $cust_sname.keyup(function (event) {
        if (event.keyCode > 40 || event.keyCode == 8 || event.keyCode == 32) {
            //首先删除下拉列表中的信息
            $autocomplete.empty().hide();
            clearTimeout(timeoutid);
            timeoutid = setTimeout(ajax_check, 100);
        } else if (event.keyCode == 38) {
            //上
            //selectedItem = -1 代表鼠标离开
            if (selectedItem == -1) {
                setSelectedItem($autocomplete.find('li').length - 1);
            } else {
                //索引减1
                setSelectedItem(selectedItem - 1);
            }
            event.preventDefault();
        } else if (event.keyCode == 40) {
            //下
            //selectedItem = -1 代表鼠标离开
            if (selectedItem == -1) {
                setSelectedItem(0);
            } else {
                //索引加1
                setSelectedItem(selectedItem + 1);
            }
            event.preventDefault();
        }
    }).keypress(function (event) {
        //enter键
        if (event.keyCode == 13) {
            //列表为空或者鼠标离开导致当前没有索引值
            if ($autocomplete.find('li').length == 0 || selectedItem == -1) {
                return false;
            }
//                $cust_sname.val($autocomplete.find('li').eq(selectedItem).text()).validatebox('remove').validatebox({required:false});
            $.ajax({
                type: 'POST',
                url: url2 + '?data=' + $autocomplete.find('li').eq(selectedItem).text(),
                dataType: 'json',
                success: function (term) {
                    callback(term);
                }
            })
            $autocomplete.empty().hide();
            event.preventDefault();
        }
    }).keydown(function (event) {
        //esc键
        if (event.keyCode == 27) {
            $autocomplete.empty().hide();
            event.preventDefault();
        }
    });
}
/*分期时间选择 -- start --*/
function select_time(id,num){
    var time = $('#select_time_'+(id-1)).val();
    if(time == ''){
        layer.alert("请先选择上一期还款时间", {icon: 2});return false;
    }
    var mi = checkMinDate(id,num);
//        var ma = checkMaxDate(id,num);
    WdatePicker({
        skin: 'whyGreen',
        dateFmt: 'yyyy-MM-dd',
        isShowClear:false,
        minDate:mi,
        maxDate:['#F{checkMaxDate('+ id + ',' + num +')}']
    });
}
/*最小时间控制*/
function checkMinDate(id,num){
    if(id == 1) {
        /*获取当前时间作为第一期还款时间最小时间限制*/
        var mydate = new Date();
        var str = "" + mydate.getFullYear() + "-";
        str += (mydate.getMonth() + 1) + "-";
        str += mydate.getDate();
        return str;
    }else if(id <= num && id != 1){
        var time = $('#select_time_'+(id-1)).val();
        /*判断上一期是否有还款日期*/
        return time;
    }
}

/*最大时间控制*/
function checkMaxDate(id,num){
    var time = $('#select_time_'+(id+1)).val();
    if(time == ''){
        return '';
    }else{
        if(id == 1){
            return time;
        }else if(id<num){
            return time;
        }
    }
}
/*分期时间选择 -- start --*/
/**
 * textarea 输入限制字数 (跟上面的characterCount函数功能相同  根据需求变化  不需要这个统计字符的功能了)
 * @param event
 */
// function surplus(event,num){
//     var content = event.value;
//     $(event).next('span').html(content.length+'/'+num);
//     if(content.length>num){
//         $(event).attr({maxlength:num});
//         $(event).next('span').html('最多只能输入'+ num +'个字符');
//     }
// }

/*! http://mths.be/placeholder v2.0.7 by @mathias */
$(function(){ $('input, textarea').placeholder(); });
/*兼容IE9下placeholder*/
(function(f, h, $) {
    var a = 'placeholder' in h.createElement('input'),
        d = 'placeholder' in h.createElement('textarea'),
        i = $.fn,
        c = $.valHooks,
        k, j;
    if (a && d) {
        j = i.placeholder = function() {
            return this
        };
        j.input = j.textarea = true
    } else {
        j = i.placeholder = function() {
            var l = this;
            l.filter((a ? 'textarea' : ':input') + '[placeholder]').not('.placeholder').bind({
                'focus.placeholder': b,
                'blur.placeholder': e
            }).data('placeholder-enabled', true).trigger('blur.placeholder');
            return l
        };
        j.input = a;
        j.textarea = d;
        k = {
            get: function(m) {
                var l = $(m);
                return l.data('placeholder-enabled') && l.hasClass('placeholder') ? '' : m.value
            },
            set: function(m, n) {
                var l = $(m);
                if (!l.data('placeholder-enabled')) {
                    return m.value = n
                }
                if (n == '') {
                    m.value = n;
                    if (m != h.activeElement) {
                        e.call(m)
                    }
                } else {
                    if (l.hasClass('placeholder')) {
                        b.call(m, true, n) || (m.value = n)
                    } else {
                        m.value = n
                    }
                }
                return l
            }
        };
        a || (c.input = k);
        d || (c.textarea = k);
        $(function() {
            $(h).delegate('form', 'submit.placeholder', function() {
                var l = $('.placeholder', this).each(b);
                setTimeout(function() {
                    l.each(e)
                }, 10)
            })
        });
        $(f).bind('beforeunload.placeholder', function() {
            $('.placeholder').each(function() {
                this.value = ''
            })
        })
    }
    function g(m) {
        var l = {},
            n = /^jQuery\d+$/;
        $.each(m.attributes, function(p, o) {
            if (o.specified && !n.test(o.name)) {
                l[o.name] = o.value
            }
        });
        return l
    }
    function b(m, n) {
        var l = this,
            o = $(l);
        if (l.value == o.attr('placeholder') && o.hasClass('placeholder')) {
            if (o.data('placeholder-password')) {
                o = o.hide().next().show().attr('id', o.removeAttr('id').data('placeholder-id'));
                if (m === true) {
                    return o[0].value = n
                }
                o.focus()
            } else {
                l.value = '';
                o.removeClass('placeholder');
                l == h.activeElement && l.select()
            }
        }
    }
    function e() {
        var q, l = this,
            p = $(l),
            m = p,
            o = this.id;
        if (l.value == '') {
            if (l.type == 'password') {
                if (!p.data('placeholder-textinput')) {
                    try {
                        q = p.clone().attr({
                            type: 'text'
                        })
                    } catch (n) {
                        q = $('<input>').attr($.extend(g(this), {
                            type: 'text'
                        }))
                    }
                    q.removeAttr('name').data({
                        'placeholder-password': true,
                        'placeholder-id': o
                    }).bind('focus.placeholder', b);
                    p.data({
                        'placeholder-textinput': q,
                        'placeholder-id': o
                    }).before(q)
                }
                p = p.removeAttr('id').hide().prev().attr('id', o).show()
            }
            p.addClass('placeholder');
            p[0].value = p.attr('placeholder')
        } else {
            p.removeClass('placeholder')
        }
    }
}(this, document, jQuery));
