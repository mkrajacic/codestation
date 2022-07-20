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
function generateToken()
{
  if (empty($_SESSION['ct']) || ($_SESSION['expireIn'] <= time())) {
    $_SESSION['ct'] = bin2hex(random_bytes(32));
    $_SESSION['expireIn'] = time() + 3600;
  }
  return $_SESSION['ct'];
}
?>

<?php
function checkTokenTime()
{
  if (!empty($_SESSION['ct'])) {
    if ($_SESSION['expireIn'] <= time()) {
      return 0;
    } else {
      return 1;
    }
  }
}
?>

<?php
function user_header($user_id, $username, $db)
{
?>
  <ul class="nav navbar-nav ml-auto">
    <li class="dropdown">
      <div class="dropdown-menu dropdown-menu-right text-center">
        <a class="dropdown-item text-light" href="user.php?username=<?php echo $username; ?>"><?php echo $username; ?></a>
        <a class="dropdown-item logout" href="logout.php">Odjava</a>
        <a class="dropdown-item text-light changeMode"><svg class="moon-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.2256 2.00253C9.59172 1.94346 6.93894 2.9189 4.92893 4.92891C1.02369 8.83415 1.02369 15.1658 4.92893 19.071C8.83418 22.9763 15.1658 22.9763 19.0711 19.071C21.0811 17.061 22.0565 14.4082 21.9975 11.7743C21.9796 10.9772 21.8669 10.1818 21.6595 9.40643C21.0933 9.9488 20.5078 10.4276 19.9163 10.8425C18.5649 11.7906 17.1826 12.4053 15.9301 12.6837C14.0241 13.1072 12.7156 12.7156 12 12C11.2844 11.2844 10.8928 9.97588 11.3163 8.0699C11.5947 6.81738 12.2094 5.43511 13.1575 4.08368C13.5724 3.49221 14.0512 2.90664 14.5935 2.34046C13.8182 2.13305 13.0228 2.02041 12.2256 2.00253ZM17.6569 17.6568C18.9081 16.4056 19.6582 14.8431 19.9072 13.2186C16.3611 15.2643 12.638 15.4664 10.5858 13.4142C8.53361 11.362 8.73568 7.63895 10.7814 4.09281C9.1569 4.34184 7.59434 5.09193 6.34315 6.34313C3.21895 9.46732 3.21895 14.5326 6.34315 17.6568C9.46734 20.781 14.5327 20.781 17.6569 17.6568Z" fill="currentColor" />
          </svg>
          <svg class="sun-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 16C14.2091 16 16 14.2091 16 12C16 9.79086 14.2091 8 12 8C9.79086 8 8 9.79086 8 12C8 14.2091 9.79086 16 12 16ZM12 18C15.3137 18 18 15.3137 18 12C18 8.68629 15.3137 6 12 6C8.68629 6 6 8.68629 6 12C6 15.3137 8.68629 18 12 18Z" fill="currentColor" />
            <path fill-rule="evenodd" clip-rule="evenodd" d="M11 0H13V4.06189C12.6724 4.02104 12.3387 4 12 4C11.6613 4 11.3276 4.02104 11 4.06189V0ZM7.0943 5.68018L4.22173 2.80761L2.80752 4.22183L5.6801 7.09441C6.09071 6.56618 6.56608 6.0908 7.0943 5.68018ZM4.06189 11H0V13H4.06189C4.02104 12.6724 4 12.3387 4 12C4 11.6613 4.02104 11.3276 4.06189 11ZM5.6801 16.9056L2.80751 19.7782L4.22173 21.1924L7.0943 18.3198C6.56608 17.9092 6.09071 17.4338 5.6801 16.9056ZM11 19.9381V24H13V19.9381C12.6724 19.979 12.3387 20 12 20C11.6613 20 11.3276 19.979 11 19.9381ZM16.9056 18.3199L19.7781 21.1924L21.1923 19.7782L18.3198 16.9057C17.9092 17.4339 17.4338 17.9093 16.9056 18.3199ZM19.9381 13H24V11H19.9381C19.979 11.3276 20 11.6613 20 12C20 12.3387 19.979 12.6724 19.9381 13ZM18.3198 7.0943L21.1923 4.22183L19.7781 2.80762L16.9056 5.6801C17.4338 6.09071 17.9092 6.56608 18.3198 7.0943Z" fill="currentColor" />
          </svg></a>
        <a class="dropdown-item text-light home-icon mt-2" href="languages.php">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M6 22.8787C4.34315 22.8787 3 21.5355 3 19.8787V9.87866C3 9.84477 3.00169 9.81126 3.00498 9.77823H3C3 9.20227 3.2288 8.64989 3.63607 8.24262L9.87868 2.00002C11.0502 0.828445 12.9497 0.828445 14.1213 2.00002L20.3639 8.24264C20.7712 8.6499 21 9.20227 21 9.77823H20.995C20.9983 9.81126 21 9.84477 21 9.87866V19.8787C21 21.5355 19.6569 22.8787 18 22.8787H6ZM12.7071 3.41423L19 9.70713V19.8787C19 20.4309 18.5523 20.8787 18 20.8787H15V15.8787C15 14.2218 13.6569 12.8787 12 12.8787C10.3431 12.8787 9 14.2218 9 15.8787V20.8787H6C5.44772 20.8787 5 20.4309 5 19.8787V9.7072L11.2929 3.41423C11.6834 3.02371 12.3166 3.02371 12.7071 3.41423Z" fill="currentColor" />
          </svg>
        </a>
      </div>
    </li>
  </ul>

<?php
}
?>

