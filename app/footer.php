<footer>
    <div class='footer-copyright text-center'>©Em Kei 2021</div>
</footer>
</div>
</div>
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('#userimgSubmit').click(function() {
            var fd = new FormData();
            var submitted = $('#submitted').val();
            var user_id = $('#user-img-edit-id').val();
            var file = $('#user-img')[0].files[0];

            fd.append('submitted', submitted);
            fd.append('user-id', user_id);
            fd.append('user-img', file);

            $.ajax({
                url: 'edit_user_image.php',
                type: 'post',
                cache: false,
                data: fd,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function(response) {
                    if (response.status == 1) {
                        $('#message').attr('class', 'text-success');
                        $('#val-msg').html(response.message);
                    } else {
                        $('#message').attr('class', 'text-danger');
                        $('#val-msg').html(response.message);
                    }
                },
                error: function() {
                    $('#message').attr('class', 'text-danger');
                    $('#val-msg').html("Molimo odaberite datoteku!");
                }
            });
        });
    });

    $('#userImgDelSubmit').click(function() {
        var fd = new FormData();
        var user_id = $('#user-img-del-id').val();

        fd.append('user-id', user_id);

        $.ajax({
            url: 'delete_user_image.php',
            type: 'post',
            cache: false,
            data: fd,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function(response) {
                if (response.status == 1) {
                    $('#message').attr('class', 'text-success');
                    $('#val-msg').html(response.message);
                } else {
                    $('#message').attr('class', 'text-danger');
                    $('#val-msg').html(response.message);
                }
            },
            error: function(/*xhr*/) {
                $('#message').attr('class', 'text-danger');
                //$('#val-msg').html('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                $('#val-msg').html('Dogodila se pogreška!');
            }
        });
    });

    $('#usernameSubmit').click(function() {
        var fd = new FormData();
        var user_id = $('#user-name-id').val();
        var username = $('#usr-username').val();

        fd.append('user-name-id', user_id);
        fd.append('usr-username', username);

        $.ajax({
            url: 'edit_username.php',
            type: 'post',
            cache: false,
            data: fd,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function(response) {
                if (response.status == 1) {
                    $('#username-message').attr('class', 'text-success');
                    $('#val-msg-username').html(response.message);
                } else {
                    $('#username-message').attr('class', 'text-danger');
                    $('#val-msg-username').html(response.message);
                }
            },
            error: function(xhr) {
                $('#username-message').attr('class', 'text-danger');
                $('#val-msg-username').html('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText + JSON.stringify(xhr));
                //$('#val-msg-username').html('Dogodila se pogreška!');
            }
        });
    });

    $('.modal').on('hidden.bs.modal', function() {
        $('.val-msg').empty();
    });
</script>
</body>

</html>