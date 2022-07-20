$(document).ready(function () {

    if(localStorage.getItem('dark')==null) {
        localStorage.setItem('dark', 1);
    }

    if (localStorage.getItem('dark') == 1) {
        $('body').addClass('dark');
        $('.sun-icon').css("display","initial");
        $('.moon-icon').css("display","none");
    } else if (localStorage.getItem('dark') == 0) {
        $('body').removeClass('dark');
        $('.sun-icon').css("display","none");
        $('.moon-icon').css("display","initial");
    }

    var direction = "";
    var oldx = 0;
    var oldy = 0;
    mousemovemethod = function (e) {

        if (e.pageX > oldx && e.pageY == oldy) {
            direction = "R";
        }
        else if (e.pageX == oldx && e.pageY > oldy) {
            direction = "D";
        }
        else if (e.pageX == oldx && e.pageY < oldy) {
            direction = "U";
        }
        else if (e.pageX < oldx && e.pageY == oldy) {
            direction = "L";
        }

        oldx = e.pageX;
        oldy = e.pageY;

    }

    document.addEventListener('mousemove', mousemovemethod);

    $('.back-button').click(function () {
        window.history.go(-1);
    });

    $('.modal-body').prop("class", "modal-body text-success");

    $('.startButton').click(function () {
        var clickedBtnID = $(this).attr('data-name');
        var lesson_id = clickedBtnID.split("-").pop();

        var fd = new FormData();

        fd.append('id', lesson_id);

        $.ajax({
            url: 'get_questions.php',
            type: 'post',
            cache: false,
            data: fd,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {
                if (response.status == 1) {
                    window.location.href = "quiz.php";
                } else if (response.status == 0) {
                    window.location.href = "error.php";
                }
            },
            error: function (xhr) {
                window.location.href = "error.php";
            }
        });
    });

    $('.practiceButton').click(function () {
        var clickedBtnID = $(this).attr('data-name');
        var language_id = clickedBtnID.split("-").pop();

        var fd = new FormData();

        fd.append('id', language_id);

        $.ajax({
            url: 'get_practice_questions.php',
            type: 'post',
            cache: false,
            data: fd,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {
                if (response.status == 1) {
                    window.location.href = "quiz.php";
                } else if (response.status == 0) {
                    window.location.href = "error.php";
                }
            },
            error: function (xhr) {
                window.location.href = "error.php";
            }
        });
    });

    $('.lessonButton').click(function () {
        var clickedBtnID = $(this).attr('data-name');
        var language_id = clickedBtnID.split("-").pop();

        window.location.href = "lessons.php?lid=" + language_id;
    });

    $('#navbarDropdown').hover(function () {
        $('.dropdown-menu').addClass('show');
        $('.dropdown-menu').prop("style", "position: absolute; transform: translate3d(-40px, 65px, 0px); left: 0px; will-change: transform;");
        $('.dropdown-menu').prop("x-placement", "bottom-end");
    });


    $('.dropdown-menu').on("mouseleave", function () {
        $('.dropdown-menu').removeClass('show');
    });

    $('#navbarDropdown').on("mouseleave", function () {
        if ($('.dropdown-menu').hasClass('show')) {
            if (direction != "D") {
                $('.dropdown-menu').removeClass('show');
            }
        }
    });

    $('.changeMode').click(function () {

        if (localStorage.getItem('dark') == 0) {
            localStorage.setItem('dark', 1);
            $('body').toggleClass('dark');
            $('.sun-icon').css("display","initial");
            $('.moon-icon').css("display","none");
            $('#wrapper-list').css('animation','light-to-dark 0.5s forwards');
        } else if (localStorage.getItem('dark') == 1) {
            localStorage.setItem('dark', 0);
            $('body').toggleClass('dark');
            $('.sun-icon').css("display","none");
            $('.moon-icon').css("display","initial");
            $('#wrapper-list').css('animation','dark-to-light 0.5s forwards');
        }
    });
});