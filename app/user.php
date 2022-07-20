<?php
$title = "Korisnički profil";
include_once("header.php");
include_once("../class/language.php");
include_once("../class/lesson.php");
include_once("../class/user.php");
include_once("../class/user_progress_lesson.php");
include_once("../class/user_progress_question.php");
include_once("../class/user_progress_language.php");
$db = connect();
session_start();

if (isset($_GET['username'])) {
    $username = $_GET['username'];
} else {
    $_SESSION['redirect_message'] = "Greška pri učitavanju sadržaja!";
    header_redirect();
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $user = new User($db);
    if ($stmt = $user->getUserByUsername($username)) {
        $user_row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($user_row);
        $avi = $image;
        $selected_id = $id;
    } else {
        $_SESSION['redirect_message'] = "Greška pri učitavanju sadržaja!";
        header_redirect();
    }

    $own_user = new User($db);
    if ($own_stmt = $own_user->getUserById($user_id)) {
        $own_user_row = $own_stmt->fetch(PDO::FETCH_ASSOC);
        $own_avi = $own_user_row['image'];
        $user_name = $own_user_row['username'];
    }
} else {
    $_SESSION['redirect_message'] = "Nemate pravo pristupa sadržaju!";
    header_redirect();
}

$lesson_progress = new LessonProgress($db);
$lesson_progress->set_user_id($selected_id);
$check_less_progress = $lesson_progress->compareLessonProgress(true);

$passed_languages = array();
$passed_img = array();
$languages_in_progress = array();
$percentages = array();

