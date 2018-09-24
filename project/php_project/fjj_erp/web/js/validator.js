//var validateFunction = {};

var validateRegExp = {
    decmal: "^([+-]?)\\d*\\.\\d+$",	//浮点数
    decmal1: "^[1-9]\\d*.\\d*|0.\\d*[1-9]\\d*$",	//正浮点数
    decmal2: "^-([1-9]\\d*.\\d*|0.\\d*[1-9]\\d*)$",	//负浮点数
    decmal3: "^-?([1-9]\\d*.\\d*|0.\\d*[1-9]\\d*|0?.0+|0)$",	//浮点数
    decmal4: "^[1-9]\\d*.\\d*|0.\\d*[1-9]\\d*|0?.0+|0$",	//非负浮点数（正浮点数 + 0）
    decmal5: "^(-([1-9]\\d*.\\d*|0.\\d*[1-9]\\d*))|0?.0+|0$",	//非正浮点数（负浮点数 + 0）
    intege: "^-?[1-9]\\d*$",	//整数
    intege1: "^[1-9]\\d*$",	//正整数
    intege2: "^-[1-9]\\d*$",	//负整数
    num: "^([+-]?)\\d*\\.?\\d+$",	//数字
    num1: "^[1-9]\\d*|0$",	//正数（正整数 + 0）
    num2: "^-[1-9]\\d*|0$",	//负数（负整数 + 0）
    ascii: "^[\\x00-\\xFF]+$",	//仅ACSII字符
    chinese: "^[\\u4e00-\\u9fa5]+$",	//仅中文
    date: "^\\d{4}(\\-|\\/|\.)\\d{1,2}\\1\\d{1,2}$",	//日期
    email: "^\\w+((-\\w+)|(\\.\\w+)|(/\\w+))*\\@[A-Za-z0-9]+((\\.|-)[A-Za-z0-9]+)*\\.[A-Za-z0-9]+$",
    //email: "^\\w+((-\\w+)|(\\.\\w+))*\\@[A-Za-z0-9]+((\\.|-)[A-Za-z0-9]+)*\\.[A-Za-z0-9]+$",
    //email: "^\\w+(-|/|\|.|@|\w)*$",
    idcard: "^[1-9]([0-9]{14}|[0-9]{17})$",
    mobile: "0?(13|14|15|18|17)[0-9]{9}$",	//手机，新增手機段17
    notempty: "^\\S+$",	//非空
    password: "^[A-Za-z0-9_-]+$",//密码
    picture: "(.*)\\.(jpg|bmp|gif|ico|pcx|jpeg|tif|png|raw|tga)$",	//图片
    qq: "^[1-9]*[1-9][0-9]*$",
    tel: "^[0-9\-()（）]{7,18}$",
    url: "^http[s]?:\\/\\/([\\w-]+\\.)+[\\w-]+([\\w-./?%&=]*)?$",	//url
    username: "^[A-Za-z0-9_\\-\\u4e00-\\u9fa5]+$",	//字母、數字、中文
    zipcode: "^\\d{6}$",//6位數字驗証碼
    realname: "^[A-Za-z0-9\\u4e00-\\u9fa5]+$",
    date_time: "^(?:(?:1[6-9]|[2-9][0-9])[0-9]{2}([-/.]?)(?:(?:0?[1-9]|1[0-2])\1(?:0?[1-9]|1[0-9]|2[0-8])|(?:0?[13-9]|1[0-2])\1(?:29|30)|(?:0?[13578]|1[02])\1(?:31))|(?:(?:1[6-9]|[2-9][0-9])(?:0[48]|[2468][048]|[13579][26])|(?:16|[2468][048]|[3579][26])00)([-/.]?)0?2\2(?:29))$",
    address: "[\u4E00-\u9FA5]{10,}$",//最少10位中文
    Phone: "((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)",//区号3-4位，电话号码7-8位，只能包含”(“、“）”、“-”和数字
    //F1664098lms 添加新的验证
    NewPhone: "^(([0/+]\d{2,3}-)?(0\d{2,4})-)(\d{7,8})(-(\d{3,}))?$",
    NewAddress: "^\s*?[\u4E00-\u9FA5 0-9a-zA-Z-_——#\(\（\）\)]{1,35}$",
    NewZipcode: "^[1-9][0-9]{6}$",//六位
    NewAccountcode: "^\s*?[\u4E00-\u9FA5]{1,5}$",
    activityCode: "[^0-9a-zA-Z]", //包含MKT活动代码只能输入数字和字母
    CodeId: "^[A-Za-z0-9_]+$"	//字母、數字、下劃線
};

