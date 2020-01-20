/**
 * 主控制脚本
*/
var adjustWindow = function(opt){
    var windowWidth = $(window).width();
    var windowHeight = $(window).height();
    //$(document.body).width(windowWidth);
    //$("div.top-panel").css({
    //    "width":"100%",
    //    "min-width":opt.topHeight + opt.leftWidth + opt.mainPadding * 4,
    //    "height":opt.topHeight
    //});
    //$("div.left-panel").css({
    //    "width":opt.leftWidth,
    //    "height":windowHeight - opt.topHeight,
    //    "top":opt.topHeight
    //});
    //$("div.main-panel").css({
    //    "width":windowWidth - opt.leftWidth - opt.mainPadding * 4,
    //    "min-width":opt.mainMinWidth,
    //    "padding-top":opt.topHeight + opt.mainPadding,
    //    "padding-right":opt.mainPadding * 2,
    //    "padding-bottom":opt.mainPadding,
    //    "padding-left":opt.leftWidth + opt.mainPadding * 2
    //});
    var option = {
        bodyMinWidth:1000,
        bodyMinHeight:500,
        topHeight:70,
        leftWidth:200,
        bottomPadding:10
    };
    var bodyWidth = windowWidth;
    if (windowWidth < option.bodyMinWidth) {
        bodyWidth = option.bodyMinWidth;
    }
//    alert(bodyWidth);
    $("div.top-panel").css({
        "width":bodyWidth,
        "height":option.topHeight
    });
    var bodyHeight = option.topHeight;
    if ($("div.left-panel").height() < option.bodyMinHeight && $("div.main-panel").height() < option.bodyMinHeight) {
        bodyHeight += option.bodyMinHeight;
//alert(bodyHeight);
    } else {
        if ($("div.left-panel").height() >= $("div.main-panel").height()) {
            bodyHeight += $("div.left-panel").height() + option.bottomPadding * 2;
            $("div.main-panel").height($("div.left-panel").height());
//alert(bodyHeight);
        } else {
            bodyHeight += $("div.main-panel").height() + option.bottomPadding * 2;
            $("div.left-panel").height($("div.main-panel").height());
//alert(bodyHeight);
        }
    }
    $(document.body).height(bodyHeight);
    $("div.left-panel").css({
        "width":option.leftWidth,
        "padding":option.bottomPadding
    });
    $("div.main-panel").css({
        "width":bodyHeight - option.leftWidth - option.bottomPadding * 2,
        "padding":option.bottomPadding
    });
};
$(document).ready(function(){
    adjustWindow();
});
$(window).on("resize", function(){
    adjustWindow();
});
