<?php
include_once('../functions.php');
$db = connect();
session_start();
$title = "Index";
$include_paths = ['../class/user.php', 'header_index.php'];
foreach ($include_paths as $path)
    include_once($path);

if (isset($_SESSION['user_id']))
    $user_id = $_SESSION['user_id'];
else
    $user_id = 0;
?>

<body class="d-flex h-100 text-center text-white">
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
        <?php
        index_menu($user_id);
        ?>

        <main class="px-3">
            <h1>CodeStation</h1>
            <p class="lead">CodeStation je mjesto za učenje kodiranja.</p>
            <p class="lead">Naučite <span class="lead" id="learn"></span></p>
            <p class="lead">
                <?php if (check_user_status() >= 1) : ?>
                    <a href="languages.php" class="btn btn-lg btn-secondary fw-bold" style="background-color: #fffdc0;">Započnite</a>
                <?php else : ?>
                    <a href="register.php" class="btn btn-lg btn-secondary fw-bold border-white bg-white">Registracija</a>
                    <a href="login.php" class="btn btn-lg btn-secondary fw-bold" style="background-color: #fffdc0;">Prijava</a>
                <?php endif; ?>
            </p>
        </main>

        <footer class="mt-auto text-white-50">
            <p>@MK</p>
        </footer>
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
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
<script src="js/typed_text.js"></script>

</html>