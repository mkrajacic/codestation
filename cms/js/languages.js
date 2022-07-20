$(document).ready(function () {

    function refreshOnClose() {
        $('.close').click(function () {
            location.href = location.href;
        });
    }

    $('#langImgModal,#langEditModal,#langDelModal').modal({
        //backdrop: 'static',
        keyboard: false,
        show: false,
    });

    $('.liMButton').click(function () {
        var ClickedButton = $(this).data("name");
        var modal_id = ClickedButton.split("-").pop();

        $('#lang-img-id, #lang-img-del-id').val(modal_id);

        var ld = new FormData();
        ld.append('id', modal_id);
        ld.append('category', 0);

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

                    if(response.disable==0) {
                        $("#langImgDelSubmit").prop("disabled",true);
                        $("#langImgSubmit").prop("disabled",false);
                    }else if(response.disable==1) {
                        $("#langImgDelSubmit").prop("disabled",false);
                        $("#langImgSubmit").prop("disabled",true);
                    }

                    if(response.img) {
                        $('#langimgPreview').attr("src","img/lang/" + response.img);
                    }else{
                        $('#langimgPreview').attr("src","img/default.jpg");
                    }
                }   

            },
            error: function () {
                $('#message-lang-edit').attr('class', 'text-danger');
                $('#val-msg-lang-edit').html("Greška pri dohvaćanju podataka!");
            }
        });

        $('#langImgModal').modal('show');
    });

    $('.leMButton').click(function () {
        var ClickedButton = $(this).data("name");
        var modal_id = ClickedButton.split("-").pop();

        $('#lang-edit-id').val(modal_id);

        var ld = new FormData();
        ld.append('id', modal_id);
        ld.append('category', 1);

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
                    $('#lang-name').val(response.name);
                    tinymce.get("lang-desc").setContent(response.description);
                    $('#lang-c-mode').val(response.compiler_mode);
                    $('#lang-l-version').val(response.language_version);
                    $('#lang-e-mode').val(response.editor_mode);
                }

            },
            error: function () {
                $('#message-lang-edit').attr('class', 'text-danger');
                $('#val-msg-lang-edit').html("Greška pri dohvaćanju podataka!");
            }
        });

        $('#langEditModal').modal('show');
    });

    $('.ldMButton').click(function () {
        var ClickedButton = $(this).data("name");
        var modal_id = ClickedButton.split("-").pop();

        $('#lang-del-id').val(modal_id);

        var ld = new FormData();
        ld.append('id', modal_id);
        ld.append('category', 2);

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
                    $('#lang-del-confirm-text').html("Jeste li sigurni da želite obrisati '" + response.name + "'?");
                }

            },
            error: function () {
                $('#message-lang-edit').attr('class', 'text-danger');
                $('#val-msg-lang-edit').html("Greška pri dohvaćanju podataka!");
            }
        });

        $('#langDelModal').modal('show');
    });

    $('#langImgSubmit').click(function () {

        var fd = new FormData();
        var submitted = $('#lang-img-submitted').val();
        var id = $('#lang-img-id').val();
        var file = $('#lang-img')[0].files[0];
        var ct = $('#lang-img-ct').val();

        fd.append('submitted', submitted);
        fd.append('id', id);
        fd.append('lang-img', file);
        fd.append('ct', ct);

        $.ajax({
            url: 'edit_language_image.php',
            type: 'post',
            cache: false,
            data: fd,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {

                $('#val-msg-lang-img').html(response.message);

                if (response.status == 1) {
                    $('#message-lang-img').attr('class', 'text-success');
                    $('#langImgModal').on('hidden.bs.modal', function (event) {
                        location.href = location.href;
                    });
                    refreshOnClose();
                } else {
                    $('#message-lang-img').attr('class', 'text-danger');
                }
            },
            error: function () {
                $('#message-lang-img').attr('class', 'text-danger');
                $('#val-msg-lang-img').html("Molimo odaberite fotografiju!");
            }
        });
    });

    $('#langImgDelSubmit').click(function () {
        var id = $('#lang-img-del-id').val();
        var ct = $('#lang-img-del-ct').val();

        var fd = new FormData();

        fd.append('id', id);
        fd.append('ct', ct);

        $.ajax({
            url: 'delete_language_image.php',
            type: 'post',
            cache: false,
            data: fd,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {
                if (response.status == 1) {
                    $('#message-lang-img').attr('class', 'text-success');
                    $('#val-msg-lang-img').html(response.message);
                    $('#langImgDelModal').on('hidden.bs.modal', function (event) {
                        location.href = location.href;
                    });
                    refreshOnClose();
                } else {
                    $('#message-lang-img').attr('class', 'text-danger');
                    $('#val-msg-lang-img').html(response.message);
                }
            },
            error: function () {
                $('#message-lang-img').attr('class', 'text-danger');
                $('#val-msg-lang-img').html("Dogodila se pogreška!");
            }
        });
    });

    $('#langEditSubmit').click(function () {
        var fd = new FormData();
        var submitted = $('#lang-edit-submitted').val();
        var id = $('#lang-edit-id').val();
        var name = $('#lang-name').val();
        var description = tinymce.get("lang-desc").getContent();
        var c_mode = $('#lang-c-mode').val();
        var l_version = $('#lang-l-version').val();
        var e_mode = $('#lang-e-mode').val();
        var ct = $('#lang-edit-ct').val();

        fd.append('submitted', submitted);
        fd.append('id', id);
        fd.append('lang-name', name);
        fd.append('lang-desc', description);
        fd.append('lang-version', l_version);
        fd.append('lang-c-mode', c_mode);
        fd.append('lang-e-mode', e_mode);
        fd.append('ct', ct);

        $.ajax({
            url: 'edit_language.php',
            type: 'post',
            cache: false,
            data: fd,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {

                $('#val-msg-lang-edit').html(response.message);

                if (response.status == 1) {
                    $('#message-lang-edit').attr('class', 'text-success');
                    $('#langEditModal').on('hidden.bs.modal', function (event) {
                        location.href = location.href;
                    });
                    refreshOnClose();
                } else {
                    $('#message-lang-edit').attr('class', 'text-danger');
                }
            },
            error: function () {
                $('#message-lang-edit').attr('class', 'text-danger');
                $('#val-msg-lang-edit').html("Dogodila se pogreška!");
            }
        });
    });

    $('#langDelSubmit').click(function () {
        var fd = new FormData();
        var id = $('#lang-del-id').val();
        var ct = $('#lang-del-ct').val();

        fd.append('id', id);
        fd.append('ct', ct);

        $.ajax({
            url: 'delete_language.php',
            type: 'post',
            cache: false,
            data: fd,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {

                $('#val-msg-lang-del').html(response.message);

                if (response.status == 1) {
                    $('#message-lang-del').attr('class', 'text-success');
                    $('#lang-del-confirm-text').html('');
                    $('#langDelSubmit').css('display', 'none');
                    $('#langDelCancel').html("Zatvori");
                    $('#langDelModal').on('hidden.bs.modal', function (event) {
                        location.href = location.href;
                    });
                    refreshOnClose();
                } else {
                    $('#message-lang-del').attr('class', 'text-danger');
                }
            },
            error: function () {
                $('#message-lang-del').attr('class', 'text-danger');
                $('#val-msg-lang-del').html("Dogodila se pogreška!");
            }
        });
    });

});