$(document).ready(function () {

    function refreshOnClose() {
        $('.close').click(function () {
            location.href = location.href;
        });
    }

    $('#userimgModal,#usernameModal,#passwordModal,#deactivateModal').modal({
        //backdrop: 'static',
        keyboard: false,
        show: false,
    });

    $('#userimgModal,#usernameModal,#passwordModal').on('shown.bs.modal', function () {
        $('[data-toggle="tooltip"]').tooltip({
            'animation': true,
            'placement': 'top',
            'trigger': 'hover',
            'html': true,
            'container': 'body'
        });
    });

    $('.userimgButton').click(function () {
        $('#userimgModalLabel').html("Uredi sliku profila");
        $('#val-msg').html('');
    });

    $('#userimgSubmit').click(function () {
        var fd = new FormData();
        var submitted = $('#submitted').val();
        var user_id = $('#user-img-edit-id').val();
        var file = $('#user-img')[0].files[0];
        var ct = $('#user-img-edit-ct').val();

        fd.append('submitted', submitted);
        fd.append('user-id', user_id);
        fd.append('user-img', file);
        fd.append('ct', ct);

        $.ajax({
            url: '../cms/edit_user_image.php',
            type: 'post',
            cache: false,
            data: fd,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {
                if (response.status == 1) {
                    $('#message').attr('class', 'text-success');
                    $('#val-msg').html(response.message);
                } else {
                    $('#message').attr('class', 'text-light');
                    $('#val-msg').html(response.message);
                }
            },
            error: function () {
                $('#message').attr('class', 'text-light');
                $('#val-msg').html("Molimo odaberite datoteku!");
            }
        });
    });
});

$('#userImgDelSubmit').click(function () {
    var fd = new FormData();
    var user_id = $('#user-img-del-id').val();
    var ct = $('#user-img-del-ct').val();

    fd.append('user-id', user_id);
    fd.append('ct', ct);

    $.ajax({
        url: '../cms/delete_user_image.php',
        type: 'post',
        cache: false,
        data: fd,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (response) {
            $('#userimgModal').modal('show');
            $('#userimgModalLabel').html("Obriši sliku profila");
            $("#userImg input:not('#userimgSubmit')").css("display","none");
            $("#userImg label").css("display","none");
            $('#userimgSubmit').unbind("click");
            $('#userimgSubmit').val("Zatvori");

            $('#userimgSubmit').click(function () {
                $('#userimgModal').modal('hide');
                location.href = location.href;
            });

            $('#userimgModal').on('hidden.bs.modal', function (event) {
                    location.href = location.href;
            });
            refreshOnClose();

            if (response.status == 1) {
                $('#message').attr('class', 'text-success');
                $('#val-msg').html(response.message);
            } else {
                $('#message').attr('class', 'text-light');
                $('#val-msg').html(response.message);
            }
        },
        error: function (xhr) {
            $('#message').attr('class', 'text-light');
            $('#val-msg').html('Dogodila se pogreška!');
        }
    });
});

$('#usernameSubmit').click(function () {
    var fd = new FormData();
    var user_id = $('#user-name-id').val();
    var username = $('#usr-username').val();
    var submitted = $('#user-name-submitted').val();
    var ct = $('#user-name-ct').val();

    fd.append('user-name-id', user_id);
    fd.append('usr-username', username);
    fd.append('submitted', submitted);
    fd.append('ct', ct);

    $.ajax({
        url: '../cms/edit_username.php',
        type: 'post',
        cache: false,
        data: fd,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (response) {

            $('#val-msg-username').html(response.message);

            if (response.status == 1) {
                $('#username-message').attr('class', 'text-success');
            } else {
                $('#username-message').attr('class', 'text-light');
            }
        },
        error: function (xhr) {
            $('#username-message').attr('class', 'text-light');
            $('#val-msg-username').html('Dogodila se pogreška!');
        }
    });
});

$('#passwordSubmit').click(function () {
    var fd = new FormData();
    var user_id = $('#user-password-id').val();
    var submitted = $('#user-password-submitted').val();
    var old_password = $('#usr-password-old').val();
    var new_password = $('#usr-password').val();
    var new_password_again = $('#usr-password2').val();
    var ct = $('#user-password-ct').val();

    fd.append('user-password-id', user_id);
    fd.append('submitted', submitted);
    fd.append('usr-password-old', old_password);
    fd.append('usr-password', new_password);
    fd.append('usr-password2', new_password_again);
    fd.append('ct', ct);

    $.ajax({
        url: '../cms/change_password.php',
        type: 'post',
        cache: false,
        data: fd,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (response) {

            $('#val-msg-password').html(response.message);
            $('#passwordModal').on('hidden.bs.modal', function (event) {
                    location.href = location.href;
            })
            refreshOnClose();

            if (response.status == 1) {
                $('#password-message').attr('class', 'text-success');
            } else {
                $('#password-message').attr('class', 'text-light');
            }
        },
        error: function (xhr) {
            $('#password-message').attr('class', 'text-light');
            $('#val-msg-password').html('Dogodila se pogreška!');
        }
    });
});

$('#deactivateSubmit').click(function () {
    var fd = new FormData();
    var id = $('#user-deactivate-id').val();
    var ct = $('#user-img-edit-ct').val();
    var ct = $('#user-deactivate-ct').val();

    fd.append('id', id);
    fd.append('ct', ct);

    $.ajax({
        url: '../cms/deactivate_profile.php',
        type: 'post',
        cache: false,
        data: fd,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (response) {

            $('#val-msg-deactivate').html(response.message);

            if (response.status == 1) {
                $('#deactivateSubmit').prop("value", "Zatvori");

                $('.d-warning').html('');

                setTimeout(function () {
                    $('#deactivateModal').modal('hide');
                    location.href = "register.php";
                }, 3000);

                $('#deactivateModal').on('hidden.bs.modal', function (event) {
                    event.preventDefault();
                    location.href = "register.php";
                });

            } else {
                $('#deactivate-message').attr('class', 'text-light');
            }
        },
        error: function () {
            $('#message-answr-del').attr('class', 'text-light');
            $('#val-msg-answr-del').html("Dogodila se pogreška!");
        }
    });
});