<?php
function user_mobile($user_id, $username, $db)
{
?>
  <div class="mobile-nav">
    <ul class="list-group list-group-horizontal">
      <li class="changeMode list-group-item">
        <svg class="moon-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" clip-rule="evenodd" d="M12.2256 2.00253C9.59172 1.94346 6.93894 2.9189 4.92893 4.92891C1.02369 8.83415 1.02369 15.1658 4.92893 19.071C8.83418 22.9763 15.1658 22.9763 19.0711 19.071C21.0811 17.061 22.0565 14.4082 21.9975 11.7743C21.9796 10.9772 21.8669 10.1818 21.6595 9.40643C21.0933 9.9488 20.5078 10.4276 19.9163 10.8425C18.5649 11.7906 17.1826 12.4053 15.9301 12.6837C14.0241 13.1072 12.7156 12.7156 12 12C11.2844 11.2844 10.8928 9.97588 11.3163 8.0699C11.5947 6.81738 12.2094 5.43511 13.1575 4.08368C13.5724 3.49221 14.0512 2.90664 14.5935 2.34046C13.8182 2.13305 13.0228 2.02041 12.2256 2.00253ZM17.6569 17.6568C18.9081 16.4056 19.6582 14.8431 19.9072 13.2186C16.3611 15.2643 12.638 15.4664 10.5858 13.4142C8.53361 11.362 8.73568 7.63895 10.7814 4.09281C9.1569 4.34184 7.59434 5.09193 6.34315 6.34313C3.21895 9.46732 3.21895 14.5326 6.34315 17.6568C9.46734 20.781 14.5327 20.781 17.6569 17.6568Z" fill="currentColor" />
        </svg>
        <svg class="sun-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" clip-rule="evenodd" d="M12 16C14.2091 16 16 14.2091 16 12C16 9.79086 14.2091 8 12 8C9.79086 8 8 9.79086 8 12C8 14.2091 9.79086 16 12 16ZM12 18C15.3137 18 18 15.3137 18 12C18 8.68629 15.3137 6 12 6C8.68629 6 6 8.68629 6 12C6 15.3137 8.68629 18 12 18Z" fill="currentColor" />
          <path fill-rule="evenodd" clip-rule="evenodd" d="M11 0H13V4.06189C12.6724 4.02104 12.3387 4 12 4C11.6613 4 11.3276 4.02104 11 4.06189V0ZM7.0943 5.68018L4.22173 2.80761L2.80752 4.22183L5.6801 7.09441C6.09071 6.56618 6.56608 6.0908 7.0943 5.68018ZM4.06189 11H0V13H4.06189C4.02104 12.6724 4 12.3387 4 12C4 11.6613 4.02104 11.3276 4.06189 11ZM5.6801 16.9056L2.80751 19.7782L4.22173 21.1924L7.0943 18.3198C6.56608 17.9092 6.09071 17.4338 5.6801 16.9056ZM11 19.9381V24H13V19.9381C12.6724 19.979 12.3387 20 12 20C11.6613 20 11.3276 19.979 11 19.9381ZM16.9056 18.3199L19.7781 21.1924L21.1923 19.7782L18.3198 16.9057C17.9092 17.4339 17.4338 17.9093 16.9056 18.3199ZM19.9381 13H24V11H19.9381C19.979 11.3276 20 11.6613 20 12C20 12.3387 19.979 12.6724 19.9381 13ZM18.3198 7.0943L21.1923 4.22183L19.7781 2.80762L16.9056 5.6801C17.4338 6.09071 17.9092 6.56608 18.3198 7.0943Z" fill="currentColor" />
        </svg>
      </li>
      <li class="profileButton list-group-item"><a href="user.php?username=<?php echo $username; ?>"><svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M16 9C16 11.2091 14.2091 13 12 13C9.79086 13 8 11.2091 8 9C8 6.79086 9.79086 5 12 5C14.2091 5 16 6.79086 16 9ZM14 9C14 10.1046 13.1046 11 12 11C10.8954 11 10 10.1046 10 9C10 7.89543 10.8954 7 12 7C13.1046 7 14 7.89543 14 9Z" fill="currentColor" />
            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 1C5.92487 1 1 5.92487 1 12C1 18.0751 5.92487 23 12 23C18.0751 23 23 18.0751 23 12C23 5.92487 18.0751 1 12 1ZM3 12C3 14.0902 3.71255 16.014 4.90798 17.5417C6.55245 15.3889 9.14627 14 12.0645 14C14.9448 14 17.5092 15.3531 19.1565 17.4583C20.313 15.9443 21 14.0524 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12ZM12 21C9.84977 21 7.87565 20.2459 6.32767 18.9878C7.59352 17.1812 9.69106 16 12.0645 16C14.4084 16 16.4833 17.1521 17.7538 18.9209C16.1939 20.2191 14.1881 21 12 21Z" fill="currentColor" />
          </svg></a></li>
      <li class="logoutButton list-group-item">
        <a href="logout.php">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M13 4.00894C13.0002 3.45665 12.5527 3.00876 12.0004 3.00854C11.4481 3.00833 11.0002 3.45587 11 4.00815L10.9968 12.0116C10.9966 12.5639 11.4442 13.0118 11.9965 13.012C12.5487 13.0122 12.9966 12.5647 12.9968 12.0124L13 4.00894Z" fill="currentColor" />
            <path d="M4 12.9917C4 10.7826 4.89541 8.7826 6.34308 7.33488L7.7573 8.7491C6.67155 9.83488 6 11.3349 6 12.9917C6 16.3054 8.68629 18.9917 12 18.9917C15.3137 18.9917 18 16.3054 18 12.9917C18 11.3348 17.3284 9.83482 16.2426 8.74903L17.6568 7.33481C19.1046 8.78253 20 10.7825 20 12.9917C20 17.41 16.4183 20.9917 12 20.9917C7.58172 20.9917 4 17.41 4 12.9917Z" fill="currentColor" />
          </svg>
        </a>
      </li>
      <li class="list-group-item"><a href="languages.php">
          <svg class="homeButton" width="35" height="35" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M6 22.8787C4.34315 22.8787 3 21.5355 3 19.8787V9.87866C3 9.84477 3.00169 9.81126 3.00498 9.77823H3C3 9.20227 3.2288 8.64989 3.63607 8.24262L9.87868 2.00002C11.0502 0.828445 12.9497 0.828445 14.1213 2.00002L20.3639 8.24264C20.7712 8.6499 21 9.20227 21 9.77823H20.995C20.9983 9.81126 21 9.84477 21 9.87866V19.8787C21 21.5355 19.6569 22.8787 18 22.8787H6ZM12.7071 3.41423L19 9.70713V19.8787C19 20.4309 18.5523 20.8787 18 20.8787H15V15.8787C15 14.2218 13.6569 12.8787 12 12.8787C10.3431 12.8787 9 14.2218 9 15.8787V20.8787H6C5.44772 20.8787 5 20.4309 5 19.8787V9.7072L11.2929 3.41423C11.6834 3.02371 12.3166 3.02371 12.7071 3.41423Z" fill="currentColor" />
          </svg></a>
      </li>
    </ul>
  </div>
<?php
}
?>

