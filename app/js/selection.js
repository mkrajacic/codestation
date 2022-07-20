$(document).ready(function () {
    if (localStorage.getItem('dark') == 1) {
        $('body').addClass('dark');
        $('.sun-icon').css("display", "initial");
        $('.moon-icon').css("display", "none");
    } else if (localStorage.getItem('dark') == 0) {
        $('body').removeClass('dark');
        $('.sun-icon').css("display", "none");
        $('.moon-icon').css("display", "initial");
    }

    $('.backButton').click(function () {
        window.history.go(-1);
    });

    $('.skipButton').one('click', function () {
        var clickedBtnID = $(this).attr('id');
        var index = clickedBtnID.split("-").pop();

        var ld = new FormData();
        ld.append('index', index);

        $('#bottom').prop("style", "display:block;");

        $.ajax({
            url: 'skip_question.php',
            type: 'post',
            cache: false,
            data: ld,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {

                if (response.status == 1) {
                    $('.bi-arrow-right-square').prop("style", "display:block;");
                    $('.bi-skip-forward').prop("style", "display:none; pointer-events: none;");
                    $('.nextButton').click(function () {
                        if (response.status != 0) {
                            window.location.href = "quiz.php";
                        }
                    });
                    $('.bottom-text p').text("Točan odgovor:");
                    $('.bottom-text em').text(response.answer);
                    $('#outer, #bottom,#upper-left, #upper').css('animation', 'border-incorrect 1.2s forwards');
                    $('#left').css('animation', 'left-border-incorrect 1.2s forwards');
                    $('#wrapper').css('animation-name', 'loselife');
                    $('#wrapper').css('animation-duration', '2s');

                } else if (response.status == 0) {
                    window.location.href = "error.php";
                }

            },
            error: function (xhr) {
                $('.bottom-text').text("Dogodila se pogreška!");
            }
        });
    });

    $('.answr-submit').addClass("disable");

    $('textarea, input').on('input', function () {
        $('.answr-submit').removeClass("disable");
    });

    $('textarea, input').on('blur', function () {
        if (!$(this).val()) {
            $('.answr-submit').addClass("disable");
        }
    });

    var checkBoxes = $("input[type='checkbox']");
    checkBoxes.change(function () {
        var notclicked = checkBoxes.filter(':checked').length < 1;
        if (!notclicked) {
            $('.answr-submit').removeClass("disable");
        }
    });
    checkBoxes.change();
});

$('.choice').change(function () {
    var checked_boxes = $("input[type='checkbox']:checked").length;

    var one = $('.answers').hasClass('one');

    if (one > 0) {
        if (checked_boxes > 0) {

            $('.answr-submit').removeClass("disable");

            $('.choice:not(:checked)').each(function (i, obj) {
                $(this).prop("disabled", true);
            });
        } else {
            $('.answr-submit').addClass("disable");

            $('.choice').each(function (i, obj) {
                $(this).prop("disabled", false);
            });
        }
    } else {
        if (checked_boxes > 0) {
            $('.answr-submit').removeClass("disable");
        } else {
            $('.answr-submit').addClass("disable");
        }
    }
});