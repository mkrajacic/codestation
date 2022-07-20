<?php
$title = "Prijava";
include_once("header.php");
include_once("../class/user.php");
$db = connect();

session_start();
if (check_user_status() != 0) {
    if (!(isset($_SESSION['fresh-login']) && $_SESSION['fresh-login'] == 1)) {
        $_SESSION['redirect_message'] = "Već ste ulogirani u sustav!";
        $_SESSION['show_modal'] = "redirectModal";
        header_redirect("languages.php");
    }
}
unset($_SESSION['fresh-login']);

if (isset($_POST['submitted'])) {
    $form_fields = array('login-username', 'login-password');
    $form_names = array('Korisničko ime', 'Lozinka');
    $errors = validate($form_fields, $form_names, $db, null,"User");

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

$menu_items['main'] = array('Registracija');
$menu_links['main'] = array('register.php');
$category = "Korisnici";
sidemenu($menu_items, $menu_links, $category);
?>

<div id="page-content-wrapper">
    <div class="container-fluid">
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
                <input type="text" class="form-control" id="login-username" name="login-username" placeholder="Upišite korisničko ime" <?php if (isset($_POST['login-username'])) { ?> value="<?php echo $_POST['login-username'] ?>" <?php } ?>>
            </div>
            <div class="form-group">
                <label for="login-password">Lozinka</label>
                <input type="password" class="form-control" id="login-password" name="login-password" placeholder="Upišite lozinku" <?php if (isset($_POST['login-password'])) { ?> value="<?php echo $_POST['login-password'] ?>" <?php } ?>>
            </div>
            <button type="submit" class="btn btn-x">Prijava</button>
        </form>
    </div>

    <?php
    include_once("footer.php");
    ?>
    <script>
        function myOnloadFunc() {
            $('#loginModal').modal('show');
        }
        myOnloadFunc();
    </script>