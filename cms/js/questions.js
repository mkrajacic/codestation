$(document).ready(function () {

    function refreshOnClose() {
        $('.close').click(function () {
            location.href = location.href;
        });
    }

    $('#questEditModal,#questDelModal').modal({
        //backdrop: 'static',
        keyboard: false,
        show: false,
    });

    $('.qeMButton').click(function () {

        $('#quest-type > option:not(:first-child)').remove();
        $('#quest-less > option:not(:first-child)').remove();

        var ClickedButton = $(this).data("name");
        var modal_id = ClickedButton.split("-").pop();

        $('#quest-edit-id').val(modal_id);

        var ld = new FormData();
        ld.append('id', modal_id);
        ld.append('category', 5);

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
                    $('#question').val(response.question);
                    var lessMenu = response.lessons;
                    var typeMenu = response.types;

                    for (var i in lessMenu) {
                        if (!(lessMenu[i].id === undefined)) {

                            if (lessMenu[i].id == response.lesson_id) {
                                $('#quest-less').append('<option value="' + lessMenu[i].id + '" selected>' + lessMenu[i].name + '</option>');
                            } else {
                                $('#quest-less').append('<option value="' + lessMenu[i].id + '">' + lessMenu[i].name + '</option>');
                            }

                        }
                    }

                    for (var p in typeMenu) {
                        if (!(typeMenu[p].id === undefined)) {

                            if (typeMenu[p].id == response.type) {
                                $('#quest-type').append('<option value="' + typeMenu[p].id + '" selected>' + typeMenu[p].type + '</option>');
                            } else {
                                $('#quest-type').append('<option value="' + typeMenu[p].id + '">' + typeMenu[p].type + '</option>');
                            }

                        }
                    }

                }

            },
            error: function (xhr) {
                $('#message-quest-edit').attr('class', 'text-danger');
                $('#val-msg-quest-edit').html("Greška pri dohvaćanju podataka!");
            }
        });

        $('#questEditModal').modal('show');
    });

    $('.qdMButton').click(function () {
        var ClickedButton = $(this).data("name");
        var modal_id = ClickedButton.split("-").pop();

        $('#quest-del-id').val(modal_id);

        var ld = new FormData();
        ld.append('id', modal_id);
        ld.append('category', 6);

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
                    $('#quest-del-confirm-text').html("Jeste li sigurni da želite obrisati '" + response.question + "'?");
                }

            },
            error: function () {
                $('#message-quest-edit').attr('class', 'text-danger');
                $('#val-msg-quest-edit').html("Greška pri dohvaćanju podataka!");
            }
        });

        $('#questDelModal').modal('show');
    });

    $('#questEditSubmit').click(function () {

        var fd = new FormData();
        var submitted = $('#quest-edit-submitted').val();
        var id = $('#quest-edit-id').val();
        var question = $('#question').val();
        var question_type = $('#quest-type').val();
        var question_lession = $('#quest-less').val();
        var ct = $('#quest-edit-ct').val();

        fd.append('submitted', submitted);
        fd.append('id', id);
        fd.append('question', question);
        fd.append('quest-type', question_type);
        fd.append('quest-less', question_lession);
        fd.append('ct', ct);
        
        $.ajax({
            url: 'edit_question.php',
            type: 'post',
            cache: false,
            data: fd,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {

                $('#val-msg-quest-edit').html(response.message);

                if (response.status == 1) {
                    $('#message-quest-edit').attr('class', 'text-success');
                    $('#questEditModal').on('hidden.bs.modal', function (event) {
                        location.href = location.href;
                    });
                    refreshOnClose();
                } else {
                    $('#message-quest-edit').attr('class', 'text-danger');
                }
            },
            error: function (xhr) {
                $('#message-quest-edit').attr('class', 'text-danger');
                $('#val-msg-quest-edit').html("Dogodila se pogreška!");
            }
        });
    });

    $('#questDelSubmit').click(function () {

        var fd = new FormData();
        var id = $('#quest-del-id').val();
        var ct = $('#quest-del-ct').val();

        fd.append('id', id);
        fd.append('ct', ct);

        $.ajax({
            url: 'delete_question.php',
            type: 'post',
            cache: false,
            data: fd,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {

                $('#val-msg-quest-del').html(response.message);

                if (response.status == 1) {
                    $('#message-quest-del').attr('class', 'text-success');
                    $('#quest-del-confirm-text').html('');
                    $('#questDelSubmit').css('display', 'none');
                    $('#questDelCancel').html("Zatvori");
                    $('#questDelModal').on('hidden.bs.modal', function (event) {
                        location.href = location.href;
                    });
                    refreshOnClose();
                } else {
                    $('#message-quest-del').attr('class', 'text-danger');
                }
            },
            error: function () {
                $('#message-quest-del').attr('class', 'text-danger');
                $('#val-msg-quest-del').html("Dogodila se pogreška!");
            }
        });
    });
});