<?php
function quiz_mobile_nav($index)
{
?>
  <div class="mobile-nav" id="quiz-mobile">
    <ul class="list-group list-group-horizontal">
      <li class="list-group-item">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-arrow-return-left backButton" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5z" />
        </svg>
      </li>
      <li class="list-group-item"><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" id="answr-submit-<?php echo $index ?>" class="bi bi-check-square answr-submit" viewBox="0 0 16 16">
          <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
          <path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.235.235 0 0 1 .02-.022z" />
        </svg></li>
      <li class="list-group-item">
        <svg style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-arrow-right-square nextButton" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm4.5 5.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z" />
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" id="skipButton-<?php echo $index ?>" width="16" height="16" fill="currentColor" class="bi bi-skip-forward skipButton" viewBox="0 0 16 16">
          <path d="M15.5 3.5a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0V8.752l-6.267 3.636c-.52.302-1.233-.043-1.233-.696v-2.94l-6.267 3.636C.713 12.69 0 12.345 0 11.692V4.308c0-.653.713-.998 1.233-.696L7.5 7.248v-2.94c0-.653.713-.998 1.233-.696L15 7.248V4a.5.5 0 0 1 .5-.5zM1 4.633v6.734L6.804 8 1 4.633zm7.5 0v6.734L14.304 8 8.5 4.633z" />
        </svg>
      </li>
    </ul>
  </div>
<?php
}
?>

