<?php
$title = "Greška";
include_once("header.php");
session_start();

if (isset($_SESSION['redirect_message'])) {
    $message = $_SESSION['redirect_message'];
} else {
    $message = "Dogodila se pogreška!";
}
?>
<div id="wrapper-list" style="background-color:#001177;">
    <div id="error-outer">
        <div id="cards" class="details error-details">
            <div class="details-desc">
                <span><?php
                        echo $message;
                        ?></span>
            </div>
            <svg xmlns='http://www.w3.org/2000/svg' class='errorIcon' viewBox='0 0 512 512'>
                <path d='M448 256c0-106-86-192-192-192S64 150 64 256s86 192 192 192 192-86 192-192z' fill='none' stroke='currentColor' stroke-miterlimit='10' stroke-width='32' />
                <path id="c" fill='none' stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='32' d='M320 320L192 192M192 320l128-128' />
            </svg>
            <a style="top:5vw" onclick="window.history.go(-1)" class="btn startButton detailsButton"><span>Natrag</span></a>
            <a style="top:5vw" href="index.php" class="btn startButton detailsButton"><span>Početna</span></a>
        </div>
    </div>
</div>

<?php
unset($_SESSION['redirect_message']);
include_once("footer.php");
?>

<script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
<script>
    var string = $('.details-desc span').html();
    var typed_eror = new Typed('.details-desc span', {
        strings: [string, '', string],
        typeSpeed: 60,
        backSpeed: 40,
        backDelay: 1000,
        startDelay: 1000,
        smartBackspace: false,
        loop: true
    });
</script>