var validateRules = {
    isNull: function (str) {
        return (str == "" || typeof str != "string");
    },
    betweenLength: function (str, _min, _max) {
        return (str.length >= _min && str.length <= _max);
    },
    isUserid: function (str) {
        return new RegExp(validateRegExp.username).test(str);
    },
    isPwd: function (str) {
        return new RegExp(validateRegExp.password).test(str);
    },
    isPwd2: function (str1, str2) {
        return (str1 == str2);
    },
    isEmail: function (str) {
        //是否滿足郵箱格式
        return new RegExp(validateRegExp.email).test(str);
    },
    isTel: function (str) {
        return new RegExp(validateRegExp.tel).test(str);
    },
    isMobile: function (str) {
        return new RegExp(validateRegExp.mobile).test(str);
    },
    checkType: function (element) {
        return (element.attr("type") == "checkbox" || element.attr("type") == "radio" || element.attr("rel") == "select");
    },
    isChinese: function (str) {
        return new RegExp(validateRegExp.chinese).test(str);
    },
    isRealName: function (str) {
        return new RegExp(validateRegExp.realname).test(str);
    },
    isZipCode: function (str) {
        return new RegExp(validateRegExp.zipcode).test(str);
    },
    isDate: function (str) {
        if (str.length < 8)
            return false;

        var year = str.substr(0, 4);
        var month = str.substr(4, 2);
        var day = str.substr(6, 2);

        if (!parseInt(month, 10))
            return false;

        var date = new Date(Date.parse(year + "/" + month + "/" + day));
        return (typeof (date) == "object" && year == date.getFullYear() && month == date.getMonth() + 1 && day == date.getDate());
    },

    isDateTime: function (str) {
        return new RegExp(validateRegExp.date_time).test(str);
    },
    isAddress: function (str) {
        return new RegExp(validateRegExp.address).test(str);
    },
    isMatch: function (pattern, value) {
        return new RegExp(pattern).test(value);
    },

    isPhone: function (str) {
        return new RegExp(validateRegExp.Phone).test(str);
    },
    isNewPhone: function (str) {
        return new RegExp(validateRegExp.NewPhone).test(str);
    },
    isNewAddress: function (str) {
        return new RegExp(validateRegExp.NewAddress).test(str);
    },
    isNewZipcodee: function (str) {
        return new RegExp(validateRegExp.NewZipcode).test(str);
    },
    isNewAccountcode: function (str) {
        return new RegExp(validateRegExp.NewAccountcode).test(str);
    },
    isURL: function (str) {
        return new RegExp(validateRegExp.url).test(str);
    },
    isCodeId: function (str) {
        return new RegExp(validateRegExp.CodeId).test(str);
    },
    strLen: function (str) {
        //获得字符串实际长度，中文2，英文1
        var len = 0;
        for (var i = 0; i < str.length; i++) {
            var c = str.charCodeAt(i);
            //单字节加1 
            if ((c >= 0x0001 && c <= 0x007e) || (0xff60 <= c && c <= 0xff9f)) {
                len++;
            }
            else {
                //數字佔3位
                len += 3;
            }
        }
        return len;
    }

};