<?php
function index_menu($user_id)
{  ?>
  <header class="mb-auto">
    <div>
      <h3 class="float-md-start mb-0"><a href="index.php">CodeStation</a></h3>
      <nav class="nav nav-masthead justify-content-center float-md-end">
        <?php
        if ($user_id == 0) {
        ?>
          <a class="nav-link" href="register.php">Registracija</a>
          <a class="nav-link" href="login.php">Prijava</a>
        <?php
        } else {
        ?>
          <a class="nav-link" href="language_details.php?lid=1">Python</a>
          <a class="nav-link" href="language_details.php?lid=22">Ruby</a>
          <a class="nav-link" href="language_details.php?lid=23">PHP</a>
          <a class="nav-link" href="languages.php">Svi jezici</a>
        <?php
        }
        ?>
      </nav>
    </div>
  </header>
<?php
}
?>

<?php
function floating_background()
{  ?>
  <div class="floating">
    <svg height="60" width="200">
      <text x="0" y="25" id="keywords1"></text>
    </svg>
    <svg height="60" width="200">
      <text x="0" y="25">$ ruby</text>
    </svg>
    <svg height="60" width="200">
      <text x="0" y="25" id="languages1"></text>
    </svg>
    <svg height="60" width="200">
      <text x="0" y="25" id="keywords2"></text>
    </svg>
    <svg height="60" width="200">
      <text x="0" y="25">class Learn</text>
    </svg>
    <svg height="60" width="200">
      <text x="0" y="25">$a++;</text>
    </svg>
    <svg height="60" width="200">
      <text x="0" y="25">INSERT INTO table</text>
    </svg>
    <svg height="60" width="200">
      <text x="0" y="25" id="languages2">alert('fun');</text>
    </svg>
    <svg height="60" width="200">
      <text x="0" y="25">def funct</text>
    </svg>
    <svg height="60" width="200">
      <text x="0" y="25" id="keywords3"></text>
    </svg>
  </div>
<?php
}
?>

<?php
function header_index($title)
{
?>
  <!doctype html>
  <html lang="en" class="h-100">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Web application for code learning">
    <meta name="author" content="MK">
    <title><?php echo $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/cover.css" rel="stylesheet">
  </head>
<?php
}
?>

