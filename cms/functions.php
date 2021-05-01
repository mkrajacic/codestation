<?php
function connect()
{
  $servername = "localhost";
  $username = "admin";
  $password = "admin5";
  $db = "coding";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
  return $conn;
}
?>

<?php
function user_header($user_id, $db)
{
  $user = new User($db);
  if ($stmt = $user->getUserById($user_id)) {
    $user_row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($user_row);
?>
    <nav class="navbar navbar-expand-lg navbar-light bg-dark border-bottom">
      <ul class="nav navbar-nav ml-auto">
        <li class="dropdown">
          <a href="#" class="nav-link dropdown-toggle text-white" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
            <span>Dobrodošli, </span> <span class="text-pink"><?php echo " " . $username ?></span> <b class="caret"></b>
          </a>
          <div class="dropdown-menu dropdown-menu-right bg-secondary">
            <img class="center" src="<?php
                                      if (!is_null($image)) {
                                        echo "img/user/" . $image;
                                      } else {
                                        echo "img/default.jpg";
                                      }
                                      ?>">
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-white" href="#">Novo korisničko ime</a>
            <a class="dropdown-item text-white" href="#" data-toggle="modal" data-target="#userimgModal">Uredi sliku profila</a>
            <a class="dropdown-item text-white" href="#">Promijena lozinke</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-warning" href="logout.php">Odjava</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-pink" href="#">Deaktivacija</a>
          </div>
        </li>
      </ul>
    </nav>

    <!-- user image edit modal -->
    <div class="modal fade" id="userimgModal" tabindex="-1" role="dialog" aria-labelledby="userimgModal" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-dark" id="userimgModalLabel">Uredi sliku profila</h5>
            <button type="button" id="close-button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <?php
            show_modal_messages();
            ?>
            <form method="post" action="edit_user_image.php" enctype="multipart/form-data" id="userImg">
              <input type="hidden" name="submitted" id="submitted">
              <input type="hidden" name="id" value="<?php echo $id ?>">
              <div class="form-group">
                <label class="text-dark" for="lang-img">Slika profila</label>
                <input type="file" class="form-control-file" id="user-img" name="user-img">
                <small id="langnameHelp" class="form-text text-muted">Datoteka ne smije biti veća od 2MB. Dozvoljeni formati datoteke su png, jpg i jpeg.</small>
              </div>
          </div>
          <div class="modal-footer">
            <button type="submit" id="userimgSubmit" class="btn btn-pink">Uredi sliku</button>
            </form>
            <a class="btn btn-outline-danger" href="delete_user_image.php?id=<?php echo $id ?>" role="button">Obriši sliku profila</a>
          </div>
        </div>
      </div>
    </div>

    <!-- lang image del modal -->
    <div class="modal fade" id="userimgdelModal" tabindex="-1" role="dialog" aria-labelledby="userimgdelModal" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-dark">Obriši sliku profila</h5>
            <button type="button" id="close-button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <?php
            show_modal_messages();
            ?>
          </div>
          <div class="modal-footer">
            <button type="button" id="userImgdelConfirm" class="btn btn-pink" data-dismiss="modal" aria-label="Close">U redu</button>
          </div>
        </div>
      </div>
    </div>
<?php
  }
}
?>

<?php
function delete_confirmation($deleted_item_label, $deleted_item, $delete_url, $delete_button)
{
?>
  <p>Jeste li sigurni da želite obrisati . <?php echo $deleted_item_label . " '" . $deleted_item . "' "; ?>?</p>
  <a class="btn btn-pink" href="<?php echo $delete_url ?>" role="button"><?php echo $delete_button ?></a>
  <button type="button" onclick="window.history.go(-1);" class="btn btn-outline-light-pink">Odustani</button>
<?php
}
?>

<?php
function sidemenu($menu_items, $menu_links, $category = "Index")
{
?>
  <div class="bg-dark border-right" id="sidebar-wrapper">
    <div class="sidebar-heading"><a class="text-white" style="text-decoration:none;" href="index.php">MK</a></div>
    <div class="list-group list-group-flush">

      <?php
      $count = 0;
      foreach ($menu_items['main'] as $item) {
        if ($item == $category) {
      ?>

          <a href="<?php echo $menu_links['main'][$count] ?>" class="bg-dark list-group-item list-group-item-action text-pink" style="background-color:#1d2124 !important;"><?php echo $item ?></a>
          <?php
          $count++;
          $subcount = 0;
          foreach ($menu_items['sub'] as $subitem) {
          ?>
            <a href="<?php echo $menu_links['sub'][$subcount] ?>" class="bg-dark list-group-item list-group-item-action text-pink" style="background-color:#323e4a !important;"><?php echo $subitem ?></a>
          <?php
            $subcount++;
          }
        } else {
          ?>
          <a href="<?php echo $menu_links['main'][$count] ?>" class="bg-dark list-group-item list-group-item-action text-pink"><?php echo $item ?></a>
      <?php
          $count++;
        }
      }
      ?>
    </div>
  </div>
<?php
}
?>

