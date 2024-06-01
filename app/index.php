<?php
$title = "Index";
include_once("../class/user.php");
include_once("../functions.php");
$db = connect();
session_start();
if (isset($_SESSION['user_id']))
    $user_id = $_SESSION['user_id'];
else
    $user_id = 0;

header_index($title);
?>

<body class="d-flex h-100 text-center text-white">
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
        <?php
        index_menu($user_id);
        ?>

        <main class="px-3">
            <h1>CodeStation</h1>
            <p class="lead">CodeStation je mjesto za učenje kodiranja.</p>
            <p class="lead">Naučite <span class="lead" id="learn"></span></p>
            <p class="lead">
                <?php if (check_user_status() >= 1) : ?>
                    <a href="languages.php" class="btn btn-lg btn-secondary fw-bold" style="background-color: #fffdc0;">Započnite</a>
                <?php else : ?>
                    <a href="register.php" class="btn btn-lg btn-secondary fw-bold border-white bg-white">Registracija</a>
                    <a href="login.php" class="btn btn-lg btn-secondary fw-bold" style="background-color: #fffdc0;">Prijava</a>
                <?php endif; ?>
            </p>
        </main>

        <footer class="mt-auto text-white-50">
            <p>@MK</p>
        </footer>
        <?php
        floating_background();
        ?>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
<script src="js/typed_text.js"></script>

</html>