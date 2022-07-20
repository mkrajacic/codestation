$(document).ready(function () {

    var current_page = $(location).attr('pathname');

    function createSlider(current_page) {

        var items=$('#lc').val();
        var windowWidth = $(window).width();

        if((items==0)) {
            $('#left-button').css('display','none');
            $('#right-button').css('display','none');
        }else{
            if (current_page.includes("lessons.php")) {

                $('#slick1').on('init', function (event, slick, direction) {
                    if (!($('#slick1 .slick-slide').length > 1)) { 
                        $('#left-button').css('display','none');
                        $('#right-button').css('display','none');
                    }
                    if(windowWidth>1200) {
                        if (!(items > 8)) { 
                            $('#left-button').css('display','none');
                            $('#right-button').css('display','none');
                        }
                    }else if((windowWidth<=1200) && (windowWidth>=930)) {
                        if (!(items > 6)) { 
                            $('#left-button').css('display','none');
                            $('#right-button').css('display','none');
                        }else{
                            $('#left-button').css('display','block');
                            $('#right-button').css('display','block');
                        }
                    }
                    else if((windowWidth<930) && (windowWidth>=850)) {
                        if (!(items > 4)) { 
                            $('#left-button').css('display','none');
                            $('#right-button').css('display','none');
                        }else{
                            $('#left-button').css('display','block');
                            $('#right-button').css('display','block');
                        }
                    }
                });
        
                $('#slick1').not('.slick-initialized').slick({
                    rows: 2,
                    dots: false,
                    prevArrow: $("#left-button"),
                    nextArrow: $("#right-button"),
                    arrows: true,
                    infinite: false,
                    speed: 300,
                    slidesToShow: 4,
                    slidesToScroll: 4,
                    responsive:[
                        {
                            breakpoint:1200,
                            settings:{
                                slidesToShow:3,
                                slidesToScroll:3
                            }
                        },
                        {
                            breakpoint:930,
                            settings:{
                                slidesToShow:2,
                                slidesToScroll:2
                            }
                        },
                        {
                            breakpoint:850,
                            settings: "unslick"
                        }
                    ]
                });
                
            } else if (current_page.includes("languages.php")) {
    
                $('#slick2').on('init', function (event, slick, direction) {
                    if (!($('#slick2 .slick-slide').length > 1)) { 
                        $('#left-button').css('display','none');
                        $('#right-button').css('display','none');
                    }
                });
    
                $('#slick2').not('.slick-initialized').slick({
                    rows: 1,
                    dots: false,
                    prevArrow: $("#left-button"),
                    nextArrow: $("#right-button"),
                    arrows: true,
                    infinite: false,
                    speed: 300,
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    responsive:[
                        {
                            breakpoint:1330,
                            settings:{
                                slidesToShow:2,
                                slidesToScroll:2
                            }
                        },
                        {
                            breakpoint:850,
                            settings: "unslick"
                        }
                    ]
                });
            }
        }
    }

    createSlider(current_page);

    $(window).resize(function(){
        var windowWidth = $(window).width();
        if (windowWidth > 850) {
            createSlider(current_page);   
        }
        toggleArrows(windowWidth);
    });

    function toggleArrows(windowWidth) {
        var items=$('#lc').val();

        if(windowWidth>1200) {
            if (!(items > 8)) { 
                $('#left-button').css('display','none');
                $('#right-button').css('display','none');
            }
        }else if((windowWidth<=1200) && (windowWidth>=930)) {
            if (!(items > 6)) { 
                $('#left-button').css('display','none');
                $('#right-button').css('display','none');
            }else{
                $('#left-button').css('display','block');
                $('#right-button').css('display','block');
            }
        }
        else if((windowWidth<930) && (windowWidth>=850)) {
            if (!(items > 4)) { 
                $('#left-button').css('display','none');
                $('#right-button').css('display','none');
            }else{
                $('#left-button').css('display','block');
                $('#right-button').css('display','block');
            }
        }
    }

    $('#left-button').on('mouseover', function () {
        $('#left-button').css("opacity", "1");
    });

    $('#right-button').on('mouseover', function () {
        $('#right-button').css("opacity", "1");
    });

    $('#left-button').on('mouseout', function () {
        $('#left-button').css("opacity", "0.5");
    });

    $('#right-button').on('mouseout', function () {
        $('#right-button').css("opacity", "0.5");
    });

});