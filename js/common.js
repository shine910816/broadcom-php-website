/**
 * 窗体宽高自适应
*/
var adjustWindow = function(){
    var windowWidth = $(window).width();
    var windowHeight = $(window).height();
    var option = {
        bodyMinWidth:1000,
        topHeight:70,
        leftWidth:200,
        bottomPadding:10
    };
    var bodyWidth = windowWidth;
    if (windowWidth < option.bodyMinWidth) {
        bodyWidth = option.bodyMinWidth;
    }
    var bodyHeight = option.topHeight;
    if ($("div.left-panel").height() < windowHeight - option.topHeight && $("div.main-panel").height() < windowHeight - option.topHeight) {
        bodyHeight += windowHeight - option.topHeight;
    } else {
        if ($("div.left-panel").height() >= $("div.main-panel").height()) {
            bodyHeight += $("div.left-panel").height() + option.bottomPadding * 2;
        } else {
            bodyHeight += $("div.main-panel").height() + option.bottomPadding * 2;
        }
    }
    var bottomHeight = bodyHeight - option.topHeight - option.bottomPadding * 2;
    $(document.body).css({
        "width":bodyWidth,
        "height":bodyHeight
    });
    $("div.top-panel").css({
        "width":bodyWidth,
        "height":option.topHeight
    });
    $("div.bottom-panel").css({
        "width":bodyWidth,
        "height":bottomHeight + option.bottomPadding * 2
    });
    $("div.left-panel").css({
        "width":option.leftWidth,
        "height":bottomHeight,
        "padding":option.bottomPadding
    });
    $("div.main-panel").css({
        "width":bodyWidth - option.leftWidth - option.bottomPadding * 4,
        "height":bottomHeight,
        "padding":option.bottomPadding
    });
};
$(document).ready(function(){
    adjustWindow();
$(window).on("resize", function(){
    adjustWindow();
});
});
