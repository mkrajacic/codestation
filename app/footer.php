<?php
if(isset($user_id) && isset($user_name)) {
    user_mobile($user_id, htmlspecialchars(strip_tags($user_name)), $db);
}
?>
</body>
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/user_modals.js"></script>
<script src="js/buttons.js"></script>
<script src="js/responsive.js"></script>
</html>