<?php
function sidemenu($menu_items, $menu_links, $category = "Index", $user_id = null, $user_name = null, $avi = null)
{
?>
  <div id="sidebar-wrapper">
    <div class="sidebar-heading"><a class="text-white" style="text-decoration:none;" href="#">MK</a></div>
    <div class="list-group list-group-flush">
      <?php
      $count = 0;
      foreach ($menu_items['main'] as $item) {
        if ($item == $category) {
      ?>

          <a href="<?php echo $menu_links['main'][$count] ?>" class="list-group-item list-group-item-action text-y" style="background-color:#323e4a;"><?php echo $item ?></a>
          <?php
          if (isset($menu_items['sub'])) {
            $count++;
            $subcount = 0;
            foreach ($menu_items['sub'] as $subitem) {
          ?>
              <a href="<?php echo $menu_links['sub'][$subcount] ?>" class="list-group-item list-group-item-action text-y" style="background-color:#323e4a;">
                <?php echo $subitem ?></a>
          <?php
              $subcount++;
            }
          }
        } else {
          ?>
          <a href="<?php echo $menu_links['main'][$count] ?>" class="list-group-item list-group-item-action text-y" style="background-color:#1d2124;">
            <?php echo $item ?></a>
        <?php
          $count++;
        }
      }

      if ((!is_null($user_id)) && (!is_null($user_name))) {
        $user_name = htmlspecialchars(strip_tags($user_name));
        ?>
        <div class="user-side fixed-bottom">
          <img src="<?php if (!is_null($avi)) { ?>img/user/<?php echo $avi;
                                                          } else {
                                                            echo 'img/default.jpg';
                                                          } ?>" width="80px" height="auto">
          <div class="btn-group dropup">
            <span class="bg-dark list-group-item list-group-item-action text-y dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color:#1d2124 !important;"><?php echo $user_name ?></span>
            <div class="dropdown-menu text-center bg-dark">
              <input type="hidden" value="<?php echo $user_id ?>" id="user-img-del-id">
              <input type="hidden" value="<?php echo generateToken(); ?>" id="user-img-del-ct">
              <a class="dropdown-item text-y" href="../app/user.php?username=<?php echo $user_name; ?>">Vaš profil</a>
              <a class="dropdown-item text-y usernameButton" data-toggle="modal" data-target="#usernameModal">Novo korisničko ime</a>
              <a class="dropdown-item text-y passwordButton" data-toggle="modal" data-target="#passwordModal">Promijena lozinke</a>
              <a class="dropdown-item text-y userimgButton" data-toggle="modal" data-target="#userimgModal" <?php if (!is_null($avi)) {
                                                                                                              echo "disabled";
                                                                                                            } ?>>Uredi sliku profila</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item text-light" href="logout.php">Odjava</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item text-warning" data-toggle="modal" data-target="#deactivateModal">Deaktivacija</a>
            </div>
          </div>
        </div>
      <?php
      }
      ?>
    </div>
  </div>
  <!-- user image edit modal -->
  <div class="modal fade" id="userimgModal" tabindex="-1" role="dialog" aria-labelledby="userimgModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-m" id="userimgModalLabel">Uredi sliku profila</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="message" class='text-success'>
            <p class="val-msg" id='val-msg'></p>
          </div>
          <img width="250px" height="auto" id="userimgPreview" src="<?php if (is_null($avi)) {
                                                                      echo 'img/default.jpg';
                                                                    } else {
                                                                      echo 'img/user/' . $avi;
                                                                    } ?>">
          <form method="post" action="" enctype="multipart/form-data" id="userImg">
            <input type="hidden" id="user-image-submitted">
            <input type="hidden" value="<?php echo $user_id ?>" id="user-img-edit-id">
            <input type="hidden" value="<?php echo generateToken(); ?>" id="user-img-edit-ct">
            <div class="form-group">
              <label class="text-m" for="user-img">Slika profila</label>
              <input type="file" class="form-control-file" id="user-img">
              <small id="userImgHelp" class="form-text text-z">Datoteka ne smije biti veća od 2MB. Dozvoljeni formati datoteke su png, jpg i jpeg.</small>
            </div>
            <input type="button" id="userimgSubmit" class="btn btn-x" value="Uredi sliku" <?php if (!is_null($avi)) {
                                                                                            echo "disabled";
                                                                                          } ?>>
          </form>
        </div>
        <div class="modal-footer">
          <input type="button" class="btn btn-z" id="userImgDelSubmit" value="Obriši sliku" <?php if (is_null($avi)) {
                                                                                              echo "disabled";
                                                                                            } ?>>
        </div>
      </div>
    </div>
  </div>

  <!-- edit username modal -->
  <div class="modal fade" id="usernameModal" tabindex="-1" role="dialog" aria-labelledby="usernameModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-m" id="usernameModalLabel">Promijeni korisničko ime</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
              <label class="text-m" for="usr-username">Korisničko ime</label>
              <input type="text" class="form-control" id="usr-username" aria-describedby="usernameHelp" placeholder="Upišite korisničko ime" value="<?php echo $user_name ?>">
              <small id="usernameHelp" class="form-text text-z">Korisničko ime ne smije sadržavati manje od 3 ili više od 15 znakova. Dozvoljeni su samo znakovi engleske abecede, brojevi te znak "_". Korisničko ime mora sadržavati barem 1 slovo.</small>
            </div>
        </div>
        <div class="modal-footer">
          <input type="button" id="usernameSubmit" class="btn btn-x" value="U redu">
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
          <h5 class="modal-title text-m" id="passwordModalLabel">Promijeni lozinku</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
              <label class="text-m" for="usr-password-old">Stara lozinka</label>
              <input type="password" class="form-control" id="usr-password-old" placeholder="Upišite staru lozinku">
            </div>
            <div class="form-group">
              <label class="text-m" for="usr-password">Nova lozinka</label>
              <input type="password" class="form-control" id="usr-password" aria-describedby="passwordHelp" placeholder="Upišite novu lozinku">
              <small id="passwordHelp" class="form-text text-z">Lozinka mora sadržavati barem jednu znamenku, jedno veliko slovo te jedno malo slovo. Minimalan broj znakova je 6.</small>
            </div>
            <div class="form-group">
              <label class="text-m" for="usr-password2">Ponovite novu lozinku</label>
              <input type="password" class="form-control" id="usr-password2" placeholder="Ponovite novu lozinku">
            </div>
        </div>
        <div class="modal-footer">
          <input type="button" id="passwordSubmit" class="btn btn-x" value="U redu">
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
          <h5 class="modal-title text-m" id="deactivateModalLabel">Deaktiviraj profil</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h5 class="text-m d-warning">Jeste li sigurni da želite deaktivirati svoj korisnički profil?</h5>
          <div id="deactivate-message" class='text-success'>
            <p class="val-msg" id='val-msg-deactivate'></p>
          </div>
          <form method="post" action="" id="deactivate" enctype="multipart/form-data">
            <input type="hidden" id="user-deactivate-submitted">
            <input type="hidden" value="<?php echo $user_id ?>" id="user-deactivate-id">
            <input type="hidden" value="<?php echo generateToken(); ?>" id="user-deactivate-ct">
        </div>
        <div class="modal-footer">
          <input type="button" id="deactivateSubmit" class="btn btn-x" value="Deaktiviraj">
          </form>
        </div>
      </div>
    </div>
  </div>
<?php
}
?>

