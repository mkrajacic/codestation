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
function user_header()
{
?>
  <nav class="navbar navbar-expand-lg navbar-light bg-dark border-bottom">
    <?php
    echo "la genesi";
    ?>
  </nav>
<?php
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

        if(!preg_match("#[A-Za-z]+#",$_POST["$field"])) {
          array_push($errors, "Korisničko ime mora sadržavati barem jedno slovo!");
        }

      }

      if ($field == "usr-password") {

        if (strlen($_POST["$field"]) < 6) {
          array_push($errors, "Polje '" . $form_names[$count] . "' mora sadržavati minimalno 6 znakova!");
        }

        $allowed = array("_","-",".","@");

        if (!ctype_alnum(str_replace($allowed, '', $_POST["$field"]))) {
          array_push($errors, "Neispravna lozinka!");
        }

        if(!preg_match("#[A-Z]+#",$_POST["$field"])) {
          array_push($errors, "Lozinka mora sadržavati barem jedno veliko slovo!");
        }else if(!preg_match("#[a-z]+#",$_POST["$field"])) {
          array_push($errors, "Lozinka mora sadržavati barem jedno malo slovo!");
        }else if(!preg_match("#[-_.@]+#",$_POST["$field"])) {
          array_push($errors, "Lozinka mora sadržavati barem jedan posebni znak!");
        }else if(!preg_match("#[0-9]+#",$_POST["$field"])){
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