$c = 0;
while ($compare = $check_less_progress->fetch(PDO::FETCH_ASSOC)) {

    if ($compare['passed_lessons'] == $compare['lessons_in_language']) {
        array_push($passed_languages, $compare['language_name']);
        array_push($passed_img, $compare['language_image']);
    } else {
        $max = $compare['lessons_in_language'];
        $percentage = ($compare['passed_lessons'] / $max) * 100;
        array_push($languages_in_progress, $compare['language_name']);
        array_push($percentages, $percentage);
    }
    $c++;
}
?>
<div id="wrapper-list">
    <div class="back-button">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-short smallDivs" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z" />
        </svg>
    </div>
    <div class="profile-button">
        <?php user_header($user_id, htmlspecialchars(strip_tags($user_name)), $db); ?>
        <img id="navbarDropdown" data-toggle="dropdown" aria-expanded="false" class="center avi" width="50" height="auto" src="<?php if (!is_null($own_avi)) {
                                                                                                                                    echo "../cms/img/user/" . $own_avi;
                                                                                                                                } else {
                                                                                                                                    echo "img/default.jpg";
                                                                                                                                } ?>">
    </div>
    <div id="upper-list">
        <div class="container-text"><?php echo htmlspecialchars(strip_tags($username)); ?></div>
    </div>
    <div id="outer" class="user">
        <div id="cards" class="details profile">
            <div class='card text-center mb-3 list-card user-card avi-card'>
                <div class='card-body' style="overflow: initial;">
                    <img class="larger-avi" height="auto" src="<?php if (!is_null($avi)) {
                                                                    echo "../cms/img/user/" . $avi;
                                                                } else {
                                                                    echo "img/default.jpg";
                                                                } ?>">
                </div>
            </div>
            <?php if ($selected_id == $user_id) {  ?>
                <div class='card text-center mb-3 list-card user-card'>
                    <div class='card-body'>
                        <input type="hidden" value="<?php echo $user_id ?>" id="user-img-del-id">
                        <input type="hidden" value="<?php echo generateToken(); ?>" id="user-img-del-ct">
                        <input type="button" class="btn btn-outline-light " id="userImgDelSubmit" value="Obriši sliku" <?php if (is_null($avi)) {
                                                                                                                            echo "disabled";
                                                                                                                        } ?>>
                        <button class="btn btn-outline-light userimgButton" data-toggle="modal" data-target="#userimgModal" <?php if (!is_null($avi)) {
                                                                                                                                echo "disabled";
                                                                                                                            } ?>>Uredi sliku profila</button>
                        <button class="btn btn-outline-light usernameButton" data-toggle="modal" data-target="#usernameModal">Novo korisničko ime</button>
                        <button class="btn btn-outline-light passwordButton" data-toggle="modal" data-target="#passwordModal">Promijena lozinke</button>
                        <button class="btn btn-outline-light deactivateButton" data-toggle="modal" data-target="#deactivateModal">Deaktivacija</button>
                    </div>
                </div>
            <?php } ?>
            <div class='card text-center mb-3 list-card user-card' style="width: 25%;">
                <div class='card-body'>
                    <h3 class='card-title'>Završeni jezici:</h3>
                    <?php
                    $p = 0;
                    if (!empty($passed_languages)) { ?>
                        <?php
                        foreach ($passed_languages as $passed) {    ?>
                            <img id="passedlangImg" class="card-img-icon" title="<?php echo htmlspecialchars(strip_tags($passed)); ?>" alt="<?php echo htmlspecialchars(strip_tags($passed)); ?>" src="<?php if (!is_null($passed_img[$p])) {
                                                                                                                                                echo "../cms/img/lang/" . $passed_img[$p];
                                                                                                                                            } else {
                                                                                                                                                echo "img/default.jpg";
                                                                                                                                            } ?>">
                    <?php
                            $p++;
                        }
                    } else {
                        echo "<span class='user-card-span'>Nema... Zasad ;)</span>";
                    }
                    ?>
                </div>
            </div>
            <div class='card text-center mb-3 list-card user-card'>
                <div class='card-body'>
                    <h3 class='card-title'>Napredak po jezicima:</h3>
                    <?php
                    if (!empty($languages_in_progress)) {
                        $l = 0;
                        foreach ($languages_in_progress as $progress) {
                    ?>
                            <span class='user-card-span'><?php echo htmlspecialchars(strip_tags($progress)); ?><span class="percentage">(<?php echo round($percentages[$l],0); ?>%)</span></span>
                            <div class="progress" style="font-size:14px">
                                <div class="progress-bar user-bar" role="progressbar" style="font-size: 18px; width: <?php echo round($percentages[$l],0); ?>%;" aria-valuenow="<?php echo round($percentages[$l],0); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                    <?php
                            $l++;
                        }
                    } else {
                        echo "<span class='user-card-span'>Nema... Zasad ;)</span>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- user image edit modal -->
<div class="modal fade" id="userimgModal" tabindex="-1" role="dialog" aria-labelledby="userimgModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userimgModalLabel">Uredi sliku profila</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="message" class='text-success'>
                    <p class="val-msg" id='val-msg'></p>
                </div>
                <form method="post" action="" enctype="multipart/form-data" id="userImg">
                    <input type="hidden" id="user-image-submitted">
                    <input type="hidden" value="<?php echo $user_id ?>" id="user-img-edit-id">
                    <input type="hidden" value="<?php echo generateToken(); ?>" id="user-img-edit-ct">
                    <div class="form-group">
                        <label for="user-img">Slika profila<svg data-toggle="tooltip" data-placement="top" title="Datoteka ne smije biti veća od 2MB. Dozvoljeni formati datoteke su png, jpg i jpeg." xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle tooltip-svg" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                        </svg></label>
                        <input type="file" class="form-control-file" id="user-img">
                    </div>
                    <input type="button" id="userimgSubmit" class="btn btn-lg btn-secondary fw-bold border-white bg-white text-dark" value="Uredi sliku">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- edit username modal -->
<div class="modal fade" id="usernameModal" tabindex="-1" role="dialog" aria-labelledby="usernameModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title " id="usernameModalLabel">Promijeni korisničko ime</h5>
                <button type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="username-message" class='text-success'>
                    <p class="val-msg" id='val-msg-username'></p>
                </div>
                <form method="post" action="" id="username" enctype="multipart/form-data">
                    <input type="hidden" id="user-name-submitted">
                    <input type="hidden" value="<?php echo $user_id ?>" id="user-name-id">
                    <input type="hidden" value="<?php echo generateToken(); ?>" id="user-name-ct">
                    <div class="form-group">
                        <label class="" for="usr-username">Korisničko ime<svg data-toggle="tooltip" data-placement="top" title="Korisničko ime ne smije sadržavati manje od 3 ili više od 15 znakova. Dozvoljeni su samo znakovi engleske abecede, brojevi te znak '_'. Korisničko ime mora sadržavati barem 1 slovo." xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle tooltip-svg" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                        </svg></label>
                        <input type="text" class="form-control" id="usr-username" aria-describedby="usernameHelp" placeholder="Upišite korisničko ime" value="<?php echo $user_name ?>">
                    </div>
            </div>
            <div class="modal-footer">
                <input type="button" id="usernameSubmit" class="btn btn-lg btn-secondary fw-bold border-white bg-white text-dark" value="U redu">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- change password modal -->
<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title " id="passwordModalLabel">Promijeni lozinku</h5>
                <button type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="password-message" class='text-success'>
                    <p class="val-msg" id='val-msg-password'></p>
                </div>
                <form method="post" action="" id="password" enctype="multipart/form-data">
                    <input type="hidden" id="user-password-submitted">
                    <input type="hidden" value="<?php echo $user_id ?>" id="user-password-id">
                    <input type="hidden" value="<?php echo generateToken(); ?>" id="user-password-ct">
                    <div class="form-group">
                        <label class="" for="usr-password-old">Stara lozinka</label>
                        <input type="password" class="form-control" id="usr-password-old" placeholder="Upišite staru lozinku">
                    </div>
                    <div class="form-group">
                        <label class="" for="usr-password">Nova lozinka<svg data-toggle="tooltip" data-placement="top" title="Lozinka mora sadržavati barem jednu znamenku, jedno veliko slovo te jedno malo slovo. Minimalan broj znakova je 6." xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle tooltip-svg" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                        </svg></label>
                        <input type="password" class="form-control" id="usr-password" aria-describedby="passwordHelp" placeholder="Upišite novu lozinku">
                    </div>
                    <div class="form-group">
                        <label class="" for="usr-password2">Ponovite novu lozinku</label>
                        <input type="password" class="form-control" id="usr-password2" placeholder="Ponovite novu lozinku">
                    </div>
            </div>
            <div class="modal-footer">
                <input type="button" id="passwordSubmit" class="btn btn-lg btn-secondary fw-bold border-white bg-white text-dark" value="U redu">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- deactivate modal -->
<div class="modal fade" id="deactivateModal" tabindex="-1" role="dialog" aria-labelledby="passwordModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title " id="deactivateModalLabel">Deaktiviraj profil</h5>
                <button type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class=" d-warning">Jeste li sigurni da želite deaktivirati svoj korisnički profil?</h5>
                <div id="deactivate-message">
                    <p class="val-msg" id='val-msg-deactivate'></p>
                </div>
                <form method="post" action="" id="deactivate" enctype="multipart/form-data">
                    <input type="hidden" id="user-deactivate-submitted">
                    <input type="hidden" value="<?php echo $user_id ?>" id="user-deactivate-id">
                    <input type="hidden" value="<?php echo generateToken(); ?>" id="user-deactivate-ct">
            </div>
            <div class="modal-footer">
                <input type="button" id="deactivateSubmit" class="btn btn-lg btn-secondary fw-bold border-white bg-white text-dark" value="Deaktiviraj">
                </form>
            </div>
        </div>
    </div>
</div>
<?php
include_once("footer.php");
?>