<?php
function cms_user_mobile($menu_items, $menu_links, $category = "Index", $user_id = null, $user_name = null, $avi = null)
{
?>
  <div class="mobile-nav">
    <ul class="list-group list-group-horizontal">
      <?php
      $count = 0;
      foreach ($menu_items['main'] as $item) {
        if ($item == $category) {
      ?>
          <li class="list-group-item"><a href="<?php echo $menu_links['main'][$count] ?>">
              <?php echo $item ?>
            </a>
          </li>
          <?php
          if (isset($menu_items['sub'])) {
            $count++;
            $subcount = 0;
            foreach ($menu_items['sub'] as $subitem) {
          ?>
              <li class="list-group-item"><a href="<?php echo $menu_links['sub'][$subcount] ?>">
                  <?php echo $subitem ?></a></li>
          <?php
              $subcount++;
            }
          }
        } else {
          ?>
          <li class="list-group-item"><a href="<?php echo $menu_links['main'][$count] ?>">
              <?php echo $item ?></a></li>
        <?php
          $count++;
        }
      }

      if ((!is_null($user_id)) && (!is_null($user_name))) {
        $user_name = htmlspecialchars(strip_tags($user_name));
        ?>
          <span class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M16 9C16 11.2091 14.2091 13 12 13C9.79086 13 8 11.2091 8 9C8 6.79086 9.79086 5 12 5C14.2091 5 16 6.79086 16 9ZM14 9C14 10.1046 13.1046 11 12 11C10.8954 11 10 10.1046 10 9C10 7.89543 10.8954 7 12 7C13.1046 7 14 7.89543 14 9Z" fill="currentColor" />
              <path fill-rule="evenodd" clip-rule="evenodd" d="M12 1C5.92487 1 1 5.92487 1 12C1 18.0751 5.92487 23 12 23C18.0751 23 23 18.0751 23 12C23 5.92487 18.0751 1 12 1ZM3 12C3 14.0902 3.71255 16.014 4.90798 17.5417C6.55245 15.3889 9.14627 14 12.0645 14C14.9448 14 17.5092 15.3531 19.1565 17.4583C20.313 15.9443 21 14.0524 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12ZM12 21C9.84977 21 7.87565 20.2459 6.32767 18.9878C7.59352 17.1812 9.69106 16 12.0645 16C14.4084 16 16.4833 17.1521 17.7538 18.9209C16.1939 20.2191 14.1881 21 12 21Z" fill="currentColor" />
            </svg>
            </span>
            <div class="dropdown-menu profileMenu text-center bg-dark">
              <a class="dropdown-item text-y" href="../app/user.php?username=<?php echo $user_name; ?>">Vaš profil</a>
              <a class="dropdown-item text-y usernameButton" data-toggle="modal" data-target="#usernameModal">Novo korisničko ime</a>
              <a class="dropdown-item text-y passwordButton" data-toggle="modal" data-target="#passwordModal">Promijena lozinke</a>
              <a class="dropdown-item text-y userimgButton" data-toggle="modal" data-target="#userimgModal" <?php if (!is_null($avi)) {
                                                                                                              echo "disabled";
                                                                                                            } ?>>Uredi sliku profila</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item text-warning" data-toggle="modal" data-target="#deactivateModal">Deaktivacija</a>
            </div>
      <?php
      }
      ?>
      <li class="list-group-item">
        <a href="logout.php">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M13 4.00894C13.0002 3.45665 12.5527 3.00876 12.0004 3.00854C11.4481 3.00833 11.0002 3.45587 11 4.00815L10.9968 12.0116C10.9966 12.5639 11.4442 13.0118 11.9965 13.012C12.5487 13.0122 12.9966 12.5647 12.9968 12.0124L13 4.00894Z" fill="currentColor" />
            <path d="M4 12.9917C4 10.7826 4.89541 8.7826 6.34308 7.33488L7.7573 8.7491C6.67155 9.83488 6 11.3349 6 12.9917C6 16.3054 8.68629 18.9917 12 18.9917C15.3137 18.9917 18 16.3054 18 12.9917C18 11.3348 17.3284 9.83482 16.2426 8.74903L17.6568 7.33481C19.1046 8.78253 20 10.7825 20 12.9917C20 17.41 16.4183 20.9917 12 20.9917C7.58172 20.9917 4 17.41 4 12.9917Z" fill="currentColor" />
          </svg>
        </a>
      </li>
    </ul>
  </div>
<?php
}
?>

