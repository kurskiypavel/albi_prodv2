$(document).ready(function () {
    $('input').inputmask({
        "placeholder": " "
    });
});



$(":input").inputmask();

$("#yourphone2").inputmask({
    "mask": "(999) 999-9999"
});


//prevent submit if phone less than 10

$("form").submit(function (e) {
    var validationFailed = false;
    var curchr = $('#yourphone2').val().replace(/\D/g, '');
    // var email = $('#yourMail').val();
    // if (curchr.length < 10 && email.length < 1) {
    //     validationFailed = true;
    // }
    if (curchr.length < 10 ) {
        validationFailed = true;
    }
    var url = window.location.pathname;
    var filename = url.substring(url.lastIndexOf('/')+1);

    if (validationFailed && filename!=='looksGood.php') {
        e.preventDefault();
        return false;
    }
});