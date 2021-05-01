<?php
$title = "Registracija";
include_once("header.php");
include_once("class/user.php");
$db = connect();

if (isset($_POST['submitted'])) {
    $form_fields = array('usr-username', 'usr-password', 'usr-password-repeat');
    $form_names = array('Korisničko ime', 'Lozinka', 'Ponovljena lozinka');
    $errors = validate_user($form_fields, $form_names, $db);

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

$menu_items['main'] = array('Prijava');
$menu_links['main'] = array('login.php');
sidemenu($menu_items, $menu_links, "Korisnici");
?>

<div id="page-content-wrapper">
    <div class="container-fluid">
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
                <label for="usr-username">Korisničko ime</label>
                <input type="text" class="form-control" id="usr-username" name="usr-username" aria-describedby="usernameHelp" placeholder="Upišite korisničko ime" <?php if (isset($_POST['usr-username'])) { ?> value="<?php echo $_POST['usr-username'] ?>" <?php } ?>>
                <small id="usernameHelp" class="form-text text-pink">Korisničko ime ne smije sadržavati manje od 3 ili više od 15 znakova. Dozvoljeni su samo znakovi engleske abecede, brojevi te znak "_". Korisničko ime mora sadržavati barem 1 slovo.</small>
            </div>
            <div class="form-group">
                <label for="usr-password">Lozinka</label>
                <input type="password" class="form-control" id="usr-password" name="usr-password" aria-describedby="passwordHelp" placeholder="Upišite lozinku" <?php if (isset($_POST['usr-password'])) { ?> value="<?php echo $_POST['usr-password'] ?>" <?php } ?>>
                <small id="passwordHelp" class="form-text text-pink">Lozinka mora sadržavati barem jednu znamenku i poseban znak, jedno veliko slovo te jedno malo slovo. Minimalan broj znakova je 6. Dozvoljeni posebni znakovi su "_", "-", "." i "@".</small>
            </div>
            <div class="form-group">
                <label for="usr-password-repeat">Ponovite lozinku</label>
                <input type="password" class="form-control" id="usr-password-repeat" name="usr-password-repeat" aria-describedby="passwordRepeatHelp" placeholder="Ponovite lozinku" <?php if (isset($_POST['usr-password-repeat'])) { ?> value="<?php echo $_POST['usr-password-repeat'] ?>" <?php } ?>>
                <small id="passwordRepeatHelp" class="form-text text-pink">Lozinka mora sadržavati barem jednu znamenku i poseban znak, jedno veliko slovo te jedno malo slovo. Minimalan broj znakova je 6. Dozvoljeni posebni znakovi su "_", "-", "." i "@".</small>
            </div>
            <button type="submit" class="btn btn-pink">Pošalji</button>
        </form>
    </div>

    <?php
    include_once("footer.php");

    session_start();
    if(check_user_status() != 0) {
        $_SESSION['redirect_message'] = "Već ste registrirani u sustav!";
        $_SESSION['show_modal']['name'] = "redirectModal";
        header("Location: index.php");
    }
    ?>