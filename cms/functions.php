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
function nav($menu_items, $menu_links)
{
?>
  <nav class="navbar navbar-expand-lg navbar-light bg-dark border-bottom">
    <button class="btn btn-pink" id="menu-toggle">Sakrij meni</button>
    <?php
    submenu($menu_items, $menu_links);
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
function sidemenu($menu_items, $menu_links)
{
?>
  <div class="bg-dark border-right" id="sidebar-wrapper">
    <div class="sidebar-heading">MK</div>
    <div class="list-group list-group-flush">

      <?php
      $count = 0;
      foreach ($menu_items as $item) {
      ?>

        <a href="<?php echo $menu_links[$count] ?>" class="bg-dark list-group-item list-group-item-action text-pink"><?php echo $item ?></a>

      <?php
        $count++;
      }
      ?>

    </div>
  </div>
<?php
}
?>

<?php
function submenu($menu_items, $menu_links)
{
?>
  <!-- mobile button -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
      <?php
      $count = 0;
      foreach ($menu_items as $item) {
        if ($count == 0) {
      ?>
          <li class="nav-item active">
            <a class="nav-link text-white" href="<?php echo $menu_links[$count] ?>"><?php echo $item ?></a>
          </li>
        <?php
        } else {
        ?>
          <li class="nav-item">
            <a class="nav-link text-pink" href="<?php echo $menu_links[$count] ?>"><?php echo $item ?></a>
          </li>
      <?php
        }
        $count++;
      }
      ?>
    </ul>
  </div>
<?php
}
?>

<?php
function validateLanguage($form_fields, $form_names, $db,$id=null)
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
        if(!empty($id)) {
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