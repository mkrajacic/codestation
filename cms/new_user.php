<?php
$title = "Novi korisnik";
include_once("header.php");
include_once("../class/user.php");
$db = connect();

session_start();

$auth = isAuthorized();
if (!($auth == 1)) {
    $_SESSION['redirect_message'] = "Nije vam dopušten pristup sadržaju!";
    header_redirect();
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $user = new User($db);
    if ($stmt = $user->getUserById($user_id)) {
        $user_row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($user_row);
        $user_name = $username;
        $avi = $image;
    }
    if (check_user_status() == 1) {
        $menu_items['main'] = array('Jezici', 'Korisnici');
        $menu_links['main'] = array('languages.php', 'users.php');
    }
} else {
    $_SESSION['redirect_message'] = "Nije vam dopušten pristup sadržaju!";
    header_redirect();
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

$menu_items['sub'] = array();
$menu_links['sub'] = array();
$category = "Korisnici";
sidemenu($menu_items, $menu_links, $category, $user_id, $user_name, $avi);
?>

<div id="page-content-wrapper">
    <div class="container-fluid">
        <h1 class="mt-4 create-new">Novi korisnik</h1>
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
                                Korisnik uspješno kreiran!
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
                <small id="usernameHelp" class="form-text text-y">Korisničko ime ne smije sadržavati manje od 3 ili više od 15 znakova. Dozvoljeni su samo znakovi engleske abecede, brojevi te znak "_". Korisničko ime mora sadržavati barem 1 slovo.</small>
            </div>
            <div class="form-group">
                <label for="usr-password">Lozinka</label>
                <input type="password" class="form-control" id="usr-password" name="usr-password" aria-describedby="passwordHelp" placeholder="Upišite lozinku" <?php if (isset($_POST['usr-password'])) { ?> value="<?php echo $_POST['usr-password'] ?>" <?php } ?>>
                <small id="passwordHelp" class="form-text text-y">Lozinka mora sadržavati barem jednu znamenku i poseban znak, jedno veliko slovo te jedno malo slovo. Minimalan broj znakova je 6. Dozvoljeni posebni znakovi su "_", "-", "." i "@".</small>
            </div>
            <div class="form-group">
                <label for="usr-password-repeat">Ponovite lozinku</label>
                <input type="password" class="form-control" id="usr-password-repeat" name="usr-password-repeat" aria-describedby="passwordRepeatHelp" placeholder="Ponovite lozinku" <?php if (isset($_POST['usr-password-repeat'])) { ?> value="<?php echo $_POST['usr-password-repeat'] ?>" <?php } ?>>
                <small id="passwordRepeatHelp" class="form-text text-y">Lozinka mora sadržavati barem jednu znamenku, jedno veliko slovo te jedno malo slovo. Minimalan broj znakova je 6.</small>
            </div>
            <button type="submit" class="btn btn-x">Pošalji</button>
        </form>
    </div>

    <?php
    include_once("footer.php");
    ?>