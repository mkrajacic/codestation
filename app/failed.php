<?php
$title = "Neuspijeh";
include_once("header.php");
session_start();

if (isset($_SESSION['redirect_message'])) {
    $message = $_SESSION['redirect_message'];
} else {
    $message = "Niste prošli lekciju/vježbu!";
}

if (isset($_SESSION['language_id'])) {
    $lid = $_SESSION['language_id'];
}
?>
<div id="failed">
    <div id="wrapper-list" style="background-color:#020621;">
        <div id="error-outer" style="background-color:#020621">
            <div id="cards" class="details error-details">
                <div class="details-desc">
                    <span><?php
                            echo $message;
                            ?></span>
                </div>
                <svg xmlns='http://www.w3.org/2000/svg' class='failedIcon' viewBox='0 0 512 512'>
                    <path id="z" d='M448 256c0-106-86-192-192-192S64 150 64 256s86 192 192 192 192-86 192-192z' fill='none' stroke='currentColor' stroke-miterlimit='10' stroke-width='32' />
                    <path d='M333.2 297.69c18.28-23.39 27.06-47.43 26.79-73.37-.31-31.06-25.22-56.33-55.53-56.33-20.4 0-35 10.64-44.11 20.42a5.93 5.93 0 01-8.7 0c-9.11-9.78-23.71-20.42-44.11-20.42L206 168a4 4 0 00-2.75 6.84l124 123.21a3.92 3.92 0 005.95-.36zM158.84 221a4 4 0 00-6.82 2.72v.64c-.28 27.1 9.31 52.13 29.3 76.5 9.38 11.44 26.4 29.73 65.7 56.41a15.93 15.93 0 0018 0c5.15-3.49 9.9-6.84 14.31-10a4 4 0 00.46-6.07zM336 368a15.92 15.92 0 01-11.31-4.69l-176-176a16 16 0 0122.62-22.62l176 176A16 16 0 01336 368z' />
                </svg>
                <?php if (isset($_SESSION['language_id'])) { ?>
                    <a style="top:5vw;background-color:#020621" href="lessons.php?lid=<?php echo $lid ?>" class="btn startButton detailsButton"><span>Natrag na lekcije</span></a>
                <?php } ?>
                <a style="top:5vw;background-color:#020621" href="languages.php" class="btn startButton detailsButton"><span>Jezici</span></a>
            </div>
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