<?php
function validate($form_fields, $form_names, $db, $id = null, $type)
{
  $errors = array();
  $count = 0;

  foreach ($form_fields as $field) {

    if (!isset($_POST["$field"])) {
      array_push($errors, "Polje '" . $form_names[$count] . "' je obavezno!");
    } else if (empty($_POST["$field"])) {
      array_push($errors, "Polje '" . $form_names[$count] . "' je obavezno!");
    } else if (ctype_space($_POST["$field"])) {
      array_push($errors, "Polje '" . $form_names[$count] . "' je obavezno!");
    } else {

      switch ($type) {
        case "Language":
          $lang = new Language($db);
          if ($field == "lang-name") {
            if (strlen($_POST["$field"]) > 25) {
              array_push($errors, "Polje '" . $form_names[$count] . "' ne smije biti duže od 25 znakova!");
            }
            $lang->set_name(trim($_POST["$field"]));
            if (!empty($id)) {
              $lang->set_id(($id));
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
          if ($field == "lang-c-mode") {
            if (strlen($_POST["$field"]) > 15) {
              array_push($errors, "Polje '" . $form_names[$count] . "' ne smije biti duže od 15 znakova!");
            }
          }
          if ($field == "lang-version") {
            if (!is_numeric($_POST["$field"])) {
              array_push($errors, "Polje '" . $form_names[$count] . "' ne smije sadržavati tekstualne znakove!");
            }
            if (strlen($_POST["$field"]) > 1) {
              array_push($errors, "Polje '" . $form_names[$count] . "' ne smije biti duže od 1 znaka!");
            }
          }
          if ($field == "lang-e-mode") {
            if (strlen($_POST["$field"]) > 15) {
              array_push($errors, "Polje '" . $form_names[$count] . "' ne smije biti duže od 15 znakova!");
            }
          }
          break;
        case "User":
          $user = new User($db);
          if ($field == "usr-username") {

            if (strlen($_POST["$field"]) >= 15) {
              array_push($errors, "Polje '" . $form_names[$count] . "' ne smije biti duže od 15 znakova!");
            } else if (strlen($_POST["$field"]) < 3) {
              array_push($errors, "Polje '" . $form_names[$count] . "' ne smije biti kraće od 3 znaka!");
            }

            $user->set_username(trim($_POST["$field"]));
            if (!empty($id)) {
              $user->set_id(($id));
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

            $foundlower = 0;
            $foundupper = 0;
            $founddigit = 0;

            $exp = str_split($_POST["$field"]);

            foreach ($exp as $letter) {
              if (ctype_lower($letter)) {
                $foundlower = 1;
              }
              if (ctype_upper($letter)) {
                $foundupper = 1;
              }
              if (ctype_digit($letter)) {
                $founddigit = 1;
              }
            }

            if ($foundlower == 0) {
              array_push($errors, "Lozinka mora sadržavati barem jedno malo slovo!");
            }
            if ($foundupper == 0) {
              array_push($errors, "Lozinka mora sadržavati barem jedno veliko slovo!");
            }

            if ($founddigit == 0) {
              array_push($errors, "Lozinka mora sadržavati barem jednu znamenku!");
            }
          }
          break;
        case "Lesson":
          $less = new Lesson($db);
          if ($field == "less-name") {
            if (strlen($_POST["$field"]) > 100) {
              array_push($errors, "Polje '" . $form_names[$count] . "' ne smije biti duže od 100 znakova!");
            }
            $less->set_name(trim($_POST["$field"]));
            if (!empty($id[0])) {
              $less->set_id(($id[0]));
            }
            if (!empty($id[1])) {
              $less->set_language_id(($id[1]));
            }
            if (!$less->isUniqueName()) {
              array_push($errors, "Već postoji lekcija sa istim nazivom!");
            }
          }
          if ($field == "less-desc") {
            if (strlen($_POST["$field"]) < 100) {
              array_push($errors, "Polje '" . $form_names[$count] . "' mora sadržavati minimalno 100 znakova!");
            }
          }
          break;
        case "Question":
          $quest = new Question($db);
          if ($field == "quest-name") {
            if (strlen($_POST["$field"]) < 10) {
              array_push($errors, "Polje '" . $form_names[$count] . "' ne smije biti kraće od 10 znakova!");
            }
          }
          if ($field == "quest-type") {
            if ($_POST["$field"] == 0) {
              array_push($errors, "Polje '" . $form_names[$count] . "' je obavezno polje!");
            }
          }
          if ($field == "quest-less") {
            if ($_POST["$field"] == 0) {
              array_push($errors, "Polje '" . $form_names[$count] . "' je obavezno polje!");
            }
          }
          break;
        case "Answer":
          if ($field == "answer-code") {
            if (strlen($_POST["$field"]) < 5) {
              array_push($errors, "Polje '" . $form_names[$count] . "' ne smije biti kraće od 3 znakova!");
            }
          }
          break;
      }
    }

    $count++;
  }

  if (sizeof($errors) > 0) {
    $c = 0;
    foreach ($errors as &$value) {
      if ($c < sizeof($errors)) {
        $value .= "<br>";
        $c++;
      }
    }
    unset($value);
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
    } else if ($width > "1024" || $height > "1024") {
      array_push($response, "Dimenzije datoteke moraju biti unutar 1024x1024.");
    }
  }

  if (sizeof($response) > 0) {
    $c = 0;
    foreach ($response as &$value) {
      if ($c < sizeof($response)) {
        $value .= "<br>";
        $c++;
      }
    }
    unset($value);
  }
  return $response;
}
?>

<?php
function login(User $user)
{
  $_SESSION['fresh-login'] = 1;
  $_SESSION['user_id'] = $user->get_id();
  $_SESSION['user_role'] = $user->get_role_code();
  echo ("<script>location.href = 'languages.php';</script>");
?>
<?php
}
?>

<?php
function show_modal($modal_names)
{
  if (isset($_SESSION['show_modal'])) {

    $modal = $_SESSION['show_modal'];

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
        $_SESSION['show_modal'] = "";
      }
    }
  }
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

<?php
function isAuthorized()
{
  if (isset($_SESSION['user_id'])) {
    if ((check_user_status() == 1)) {
      return 1;
    } else if ((check_user_status() == 2)) {
      return 2;
    } else if (check_user_status() == 3) {
      return 3;
    }
  } else {
    return 0;
  }
}
?>

<?php
function pagination($current_page, $number_of_results, $pagehref, $results_per_page = 10)
{
  if (!is_null($number_of_results)) {
    $pages_available = ceil($number_of_results / $results_per_page);

    if ($pages_available > 1) { ?>
      <nav>
        <ul class="pagination justify-content-center">
          <?php
          if ($current_page > 1) { ?>
            <li class="page-item">
              <a class="page-link bg-dark text-light" href="<?php echo $pagehref; ?>page=<?php echo $current_page - 1; ?>" tabindex="-1">Natrag</a>
            </li>
          <?php
          }
          for ($page = 1; $page <= $pages_available; $page++) { ?>
            <li class="page-item <?php if ($page == $current_page) {
                                    echo 'active';
                                  } ?>"><a class="page-link bg-dark text-light" href="<?php echo $pagehref; ?>page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
          <?php
          }
          if ($pages_available > $current_page) {
          ?>
            <li class="page-item">
              <a class="page-link bg-dark text-light" href="<?php echo $pagehref; ?>page=<?php echo $current_page + 1; ?>">Naprijed</a>
            </li>
          <?php
          }
          ?>
        </ul>
      </nav>
<?php
    }
  }
}
?>

