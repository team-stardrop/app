let notification_count = 0; //notificationのカウント

function notification(val) {
    notification_count++;
    let tmp = notification_count;
    $(".error").append("<div class='error-content error-content-" + tmp + "'>" + val + "</div>");
    setTimeout(() => {
        $(".error-content-" + tmp).css("opacity", "1");
        setTimeout(() => {
            $(".error-content-" + tmp).css("opacity", "0");
            setTimeout(() => {
                $(".error-content-" + tmp).remove();
            }, 500);
        }, 4000);
    }, 10);
}