<?php
include_once("../functions.php");
    session_start();
    $_SESSION=array();
    if(isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-36000,'/');
    }
    session_destroy();
    header_redirect("index.php");
?>