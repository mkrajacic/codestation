<?php
$title = "Prolaz!";
include_once("header.php");
session_start();

if (isset($_SESSION['redirect_message'])) {
    $message = $_SESSION['redirect_message'];
} else {
    $message = "Uspješno ste prošli lekciju/vježbu!";
}

if (isset($_SESSION['language_id'])) {
    $lid = $_SESSION['language_id'];
}
?>
<div id="wrapper-list" style="background-color:#020621;">
    <div id="passed-outer">
        <div id="cards" class="details error-details">
            <div class="details-desc">
                <span><?php echo $message; ?></span>
            </div>
            <svg xmlns='http://www.w3.org/2000/svg' class='passedIcon' viewBox='0 0 512 512'>
                <path id="x" fill='none' stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='32' d='M176 464h160M256 464V336M384 224c0-50.64-.08-134.63-.12-160a16 16 0 00-16-16l-223.79.26a16 16 0 00-16 15.95c0 30.58-.13 129.17-.13 159.79 0 64.28 83 112 128 112S384 288.28 384 224z' />
                <path id="y" d='M128 96H48v16c0 55.22 33.55 112 80 112M384 96h80v16c0 55.22-33.55 112-80 112' fill='none' stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='32' />
            </svg>
            <a style="top:5vw" href="lessons.php?lid=<?php echo $lid ?>" class="btn startButton detailsButton"><span>Natrag na lekcije</span></a>
            <a style="top:5vw" href="languages.php" class="btn startButton detailsButton"><span>Jezici</span></a>
        </div>
    </div>
</div>

<?php
unset($_SESSION['language_id']);
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