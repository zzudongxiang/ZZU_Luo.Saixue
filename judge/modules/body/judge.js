function checked(wait) {
    checkedajax(wait)
    window.setInterval(function () {
        checkedajax(wait)
    }, 3000)
}

function checkedajax(wait) {
    $.ajax({
        url: "/judge/method/checked.php?wait=" + wait,
        dataType: 'json',
        success: function (data) {
            if (data.url !== '#')
                top.location.href = data.url;
        }
    });
}

