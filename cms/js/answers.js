$(document).ready(function () {

    function refreshOnClose() {
        $('.close').click(function () {
            location.href = location.href;
        });
    }

    $('#answrEditModal,#answrDelModal,#canswrEditModal,#canswrDelModal').modal({
        //backdrop: 'static',
        keyboard: false,
        show: false,
    });

    $('#canswrEditModal').on('shown.bs.modal', function() {
        code_answer.refresh();
        answer_display.refresh();
    });

    $('.aeMButton').click(function () {

        var ClickedButton = $(this).data("name");
        var modal_id = ClickedButton.split("-").pop();

        $('#answr-edit-id').val(modal_id);

        var ld = new FormData();
        ld.append('id', modal_id);
        ld.append('category', 7);

        $.ajax({
            url: 'get_modal_data.php',
            type: 'post',
            cache: false,
            data: ld,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {

                if (response.status == 1) {

                    $('#answer').val(response.answer);
                    var correct = response.correct;
                    $("#answr-cor option[value='" + correct + "']").prop("selected",true);
                }

            },
            error: function (xhr) {
                $('#message-answr-edit').attr('class', 'text-danger');
                $('#val-msg-answr-edit').html("Greška pri dohvaćanju podataka!");
            }
        });

        $('#answrEditModal').modal('show');
    });

    $('.adMButton').click(function () {
        var ClickedButton = $(this).data("name");
        var modal_id = ClickedButton.split("-").pop();

        $('#answr-del-id').val(modal_id);

        var ld = new FormData();
        ld.append('id', modal_id);
        ld.append('category', 8);

        $.ajax({
            url: 'get_modal_data.php',
            type: 'post',
            cache: false,
            data: ld,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {

                if (response.status == 1) {
                    $('#answr-del-confirm-text').html("Jeste li sigurni da želite obrisati '" + response.answer + "'?");
                }

            },
            error: function () {
                $('#message-answr-edit').attr('class', 'text-danger');
                $('#val-msg-answr-edit').html("Greška pri dohvaćanju podataka!");
            }
        });

        $('#answrDelModal').modal('show');
    });

    $('#answrEditSubmit').click(function () {

        var fd = new FormData();
        var submitted = $('#answr-edit-submitted').val();
        var id = $('#answr-edit-id').val();
        var answer = $('#answer').val();
        var correct = $('#answr-cor').val();
        var ct = $('#answr-edit-ct').val();

        fd.append('submitted', submitted);
        fd.append('id', id);
        fd.append('answer', answer);
        fd.append('correct', correct);
        fd.append('ct', ct);

        $.ajax({
            url: 'edit_answer.php',
            type: 'post',
            cache: false,
            data: fd,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {

                $('#val-msg-answr-edit').html(response.message);
                $('#answrEditModal').on('hidden.bs.modal', function (event) {
                    location.href = location.href;
                });
                refreshOnClose();

                if (response.status == 1) {
                    $('#message-answr-edit').attr('class', 'text-success');

                } else {
                    $('#message-answr-edit').attr('class', 'text-danger');
                }
            },
            error: function (xhr) {
                $('#message-answr-edit').attr('class', 'text-danger');
                $('#val-msg-answr-edit').html("Dogodila se pogreška!");
            }
        });
    });

    $('#answrDelSubmit').click(function () {

        var fd = new FormData();
        var id = $('#answr-del-id').val();
        var ct = $('#answr-del-ct').val();

        fd.append('id', id);
        fd.append('ct', ct);

        $.ajax({
            url: 'delete_answer.php',
            type: 'post',
            cache: false,
            data: fd,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {

                $('#val-msg-answr-del').html(response.message);
                $('#answrDelModal').on('hidden.bs.modal', function (event) {
                    location.href = location.href;
                });
                refreshOnClose();

                if (response.status == 1) {
                    $('#message-answr-del').attr('class', 'text-success');
                    $('#answr-del-confirm-text').html('');
                    $('#answrDelSubmit').css('display', 'none');
                    $('#answrDelCancel').html("Zatvori");

                } else {
                    $('#message-answr-del').attr('class', 'text-danger');
                }
            },
            error: function () {
                $('#message-answr-del').attr('class', 'text-danger');
                $('#val-msg-answr-del').html("Dogodila se pogreška!");
            }
        });
    });

    //code answer

    $('.caeMButton').click(function () {

        var ClickedButton = $(this).data("name");
        var modal_id = ClickedButton.split("-").pop();

        $('#canswr-edit-id').val(modal_id);

        var ld = new FormData();
        ld.append('id', modal_id);
        ld.append('category', 9);

        $.ajax({
            url: 'get_modal_data.php',
            type: 'post',
            cache: false,
            data: ld,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {
                if (response.status == 1) {
                    code_answer.setValue(response.code);
                    answer_display.setValue(response.display);
                }
            },
            error: function (xhr) {
                $('#message-canswr-edit').attr('class', 'text-danger');
                $('#val-msg-canswr-edit').html("Greška pri dohvaćanju podataka!");
            }
        });

        $('#canswrEditModal').modal('show');
    });

    $('.cadMButton').click(function () {
        var ClickedButton = $(this).data("name");
        var modal_id = ClickedButton.split("-").pop();

        $('#canswr-del-id').val(modal_id);

        var ld = new FormData();
        ld.append('id', modal_id);
        ld.append('category', 10);

        $.ajax({
            url: 'get_modal_data.php',
            type: 'post',
            cache: false,
            data: ld,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {
                if (response.status == 1) {
                    $('#canswr-del-confirm-text').html("Jeste li sigurni da želite obrisati '" + response.code + "'?");
                }
            },
            error: function () {
                $('#message-canswr-edit').attr('class', 'text-danger');
                $('#val-msg-canswr-edit').html("Greška pri dohvaćanju podataka!");
            }
        });

        $('#canswrDelModal').modal('show');
    });

    $('#canswrEditSubmit').click(function () {

        var fd = new FormData();
        var submitted = $('#canswr-edit-submitted').val();
        var id = $('#canswr-edit-id').val();
        var code = code_answer.getValue();
        var display = answer_display.getValue();
        var ct = $('#canswr-edit-ct').val();

        fd.append('submitted', submitted);
        fd.append('id', id);
        fd.append('answer-code', code);
        fd.append('answer-display', display);
        fd.append('ct', ct);

        $.ajax({
            url: 'edit_code_answer.php',
            type: 'post',
            cache: false,
            data: fd,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {

                $('#val-msg-canswr-edit').html(response.message);
                $('#canswrEditModal').on('hidden.bs.modal', function (event) {
                    location.href = location.href;
                });
                refreshOnClose();

                if (response.status == 1) {
                    $('#message-canswr-edit').attr('class', 'text-success');
                } else {
                    $('#message-canswr-edit').attr('class', 'text-danger');
                }
            },
            error: function (xhr) {
                $('#message-canswr-edit').attr('class', 'text-danger');
                $('#val-msg-canswr-edit').html("Dogodila se pogreška!");
            }
        });
    });

    $('#canswrDelSubmit').click(function () {

        var fd = new FormData();
        var id = $('#canswr-del-id').val();
        var ct = $('#canswr-del-ct').val();

        fd.append('id', id);
        fd.append('ct', ct);

        $.ajax({
            url: 'delete_code_answer.php',
            type: 'post',
            cache: false,
            data: fd,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {

                $('#val-msg-canswr-del').html(response.message);
                $('#canswrDelModal').on('hidden.bs.modal', function (event) {
                    location.href = location.href;
                });
                refreshOnClose();

                if (response.status == 1) {
                    $('#message-canswr-del').attr('class', 'text-success');
                    $('#canswr-del-confirm-text').html('');
                    $('#canswrDelSubmit').css('display', 'none');
                    $('#canswrDelCancel').html("Zatvori");
                } else {
                    $('#message-canswr-del').attr('class', 'text-danger');
                }
            },
            error: function () {
                $('#message-canswr-del').attr('class', 'text-danger');
                $('#val-msg-canswr-del').html("Dogodila se pogreška!");
            }
        });
    });


});