<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Web application for code learning">
  <meta name="author" content="MK">
  <title><?php echo $title ?></title>
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
</head>

<?php
    include_once("functions.php");
?>

<div class="d-flex" id="wrapper">

<?php
      $menu_items = array('Jezici','Lekcije','Pitanja','Korisnici','Ovlasti','Odjava');
      $menu_links = array('languages.php','lessons.php','questions.php','users.php','roles.php','logout.php');
      sidemenu($menu_items,$menu_links);
?>
    
    <div id="page-content-wrapper">
<body>