//扩展jquery方法
$.fn.extend({
    //验证数字控件
    numbervalid: function (digit,int) {
        $(this).keydown(function (e) {
            $(this).attr("ime-mode", "disabled");//禁用输入法，因为中文输入法会导致which值不正确
            digit = digit || 0;
            $("#keycode").text("keycode:" + e.key + "<br />which:" + e.which);
            if (e.which == 35) {//End
                return true;
            }
            else if (e.which == 36) { //Home
                return true;
            }
            else if (e.which >= 37 && e.which <= 40) {//  上 下 左 右
                return true;
            }
            else if (e.which == 110 || e.which == 190) {// .
                if (digit == 0) return false;

                if ($(this).val() != "" && $(this).val().indexOf(".") == -1) {
                    return true;
                } else {
                    return false;
                }
            }
            else if (e.which == 0 || e.which == 8) {
                return true;
            }
            else if (e.which == 46) {//Delete
                return true;
            } else {
                if ((e.which >= 48 && e.which <= 57 || e.which >= 96 && e.which <= 105) && e.ctrlKey == false && e.shiftKey == false) {
                    if (digit > 0) {
                        var pos = this.selectionEnd; //光标位置
                        var numArr = $(this).val().split('.');
                        if (pos <= numArr[0].length) {
                            if(int !=undefined && int==numArr[0].length){
                                return false;
                            }
                            return true;
                        } else {
                            if (numArr.length == 2 && numArr[1].length >= digit) {
                                return false;
                            }
                        }
                    }
                    return true;
                } else {
                    if (e.ctrlKey == true && (e.which == 67 || e.which == 86 || e.which == 88)) {
                        if (e.which == 86) {
                            if (digit > 0) {
                                var numArr = $(this).val().split('.');
                                if (numArr.length == 2 && numArr[1].length >= digit) {
                                    return false;
                                }
                            }
                        }
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }).bind("paste", function () {
            var copytext = clipboardData.getData('text');
            var $thisvalue = $(this).val();
            if (isNaN(copytext) == true) {
                return false;
            }
            else {
                var coypedtext = $thisvalue + copytext;
                if (isNaN(coypedtext) == true) {
                    return false;
                }
                else {
                    if (digit == 0) {
                        if (copytext.indexOf('.') > -1) {
                            return false;
                        }
                        else {
                            return true;
                        }
                    }
                    else {
                        var numArr = $thisvalue.split('.');
                        if (numArr.length == 2 && numArr[1].length >= digit) {
                            return false;
                        }

                        numArr = coypedtext.split('.');
                        if (numArr.length == 2 && numArr[1].length > digit) {
                            return false;
                        }

                        return true;
                    }
                }
            }
        });
    },
    telphone: function (digit) {
        $(this).keydown(function (e) {
            $(this).attr("ime-mode", "disabled");//禁用输入法，因为中文输入法会导致which值不正确
            digit = digit || 0;
            $("#keycode").text("key:" + e.key + "-----keycode:" + e.which);
            if (e.which == 35) {//End
                return true;
            }
            else if (e.which == 36) { //Home
                return true;
            }
            else if (e.which == 189 || e.which == 0 || e.which == 109 || e.which == 229) { // - 连接符
                console.log($(this).val().indexOf('-'));
                if ($(this).val().indexOf('-') != -1 || $(this).val() == "") {
                    return false;
                }
            }
            else if (e.which >= 37 && e.which <= 40) {//  上 下 左 右
                return true;
            }
            else if (e.which == 110 || e.which == 190) {// .
                if (digit == 0) return false;

                if ($(this).val() != "" && $(this).val().indexOf(".") == -1) {
                    return true;
                } else {
                    return false;
                }
            }
            else if (e.which == 0 || e.which == 8) {
                return true;
            }
            else if (e.which == 46) {//Delete
                return true;
            } else {
                if ((e.which >= 48 && e.which <= 57 || e.which >= 96 && e.which <= 105) && e.ctrlKey == false && e.shiftKey == false) {
                    if (digit > 0) {
                        var numArr = $(this).val().split('.');
                        if (numArr.length == 2 && numArr[1].length >= digit) {
                            return false;
                        }
                    }
                    return true;
                } else {
                    if (e.ctrlKey == true && (e.which == 67 || e.which == 86 || e.which == 88)) {
                        if (e.which == 86) {
                            if (digit > 0) {
                                var numArr = $(this).val().split('.');
                                if (numArr.length == 2 && numArr[1].length >= digit) {
                                    return false;
                                }
                            }
                        }
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }).bind("paste", function () {
            var copytext = clipboardData.getData('text');
            var $thisvalue = $(this).val();
            if (isNaN(copytext) == true) {
                return false;
            }
            else {
                var coypedtext = $thisvalue + copytext;
                if (isNaN(coypedtext) == true) {
                    return false;
                }
                else {
                    if (digit == 0) {
                        if (copytext.indexOf('.') > -1) {
                            return false;
                        }
                        else {
                            return true;
                        }
                    }
                    else {
                        var numArr = $thisvalue.split('.');
                        if (numArr.length == 2 && numArr[1].length >= digit) {
                            return false;
                        }

                        numArr = coypedtext.split('.');
                        if (numArr.length == 2 && numArr[1].length > digit) {
                            return false;
                        }

                        return true;
                    }
                }
            }
        });
    }


});