<?php
function remove_code_whitespace($code)
{

  $symbols = array('=', '+', '-', '/', '*');

  foreach ($symbols as $symbol) {
    $code = str_replace(' ' . $symbol, $symbol, $code);
    $code = str_replace($symbol . ' ', $symbol, $code);
    $code = str_replace(' ' . $symbol . ' ', $symbol, $code);
  }

  $code = str_replace("'", '"', $code);
  return $code;
}
?>

<?php
function store_incorrect(&$found_answer)
{
  array_push($found_answer, "0");
}
?>

<?php
function store_correct(&$found_answer)
{
  array_push($found_answer, "1");
}
?>

<?php
function store_partially_correct(&$found_answer)
{
  array_push($found_answer, "0.5");
}
?>

<?php
function create_question_progress($is_in_table, $question_progress)
{
  if (!$is_in_table) {
    if (!$question_progress->createQuestionProgress()) {
      encode_error();
    }
  }
}
?>

<?php
function delete_question_progress($is_in_table, $question_progress)
{
  if ($is_in_table) {
    if (!$question_progress->deleteQuestionProgress()) {
      encode_error();
    }
  }
}
?>


<?php
function encode_error($msg = null)
{
  if (!is_null($msg)) {
    echo json_encode(array('status' => 0, 'message' => $msg));
  } else {
    echo json_encode(array('status' => 0, 'message' => 'Dogodila se pogreška!'));
  }
  die();
}
?>

<?php
function header_redirect($location = null)
{
  if (is_null($location)) {
    header("Location: error.php");
  } else {
    header("Location: " . $location);
  }
  die();
}
?>

<?php
function unset_session()
{
  unset($_SESSION['practice']);
  unset($_SESSION['lesson']);
  unset($_SESSION['lesson_ids']);
  unset($_SESSION['lesson_id']);
  unset($_SESSION['question_ids']);
  unset($_SESSION['index']);
  unset($_SESSION['lifes']);
  unset($_SESSION['correct']);
  unset($_SESSION['incorrect']);
  unset($_SESSION['noPassing']);
}
?>

<?php
function admin_or_user($field)
{
  $allowed = 0;
  $auth = isAuthorized();
  if ($auth == 1) {
    $allowed = 1;
  }
  if (($_SESSION['user_id'] == $_POST["$field"])) {
    $allowed = 1;
  }

  return $allowed;
}
?>