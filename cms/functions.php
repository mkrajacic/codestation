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
function sidemenu($menu_items, $menu_links)
{
?>
  <div class="bg-light border-right" id="sidebar-wrapper">
    <div class="sidebar-heading">MK</div>
    <div class="list-group list-group-flush">
    
      <?php
      $count = 0;
      foreach ($menu_items as $item) {
      ?>

        <a href="<?php echo $menu_links[$count] ?>" class="list-group-item list-group-item-action bg-light"><?php echo $item ?></a>

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
            <a class="nav-link" href="<?php echo $menu_links[$count] ?>"><?php echo $item ?></a>
          </li>
        <?php
        } else {
        ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $menu_links[$count] ?>"><?php echo $item ?></a>
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