<?php
function validateLanguage($form_fields, $form_names, $db, $id = null)
{
  $errors = array();
  $count = 0;

  $lang = new Language($db);

  foreach ($form_fields as $field) {

    if (!isset($_POST["$field"])) {
      array_push($errors, "Polje '" . $form_names[$count] . "' je obavezno!");
    } else if (empty($_POST["$field"])) {
      array_push($errors, "Polje '" . $form_names[$count] . "' je obavezno!");
    } else if (ctype_space($_POST["$field"])) {
      array_push($errors, "Polje '" . $form_names[$count] . "' je obavezno!");
    } else {
      if ($field == "lang-name") {
        if (strlen($_POST["$field"]) > 25) {
          array_push($errors, "Polje '" . $form_names[$count] . "' ne smije biti duže od 25 znakova!");
        }
        $lang->set_name(trim(htmlspecialchars(strip_tags($_POST["$field"]))));
        if (!empty($id)) {
          $lang->set_id(trim(htmlspecialchars(strip_tags($id))));
        }
        if (!$lang->isUniqueName()) {
          array_push($errors, "Već postoji jezik sa istim nazivom!");
        }
      }
      if ($field == "lang-desc") {
        if (strlen($_POST["$field"]) < 100) {
          array_push($errors, "Polje '" . $form_names[$count] . "' mora sadržavati minimalno 100 znakova!");
        }
      }
    }

    $count++;
  }

  return $errors;
}
?>

<?php
function validate_user($form_fields, $form_names, $db, $id = null)
{
  $errors = array();
  $count = 0;

  $user = new User($db);
  foreach ($form_fields as $field) {

    if (!isset($_POST["$field"])) {
      array_push($errors, "Polje '" . $form_names[$count] . "' je obavezno!");
    } else if (empty($_POST["$field"])) {
      array_push($errors, "Polje '" . $form_names[$count] . "' je obavezno!");
    } else if (ctype_space($_POST["$field"])) {
      array_push($errors, "Polje '" . $form_names[$count] . "' je obavezno!");
    } else {

      if ($field == "usr-username") {

        if (strlen($_POST["$field"]) > 15) {
          array_push($errors, "Polje '" . $form_names[$count] . "' ne smije biti duže od 15 znakova!");
        } else if (strlen($_POST["$field"]) < 3) {
          array_push($errors, "Polje '" . $form_names[$count] . "' ne smije biti kraće od 3 znaka!");
        }

        $user->set_username(trim(htmlspecialchars(strip_tags($_POST["$field"]))));
        if (!empty($id)) {
          $user->set_id(trim(htmlspecialchars(strip_tags($id))));
        }
        if (!$user->isUniqueUsername()) {
          array_push($errors, "Korisničko ime već je zauzeto!");
        }

        $allowed = array("_");

        if (!ctype_alnum(str_replace($allowed, '', $_POST["$field"]))) {
          array_push($errors, "Neispravno korisničko ime!");
        }

        if (!preg_match("#[A-Za-z]+#", $_POST["$field"])) {
          array_push($errors, "Korisničko ime mora sadržavati barem jedno slovo!");
        }
      }

      if ($field == "usr-password") {

        if (strlen($_POST["$field"]) < 6) {
          array_push($errors, "Polje '" . $form_names[$count] . "' mora sadržavati minimalno 6 znakova!");
        }

        $allowed = array("_", "-", ".", "@");

        if (!ctype_alnum(str_replace($allowed, '', $_POST["$field"]))) {
          array_push($errors, "Neispravna lozinka!");
        }

        if (!preg_match("#[A-Z]+#", $_POST["$field"])) {
          array_push($errors, "Lozinka mora sadržavati barem jedno veliko slovo!");
        } else if (!preg_match("#[a-z]+#", $_POST["$field"])) {
          array_push($errors, "Lozinka mora sadržavati barem jedno malo slovo!");
        } else if (!preg_match("#[-_.@]+#", $_POST["$field"])) {
          array_push($errors, "Lozinka mora sadržavati barem jedan posebni znak!");
        } else if (!preg_match("#[0-9]+#", $_POST["$field"])) {
          array_push($errors, "Lozinka mora sadržavati barem jednu znamenku!");
        }
      }
    }

    $count++;
  }

  return $errors;
}
?>

