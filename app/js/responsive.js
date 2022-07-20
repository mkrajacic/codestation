$(document).ready(function () {
 
    function moveElements() {
        var width = $(window).width();

        if(width<=1500) {
            if ((width <= 1500) && (width >= 1250)) {
                $('.back-button').css('top','14vw');
            }
        }
    
        if (width <= 780) {
            if ((width <= 750) && (width >= 720)) {
                $('#upper-list').css('margin-left', '-=0.4' + 'em');
            }else if ((width <= 720) && (width >= 680)) {
                $('#upper-list').css('margin-left', '-=0.8' + 'em');
            }else if ((width <= 680) && (width >= 600)) {
                $('#upper-list').css('margin-left', '-=1.6' + 'em');
            }else if ((width <= 600) && (width >= 570)) {
                $('#upper-list').css('margin-left', '-=2.4' + 'em');
            }else if ((width <= 570) && (width >= 560)) {
                $('#upper-list').css('margin-left', '-=2.8' + 'em');
            }else if ((width <= 560) && (width >= 530)) {
                $('#upper-list').css('margin-left', '0' + 'em');
                $('#upper-practice .container-text').css('display','none');
                $('#upper-practice').css('height','9.2vw');
                $('#upper-practice').css('right','-1em');
                $('#upper-practice').css('margin-top','1.4em');
                $('#upper-practice').css('min-width','70px');
            }else if ((width <= 530) && (width >= 500)) {
                $('#upper-practice').css('right','-1.2em');
                $('#upper-list').css('margin-left', '-1' + 'em');
                $('#upper-practice .container-text').css('display','none');
                $('#upper-practice').css('height','9.2vw');
                $('#upper-practice').css('margin-top','1.4em');
                $('#upper-practice').css('min-width','70px');
            }
        }
    }

    moveElements();
});