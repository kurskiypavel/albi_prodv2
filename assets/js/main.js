// show mobile form
var phone = $('button.phone');
phone.click(function () {
    $("div.mobile").show();
});

//hide mobile form whe clicked outside
$(document).bind("mouseup touchend", function (e) {
    var container = $("div.mobile");
    // if the target of the click isn't the container nor a descendant of the container
    if (container.is(e.target) && container.has(e.target).length === 0) {
        container.hide();

    }
});

// hide mobile form when clicked on left arrow
var arrowLeft = $('.arrow');
arrowLeft.click(function () {
    $("div.mobile").hide();

});


// show thank you modal
var thankYou = $('.thankYou');
var signIn = $('button.signIn');
signIn.click(function (e) {
    e.preventDefault();
    $("div.mobile").hide();
    thankYou.show();
});

//hide mobile form whe clicked outside
$(document).bind("mouseup touchend", function (e) {
    var container = $(".thankYou");
    // if the target of the click isn't the container nor a descendant of the container
    if (container.is(e.target) && container.has(e.target).length === 0) {
        container.hide();

    }
});