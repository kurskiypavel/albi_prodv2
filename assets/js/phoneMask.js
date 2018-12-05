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
    var email = $('#yourMail').val();
    if (curchr.length < 10 && email.length < 1) {
        validationFailed = true;
    }
    if (validationFailed) {
        e.preventDefault();
        return false;
    }
});