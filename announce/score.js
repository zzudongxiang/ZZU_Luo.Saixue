function getdata() {
    window.setInterval(function () {
        $.ajax({
            url: "/announce/score._method.php",
            dataType: 'json',
            success: function (data) {
                for (var key in data) {
                    $("#" + key).text(data[key]);
                }
            }
        });
    }, 1000);
}