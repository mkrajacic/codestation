<?php
$title = "Registracija";
include_once("../functions.php");
include_once("../class/user.php");
$db = connect();

session_start();

if (isset($_SESSION['deactivated'])) {
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 36000, '/');
    }
    session_destroy();
}

if (check_user_status() != 0) {
    $_SESSION['redirect_message'] = "Već ste registrirani u sustav!";
    header_redirect("index.php");
}

if (isset($_POST['submitted'])) {
    $form_fields = array('usr-username', 'usr-password', 'usr-password-repeat');
    $form_names = array('Korisničko ime', 'Lozinka', 'Ponovljena lozinka');
    $errors = validate($form_fields, $form_names, $db, null, "User");

    $username = $_POST['usr-username'];
    $password1 = $_POST['usr-password'];
    $password2 = $_POST['usr-password-repeat'];

    $user = new User($db);
    $user->set_username($username);

    if ($password1 == $password2) {
        $password_encrypted = password_hash($password1, PASSWORD_BCRYPT);
        $user->set_password($password_encrypted);
    } else {
        array_push($errors, "Upisane lozinke se ne podudaraju!");
    }
}

header_index($title);
?>

<body class="d-flex h-100 text-center text-white">
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
        <?php
        index_menu(0);
        ?>

        <main class="px-3" style="margin-bottom:21vw !important">
            <h1 class="mt-4">Registracija</h1>
            <form method="POST" action="">
                <?php
                if (isset($errors)) {

                    if (sizeof($errors) > 0) {

                        foreach ($errors as $err) {
                ?>
                            <div class="invalid-feedback" style="display:block; font-size:16px">
                                <?php
                                echo $err;
                                ?>
                            </div>
                            <?php
                        }
                    } else {
                        if (isset($user)) {
                            if (!$user->createUser()) {  ?>
                                <div class="invalid-feedback" style="display:block; font-size:16px">
                                    Greška pri kreiranju korisnika!
                                </div>
                            <?php
                            } else {  ?>
                                <div class="valid-feedback" style="display:block; font-size:16px">
                                    Registracija uspješna!
                                </div>
                <?php
                            }
                        }
                    }
                }
                ?>
                <input type="hidden" name="submitted" id="submitted">
                <div class="form-group">
                    <label for="usr-username">Korisničko ime <svg data-toggle="tooltip" data-placement="right" title="Korisničko ime ne smije sadržavati manje od 3 ili više od 15 znakova. Dozvoljeni su samo znakovi engleske abecede, brojevi te znak '_'. Korisničko ime mora sadržavati barem 1 slovo." xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                        </svg></label>
                    <input type="text" class="form-control w-50 m-auto" id="usr-username" name="usr-username" aria-describedby="usernameHelp" placeholder="Upišite korisničko ime" <?php if (isset($_POST['usr-username'])) { ?> value="<?php echo $_POST['usr-username'] ?>" <?php } ?>>
                    <small id="usernameHelp" class="form-text text-y"></small>
                </div>
                <div class="form-group">
                    <label for="usr-password">Lozinka <svg data-toggle="tooltip" data-placement="right" title="Lozinka mora sadržavati barem jednu znamenku i poseban znak, jedno veliko slovo te jedno malo slovo. Minimalan broj znakova je 6. Dozvoljeni posebni znakovi su '_', '-', '.' i '@'." xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                        </svg></label>
                    <input type="password" class="form-control w-50 m-auto" id="usr-password" name="usr-password" aria-describedby="passwordHelp" placeholder="Upišite lozinku" <?php if (isset($_POST['usr-password'])) { ?> value="<?php echo $_POST['usr-password'] ?>" <?php } ?>>
                </div>
                <div class="form-group">
                    <label for="usr-password-repeat">Ponovite lozinku</label>
                    <input type="password" class="form-control w-50 m-auto" id="usr-password-repeat" name="usr-password-repeat" aria-describedby="passwordRepeatHelp" placeholder="Ponovite lozinku" <?php if (isset($_POST['usr-password-repeat'])) { ?> value="<?php echo $_POST['usr-password-repeat'] ?>" <?php } ?>>
                </div>
                <button type="submit" class="mt-3 btn btn-lg btn-secondary fw-bold" style="background-color: #fffdc0;">Pošalji</button>
            </form>
        </main>
    </div>
</body>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/tooltip_init.js"></script>
</html>