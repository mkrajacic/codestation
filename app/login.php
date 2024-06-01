<?php
include_once('../functions.php');
$db = connect();
session_start();
$title = "Prijava";
$include_paths = ['../class/user.php', 'header_index.php'];
foreach ($include_paths as $path)
    include_once($path);

if (check_user_status() != 0) {
    if (!(isset($_SESSION['fresh-login']) && !($_SESSION['fresh-login'] == 1))) {
        $_SESSION['redirect_message'] = "Već ste ulogirani u sustav!";
        $_SESSION['show_modal'] = "redirectModal";
        header_redirect();
    }
}
unset($_SESSION['fresh-login']);

if (isset($_POST['submitted'])) {
    $form_fields = array('login-username', 'login-password');
    $form_names = array('Korisničko ime', 'Lozinka');
    $errors = validate($form_fields, $form_names, $db, null, "User");

    $username = $_POST['login-username'];
    $password = $_POST['login-password'];

    $user = new User($db);
    $user->set_username($username);
    $user->set_password($password);

    if ($user->isUniqueUsername()) {
        array_push($errors, "Krivo korisničko ime!");
    } else {
        if (!$user->isCorrectPassword()) {
            array_push($errors, "Neispravna lozinka!");
        } else {
            if ($stmt = $user->getIdByUsername($user->get_username())) {
                $user_row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($user_row);
                $user_id = $id;
                $user->set_id($user_id);
            } else {
                array_push($errors, "Greška pri učitavanju podataka o korisniku!");
            }

            if ($stmt = $user->getUserById($user_id)) {
                $user_row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($user_row);
                $user_image = $image;
                $user->set_image($user_image);
                $user_role = $role_code;
                $user->set_role_code($user_role);
            } else {
                array_push($errors, "Greška pri učitavanju podataka o korisniku!");
            }
        }
    }
}
?>

<body class="d-flex h-100 text-center text-white">
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
        <?php
        index_menu(0);
        ?>

        <main class="px-3" style="margin-bottom:24vw !important">
            <h1 class="mt-4">Prijava</h1>

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

                            login($user);
                        ?>
                <?php
                        }
                    }
                }
                ?>
                <input type="hidden" name="submitted" id="submitted">
                <div class="form-group">
                    <label for="usr-username">Korisničko ime</label>
                    <input type="text" class="form-control w-50 m-auto" id="login-username" name="login-username" placeholder="Upišite korisničko ime" <?php if (isset($_POST['login-username'])) { ?> value="<?php echo $_POST['login-username'] ?>" <?php } ?>>
                </div>
                <div class="form-group">
                    <label for="login-password">Lozinka</label>
                    <input type="password" class="form-control w-50 m-auto" id="login-password" name="login-password" placeholder="Upišite lozinku" <?php if (isset($_POST['login-password'])) { ?> value="<?php echo $_POST['login-password'] ?>" <?php } ?>>
                </div>
                <button type="submit" class="mt-3 btn btn-lg btn-secondary fw-bold" style="background-color: #fffdc0;">Prijava</button>
            </form>
        </main>
    </div>
</body>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</html>