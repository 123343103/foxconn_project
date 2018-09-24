$(function () {
    $.fn.myMenu = function ($option) {
        var defaultOption = {
            'first-menu': 'first-menu',
            'second-menu': 'second-menu',
            'three-menu': 'three-menu'
        };

        var ops = $.extend(defaultOption, $option);
        this.addClass(ops['first-menu']);               //1阶UL
        var $firstLi = this.children('li');

        $firstLi.on("mouseenter", function () {
            $(this).children("ul").show();
        });
        $firstLi.on("mouseleave", function () {

            $(this).children("ul").hide();
        });

        if ($firstLi.length != 0) {
            $firstLi.each(function () {
                var $second_ul = $(this).children('ul');      //2阶UL
                $second_ul.addClass(ops['second-menu']);
                var $secondLI = $second_ul.children('li');
                if ($secondLI.length != 0) {
                    $secondLI.each(function () {
                        $(this).prepend("|");
                        var $threeUL = $(this).children('ul');         //3阶UL
                        $threeUL.addClass(ops['three-menu']);
                    })
                }
            })
        }
        this.show();
        return this;
    };
    $("#menu").myMenu();

});


