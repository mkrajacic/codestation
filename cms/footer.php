<footer>
    <div class='footer-copyright text-center'>©Em Kei 2021</div>
</footer>
</div>
</div>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('#userimgSubmit').click(function() {
            var fd = new FormData();
            var submitted = $('#submitted').val();
            var user_id = $('#user-id').val();
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

    $('.modal').on('hidden.bs.modal', function() {
        $('.val-msg').empty();
    });
</script>
</body>

</html>