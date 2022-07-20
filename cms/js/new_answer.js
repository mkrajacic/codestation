    $('#answr-count').on('change', function() {
        var count = $('#answr-count option:selected').val();

        for (var i = 1; i <= 8; i++) {
            $('#answer-' + i).css({
                'display': 'none'
            });
        }

        for (var j = 1; j <= count; j++) {
            $('#answer-' + j).css({
                'display': 'block'
            });
        }
    });

    $('.correct').on('click', function() {
        var checked_boxes = $("input[type='checkbox']:checked").length;

        var one = $('.questtypeHelp:contains("Samo jedan")').length;

        if (one > 0) {
            if (checked_boxes > 0) {

                $('.correct:not(:checked)').each(function(i, obj) {
                    $(this).prop("disabled", true);
                });

            }

            if (checked_boxes == 0) {
                $('.correct').each(function(i, obj) {
                    $(this).prop("disabled", false);
                });
            }
        }
    });