/*
 *   PROGRAMS SCRIPT BEGINS
 */

// add active class on clicked element and remove from neighbor

$('#newProgram').click(function () {
    $(this).next().removeClass("active");
    $(this).addClass('active');
    showNewPrograms();
})

$('#allProgram').click(function () {
    $(this).prev().removeClass("active");
    $(this).addClass('active');
    showAllPrograms();
})

// show new programs when clicked and hide all programs
function showNewPrograms() {
    $('.programs.new').css('display', 'block');
    $('.programs.all').css('display', 'none');
    $('tr').css('display', 'block');
}

// show all programs when clicked and hide new programs
function showAllPrograms() {
    $('.programs.all').css('display', 'block');
    $('.programs.new').css('display', 'none');
}


//        search from input on the top  in titles of programs
function myFunction() {
    var input, filter, table, tr, td, i;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();


    $('#allProgram').addClass("active");
    $('#newProgram').removeClass("active");


    if ($("#myTableAll").is(":visible") === true) {
        table = document.getElementById("myTableAll");
    } else {
        table = document.getElementById("myTableNew");
    }

    // table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0].getElementsByClassName("title")[0];
        if (td) {
            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}


/*
 *   PROGRAMS SCRIPT ENDS
 */


/*
 *   EVENTS SCRIPT BEGINS
 */

// add active class on clicked element and remove from neighbor

$('#groupEvents').click(function () {
    $(this).next().removeClass("active")
    $(this).addClass('active');
    showGroupEvents();
})

$('#privateEvents').click(function () {
    $(this).prev().removeClass("active")
    $(this).addClass('active');
    showPrivateEvents();
})

// show private events when clicked and hide group events
function showPrivateEvents() {
    $('.events.private').css('display', 'block');
    $('.events.group').css('display', 'none');
}

// show group events when clicked and hide private events
function showGroupEvents() {
    $('.events.group').css('display', 'block');
    $('.events.private').css('display', 'none');
}


/*
 *   EVENTS SCRIPT ENDS
 */




/*
 *   PROGRAM & PROGRAMS & USER FAVORITES SCRIPT BEGINS
 */


//add program to favorite
function like(user, id) {

    $.ajax({
        type: "POST",
        url: "../ajax/like.php",
        data: {
            user: user,
            id: id
        }
    });
}


//delete program from favorite
function dislike(user, id) {
    $.ajax({
        type: "POST",
        url: "../ajax/dislike.php",
        data: {
            user: user,
            id: id
        }
    });
}

//block program link on the top of icon
$('i.fa-share').on("click", function (e) {
    e.preventDefault();
});

//event listener for favorites
$('i.fa-heart').on("click", function (e) {
    //block program link on the top of icon
    e.preventDefault();
    // set vars
    var url = new URL(window.location.href);
    var user = url.searchParams.get("user");

    if (url.searchParams.get("id")) {
        var id = url.searchParams.get("id");
    } else {
        var id = $(this).attr('id');
    }


    if ($(this).hasClass('far') === true) {
        $(this).removeClass('far').addClass('fas');
        like(user, id);
    } else {
        $(this).removeClass('fas').addClass('far');
        dislike(user, id);
    }
});

$('i.fa-times').on("click", function (e) {
    //block program link on the top of icon
    e.preventDefault();
    // set vars
    var url = new URL(window.location.href);
    var user = url.searchParams.get("user");
    var id = $(this).attr('id');

    $(this).parent().parent().remove();
    dislike(user, id);

});

/*
 *   PROGRAM & PROGRAMS & USER FAVORITES SCRIPT ENDS
 */


/*
 *  CONTROL VISIBILITY OF NAVIGATOIN  SCRIPT BEGINS
 */

// $(document).bind("mouseup touchmove", function (e) {
//     var onready = window.innerHeight;
// 	var max = window.innerHeight;
//     $(window).resize(function () {
//         var resized = window.innerHeight;
// 		if(resized>max){
// 			max = resized;
// 		}
//         if (resized < max) {
//             $('.footerBar').hide();
//         } else {
//             $('.footerBar').show();
//         }

//     })
// })

// touch - OFF

// scroll - ON



/*
 *  CONTROL VISIBILITY OF NAVIGATOIN  SCRIPT BEGINS
 */

/*
 *  USER PAGE  SCRIPT BEGINS
 */





/*
 *  USER PAGE SCRIPT BEGINS
 */

