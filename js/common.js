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
var open_operate_option = false;
$(document).ready(function(){
    adjustWindow();
    $("input[type='checkbox'], input[type='radio']").change(function(){
        $("input[name='" + $(this).attr("name") + "']").each(function(){
            if ($(this).prop("checked")) {
                $(this).parent().addClass("ui-btn-orange");
            } else {
                $(this).parent().removeClass("ui-btn-orange");
            }
        });
    });
    $(".auto-select").focus(function(){
        $(this).select();
    });
    $(".operate-button").click(function(){
        if (open_operate_option) {
            $(".operate-button").removeClass("ui-btn-orange");
            $(".operate-button").removeClass("ui-btn-open");
            $(".operate-button").children("i").addClass("fa-angle-down");
            $(".operate-button").children("i").removeClass("fa-angle-up");
            $("div#operate-disp-option").css({"display":"none"});
            open_operate_option = false;
        } else {
            $(this).addClass("ui-btn-orange");
            $(this).addClass("ui-btn-open");
            $(this).children("i").removeClass("fa-angle-down");
            $(this).children("i").addClass("fa-angle-up");
            var new_top = $(this).offset().top + $(this).height();
            var new_left = $(this).offset().left;
            $("div#operate-disp-option").empty().append($(this).next().html());
            $("div#operate-disp-option").css({
                "display":"block",
                "top":new_top,
                "left":new_left
            });
            open_operate_option = true;
        }
    });
$(window).on("resize", function(){
    adjustWindow();
});
});
