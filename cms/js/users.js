$(document).ready(function () {

    function refreshOnClose() {
        $('.close').click(function () {
            location.href = location.href;
        });
    }

    $('#list-userimgModal,#list-userModal,#list-deactivateModal').modal({
        //backdrop: 'static',
        keyboard: false,
        show: false,
    });

    $('.uiMButton').click(function () {
        var ClickedButton = $(this).data("name");
        var modal_id = ClickedButton.split("-").pop();

        $('#list-user-img-edit-id').val(modal_id);

        var ld = new FormData();
        ld.append('id', modal_id);
        ld.append('category', 11);

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

                    if (response.disable == 0) {
                        $("#list-userImgDelSubmit").prop("disabled", true);
                        $("#list-userImgSubmit").prop("disabled", false);
                    } else if (response.disable == 1) {
                        $("#list-userImgDelSubmit").prop("disabled", false);
                        $("#list-userImgSubmit").prop("disabled", true);
                    }

                    if (response.img) {
                        $('#list-userimgPreview').attr("src", "img/user/" + response.img);
                    } else {
                        $('#list-userimgPreview').attr("src", "img/default.jpg");
                    }
                }

            },
            error: function () {
                $('#list-ui-message').attr('class', 'text-danger');
                $('#val-msg-list-ui').html("Greška pri dohvaćanju podataka!");
            }
        });

        $('#list-userimgModal').modal('show');
    });

    $('.ueMButton').click(function () {
        var ClickedButton = $(this).data("name");
        var modal_id = ClickedButton.split("-").pop();

        $('#list-user-name-id').val(modal_id);

        var ld = new FormData();
        ld.append('id', modal_id);
        ld.append('category', 11);

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
                    $('#usr-list-username').val(response.username);
                }

            },
            error: function () {
                $('#list-user-message').attr('class', 'text-danger');
                $('#val-msg-list-user').html("Greška pri dohvaćanju podataka!");
            }
        });

        $('#list-userModal').modal('show');
    });

    $('.udMButton').click(function () {
        var ClickedButton = $(this).data("name");
        var modal_id = ClickedButton.split("-").pop();

        $('#user-list-deactivate-id').val(modal_id);

        var ld = new FormData();
        ld.append('id', modal_id);
        ld.append('category', 11);

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
                    $('#list-deactivate-confirm').html("Jeste li sigurni da želite obrisati profil '" + response.username + "'?");
                }

            },
            error: function () {
                $('#list-deactivate-message').attr('class', 'text-danger');
                $('#val-msg-list-deactivate').html("Greška pri dohvaćanju podataka!");
            }
        });

        $('#list-deactivateModal').modal('show');
    });

    $('.uerMButton').click(function () {
        var ClickedButton = $(this).data("name");
        var modal_id = ClickedButton.split("-").pop();

        $('#list-user-role-id').val(modal_id);
        $('#rc1').val("AD");
        $('#rc2').val("MOD");
        $('#rc3').val("USR");
        $('#rc1').html("Administrator");
        $('#rc2').html("Moderator");
        $('#rc3').html("Korisnik");

        var ld = new FormData();
        ld.append('id', modal_id);
        ld.append('category', 11);

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
                    if(response.role=="AD") {
                        $('#rc1').prop("selected",true);
                    }else if(response.role=="MOD") {
                        $('#rc2').prop("selected",true);
                    }else if(response.role=="USR") {
                        $('#rc3').prop("selected",true);
                    }
                }

            },
            error: function () {
                $('#list-user-role-message').attr('class', 'text-danger');
                $('#val-msg-list-role').html("Greška pri dohvaćanju podataka!");
            }
        });

        $('#list-userRoleModal').modal('show');
    });

    $('#list-userRoleSubmit').click(function () {
        var fd = new FormData();
        var user_id = $('#list-user-role-id').val();
        var role = $('#list-user-role').val();
        var submitted = $('#list-user-name-submitted').val();
        var ct = $('#list-user-name-ct').val();
    
        fd.append('id', user_id);
        fd.append('role', role);
        fd.append('submitted', submitted);
        fd.append('ct', ct);
    
        $.ajax({
            url: 'edit_user_role.php',
            type: 'post',
            cache: false,
            data: fd,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {
    
                $('#val-msg-list-user-role').html(response.message);
    
                if (response.status == 1) {
                    $('#list-user-role-message').attr('class', 'text-success');
                    $('#list-userRoleModal').on('hidden.bs.modal', function (event) {
                        location.href = location.href;
                    });
                    refreshOnClose();
                    $('#list-user-role-form').css('display','none');
                    $('#list-userRoleSubmit').css('display','none');
                } else {
                    $('#list-user-role-message').attr('class', 'text-danger');
                }
            },
            error: function (xhr) {
                $('#list-user-role-message').attr('class', 'text-danger');
                $('#val-msg-list-user-role').html('Dogodila se pogreška!');
            }
        });
    });

    $('#list-userImgSubmit').click(function () {
        var fd = new FormData();
        var submitted = $('#submitted').val();
        var user_id = $('#list-user-img-edit-id').val();
        var file = $('#list-user-img')[0].files[0];
        var ct = $('#list-user-img-edit-ct').val();

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
                    $('#list-ui-message').attr('class', 'text-success');
                    $('#val-msg-list-ui').html(response.message);
                    $('#list-userimgModal').on('hidden.bs.modal', function (event) {
                        location.href = location.href;
                    });
                    refreshOnClose();
                } else {
                    $('#list-ui-message').attr('class', 'text-danger');
                    $('#val-msg-list-ui').html(response.message);
                }
            },
            error: function () {
                $('#list-ui-message').attr('class', 'text-danger');
                $('#val-msg-list-ui').html("Molimo odaberite datoteku!");
            }
        });
    });
});

