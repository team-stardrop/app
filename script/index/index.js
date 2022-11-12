$(window).keydown(function(e) {
    if (e.keyCode == 27) {
        postLayer_close();
    }
});

$(".header-bottom-content-post").click(function() {
    postLayer_open();
});

$(".form-top-top-closeButton").click(function() {
    postLayer_close();
});

$(".postLayer").click(function() {
    postLayer_close();
});



function postLayer_open() {
    $(".postLayer").css("display", "block");
    $(".postLayer-content").css("display", "block");
}

function postLayer_close() {
    $(".postLayer").css("display", "none");
    $(".postLayer-content").css("display", "none");
}