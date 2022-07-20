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
            url: 'edit_user_image.php',
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
                    $('#userimgModal').on('hidden.bs.modal', function (event) {
                        location.href = location.href;
                    });
                    refreshOnClose();
                } else {
                    $('#message').attr('class', 'text-danger');
                    $('#val-msg').html(response.message);
                }
            },
            error: function () {
                $('#message').attr('class', 'text-danger');
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
        url: 'delete_user_image.php',
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
                $('#userimgModal').on('hidden.bs.modal', function (event) {
                    location.href = location.href;
                });
                refreshOnClose();
            } else {
                $('#message').attr('class', 'text-danger');
                $('#val-msg').html(response.message);
            }
        },
        error: function (/*xhr*/) {
            $('#message').attr('class', 'text-danger');
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
        url: 'edit_username.php',
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
                $('#usernameModal').on('hidden.bs.modal', function (event) {
                    location.href = location.href;
                });
                refreshOnClose();
            } else {
                $('#username-message').attr('class', 'text-danger');
            }
        },
        error: function (xhr) {
            $('#username-message').attr('class', 'text-danger');
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
        url: 'change_password.php',
        type: 'post',
        cache: false,
        data: fd,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (response) {

            $('#val-msg-password').html(response.message);

            if (response.status == 1) {
                $('#password-message').attr('class', 'text-success');
                $('#passwordModal').on('hidden.bs.modal', function (event) {
                    location.href = location.href;
                });
                refreshOnClose();
            } else {
                $('#password-message').attr('class', 'text-danger');
            }
        },
        error: function (xhr) {
            $('#password-message').attr('class', 'text-danger');
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
        url: 'deactivate_profile.php',
        type: 'post',
        cache: false,
        data: fd,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (response) {
            
            $('#val-msg-deactivate').html(response.message);

            if (response.status == 1) {
                $('#deactivate-message').attr('class', 'text-success');
                $('#deactivateSubmit').html("Zatvori");

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
                $('#deactivate-message').attr('class', 'text-danger');
            }
        },
        error: function () {
            $('#message-answr-del').attr('class', 'text-danger');
            $('#val-msg-answr-del').html("Dogodila se pogreška!");
        }
    });
});