<?php
function image_upload($img)
{
  $response = array();

  if (!empty($_FILES["$img"]["tmp_name"])) {

    if (@getimagesize($_FILES["$img"]["tmp_name"])) {
      $fileinfo = getimagesize($_FILES["$img"]["tmp_name"]);
      $width = $fileinfo[0];
      $height = $fileinfo[1];
    }

    $allowed_image_extension = array(
      "png",
      "PNG",
      "jpg",
      "jpeg"
    );

    $file_extension = pathinfo($_FILES["$img"]["name"], PATHINFO_EXTENSION);

    if (!in_array($file_extension, $allowed_image_extension)) {
      array_push($response, "Datoteka nije ispravna. Molimo odaberite png, jpg ili jpeg datoteku.");
    } else if (($_FILES["$img"]["size"] > 2000000)) {
      array_push($response, "Datoteka je prevelika.");
    } else if ($width > "1024" || $height > "768") {
      array_push($response, "Dimenzije datoteke moraju biti unutar 1024x768.");
    }
  }

  return $response;
}
?>

<?php
function login(User $user)
{
  session_start();
  $_SESSION['fresh-login'] = 1;
  $_SESSION['user_id'] = $user->get_id();
  $_SESSION['user_role'] = $user->get_role_code();
?>
  <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-dark" id="loginModalLabel">Prijava</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-success">
          Uspješan login!
        </div>
        <div class="modal-footer text-dark">
          <?php
          if (check_user_status() == 1 || check_user_status() == 2) {
          ?>
            <a class="btn btn-pink" href="languages.php" role="button">Jezici</a>
            <a class="btn btn-outline-secondary" href="index.php" role="button">Početna stranica</a>
          <?php
          } else {
          ?>
            <a class="btn btn-outline-secondary" href="index.php" role="button">Početna stranica</a>
          <?php
          }
          ?>
        </div>
      </div>
    </div>
  </div>
<?php
}
?>

<?php
function show_modal($modal_names)
{
  if (isset($_SESSION['show_modal']['name'])) {

    $modal = $_SESSION['show_modal']['name'];

    foreach ($modal_names as $mod) {

      echo "<script>
      $('#$mod').on('hidden.bs.modal', function() {
          $('.val-msg').empty();
      });
    </script>";

      if ($mod == $modal) {
        echo "<script>
      $('#$mod').modal('show');
      </script>";

        unset($_SESSION['show_modal']['name']);
        unset($_SESSION['status']);
        // unset($_SESSION['success']);
        // unset($_SESSION['errors']);
      }
    }
  }
}
?>

<?php
function show_modal_messages()
{
  if (isset($_SESSION['status'])) {

    $status = $_SESSION['status'];
    $messages = $_SESSION['errors'];
    $success = $_SESSION['success'];
    $x = array();
    $y = array();

    foreach ($messages as $m) {
      array_push($x, $m);
    }

    foreach ($success as $s) {
      array_push($y, $s);
    }

    if ($status == 1) {
      foreach ($y as $mes) {
        echo "<div class='text-success'><p class='val-msg'>" . $mes . "<br></p></div>";
      }
    } else if ($status == 0) {
      foreach ($x as $suc) {
        echo "<div class='text-danger'><p class='val-msg'>" . $suc . "<br></p></div>";
      }
    }
  } else if (!empty($_SESSION['redirect_message'])) {
    echo $_SESSION['redirect_message'];
  }
}
?>

<?php
function insert_redirect_modal()
{
?>
  <div class="modal fade" id="redirectModal" tabindex="-1" role="dialog" aria-labelledby="redirectModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-dark" id="exampleModalLabel">Poruka</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-danger">
          <?php
          session_start();
          show_modal_messages();
          ?>
        </div>
        <div class="modal-footer text-dark">
          <a class="btn btn-pink" data-dismiss="modal" aria-label="Close" role="button">U redu</a>
        </div>
      </div>
    </div>
  </div>
<?php
}
?>

<?php
function check_user_status()
{
  if (isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role'] == 'AD') {
      return 1;
    } else if ($_SESSION['user_role'] == 'MOD') {
      return 2;
    } else if ($_SESSION['user_role'] == 'USR') {
      return 3;
    } else {
      return 0;
    }
  } else {
    return 0;
  }
}
?>