$('#list-userImgDelSubmit').click(function () {
    var fd = new FormData();
    var user_id = $('#list-user-img-edit-id').val();
    var ct = $('#user-img-edit-ct').val();

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
                $('#list-ui-message').attr('class', 'text-success');
                $('#val-msg-list-ui').html(response.message);
                $('#list-userimgModal').on('hidden.bs.modal', function (event) {
                    location.href = location.href;
                });
                refreshOnClose();
            } else {
                $('#message').attr('class', 'text-danger');
                $('#val-msg').html(response.message);
            }
        },
        error: function (xhr) {
            $('#list-ui-message').attr('class', 'text-danger');
            $('#val-msg-list-ui').html('Dogodila se pogreška!');
        }
    });
});

$('#list-userSubmit').click(function () {
    var fd = new FormData();
    var user_id = $('#list-user-name-id').val();
    var username = $('#usr-list-username').val();
    var new_password = $('#usr-list-password').val();
    var new_password_again = $('#usr-list-password2').val();
    var submitted = $('#list-user-name-submitted').val();
    var ct = $('#list-user-name-ct').val();

    fd.append('user-name-id', user_id);
    fd.append('usr-username', username);
    fd.append('usr-password', new_password);
    fd.append('usr-password2', new_password_again);
    fd.append('submitted', submitted);
    fd.append('ct', ct);

    $.ajax({
        url: 'edit_user.php',
        type: 'post',
        cache: false,
        data: fd,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (response) {

            $('#val-msg-list-user').html(response.message);

            if (response.status == 1) {
                $('#list-user-message').attr('class', 'text-success');
                $('#list-userModal').on('hidden.bs.modal', function (event) {
                    location.href = location.href;
                });
                refreshOnClose();
            } else {
                $('#list-user-message').attr('class', 'text-danger');
            }
        },
        error: function (xhr) {
            $('#list-user-message').attr('class', 'text-danger');
            $('#val-msg-list-user').html("Dogodila se pogreška!");
        }
    });
});

$('#list-deactivateSubmit').click(function () {
    var fd = new FormData();
    var id = $('#user-list-deactivate-id').val();
    var ct = $('#user-list-deactivate-ct').val();

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

            $('#val-msg-list-deactivate').html(response.message);

            if (response.status == 1) {
                $('#list-deactivate-message').attr('class', 'text-success');
                $('#list-deactivateClose').attr("style","display:block;");
                $('#list-deactivateSubmit').attr("style","display:none;");

                $('.d-warning').html('');

                $('#list-deactivateModal').on('hidden.bs.modal', function (event) {
                    location.href = location.href;
                });
                refreshOnClose();

            } else {
                $('#list-deactivate-message').attr('class', 'text-danger');
            }
        },
        error: function (xhr) {
            $('#list-deactivate-message').attr('class', 'text-danger');
            $('#val-msg-list-deactivate').html("Dogodila se pogreška!");
        }
    });
});