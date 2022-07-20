$(document).ready(function () {

    function refreshOnClose() {
        $('.close').click(function () {
            location.href = location.href;
        });
    }

    $('#lessEditModal,#lessDelModal').modal({
        //backdrop: 'static',
        keyboard: false,
        show: false,
    });

    $('.leMButton').click(function () {

        $('#less-precondition > option:not(:first-child)').remove();
        $('#less-lang > option:not(:first-child)').remove();

        var ClickedButton = $(this).data("name");
        var modal_id = ClickedButton.split("-").pop();

        $('#less-edit-id').val(modal_id);

        var ld = new FormData();
        ld.append('id', modal_id);
        ld.append('category', 3);

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
                    $('#less-name').val(response.name);
                    tinymce.get("less-desc").setContent(response.description);
                    var langMenu = response.languages;
                    var precMenu = response.precondition_lessons;

                    for (var i in langMenu) {
                        if (!(langMenu[i].id === undefined)) {

                            if (langMenu[i].id == response.language) {
                                $('#less-lang').append('<option value="' + langMenu[i].id + '" selected>' + langMenu[i].name + '</option>');
                            } else {
                                $('#less-lang').append('<option value="' + langMenu[i].id + '">' + langMenu[i].name + '</option>');
                            }

                        }
                    }

                    for (var p in precMenu) {
                        if (!(precMenu[p].id === undefined)) {

                            if (precMenu[p].id == response.precondition) {
                                $('#less-precondition').append('<option value="' + precMenu[p].id + '" selected>' + precMenu[p].name + '</option>');
                            } else {
                                $('#less-precondition').append('<option value="' + precMenu[p].id + '">' + precMenu[p].name + '</option>');
                            }

                        }
                    }

                }

            },
            error: function (xhr) {
                $('#message-less-edit').attr('class', 'text-danger');
                $('#val-msg-less-edit').html("Greška pri dohvaćanju podataka!");
            }
        });

        $('#lessEditModal').modal('show');
    });

    $('.ldMButton').click(function () {
        var ClickedButton = $(this).data("name");
        var modal_id = ClickedButton.split("-").pop();

        $('#less-del-id').val(modal_id);

        var ld = new FormData();
        ld.append('id', modal_id);
        ld.append('category', 4);

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
                    $('#less-del-confirm-text').html("Jeste li sigurni da želite obrisati '" + response.name + "'?");
                }

            },
            error: function () {
                $('#message-less-edit').attr('class', 'text-danger');
                $('#val-msg-less-edit').html("Greška pri dohvaćanju podataka!");
            }
        });

        $('#lessDelModal').modal('show');
    });

    $('#lessEditSubmit').click(function () {

        var fd = new FormData();
        var submitted = $('#less-edit-submitted').val();
        var id = $('#less-edit-id').val();
        var name = $('#less-name').val();
        //var description = $('#less-desc').val();
        var description = tinymce.get("less-desc").getContent();
        var language_id = $('#less-lang').val();
        var lesson_id = $('#less-precondition').val();
        var ct = $('#less-edit-ct').val();

        fd.append('submitted', submitted);
        fd.append('id', id);
        fd.append('less-name', name);
        fd.append('less-desc', description);
        fd.append('less-lang', language_id);
        fd.append('less-precondition', lesson_id);
        fd.append('ct', ct);

        $.ajax({
            url: 'edit_lesson.php',
            type: 'post',
            cache: false,
            data: fd,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {

                $('#val-msg-less-edit').html(response.message);

                if (response.status == 1) {
                    $('#message-less-edit').attr('class', 'text-success');
                    $('#lessEditModal').on('hidden.bs.modal', function (event) {
                        location.href = location.href;
                    });
                    refreshOnClose();
                } else {
                    $('#message-less-edit').attr('class', 'text-danger');
                }
            },
            error: function () {
                $('#message-less-edit').attr('class', 'text-danger');
                $('#val-msg-less-edit').html("Dogodila se pogreška!");
            }
        });
    });

    $('#lessDelSubmit').click(function () {

        var fd = new FormData();
        var id = $('#less-del-id').val();
        var ct = $('#less-del-ct').val();

        fd.append('id', id);
        fd.append('ct', ct);

        $.ajax({
            url: 'delete_lesson.php',
            type: 'post',
            cache: false,
            data: fd,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {

                $('#val-msg-less-del').html(response.message);

                if (response.status == 1) {
                    $('#message-less-del').attr('class', 'text-success');
                    $('#less-del-confirm-text').html('');
                    $('#lessDelSubmit').css('display', 'none');
                    $('#lessDelCancel').html("Zatvori");
                    $('#lessDelModal').on('hidden.bs.modal', function (event) {
                        location.href = location.href;
                    });
                    refreshOnClose();
                } else {
                    $('#message-less-del').attr('class', 'text-danger');
                }
            },
            error: function () {
                $('#message-less-del').attr('class', 'text-danger');
                $('#val-msg-less-del').html("Dogodila se pogreška!");
            }
        });
    });

});