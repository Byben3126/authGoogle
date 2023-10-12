<?php
require_once("config.php");
require("functions.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
 
    <p>
        <?php if (googleAhtentificator::isAuth()): ?>
            <?php $userInfo = googleAhtentificator::getInfoUser($_SESSION["access_token"]); ?>
            <?php if ($userInfo !== false): ?>
                
                <p>Hello <?php echo htmlspecialchars($userInfo["email"], ENT_QUOTES, 'UTF-8'); ?></p>
                <a href="./logout.php">Déconnexion</a>
            <?php else: ?>
                <p>Erreur lors de la récupération des informations de l'utilisateur.</p>
            <?php endif; ?>

        <?php else: ?>
            <?php if (isset($_GET["errorLogin"])) {
                echo 'Probleme de l\'authetification</br>';
                echo ($_GET["errorLogin"]);
            } ?>

            <a href="https://accounts.google.com/o/oauth2/v2/auth?scope=email&access_type=online&response_type=code&redirect_uri=<?= urlencode("http://localhost:80/localhost/phpauthgoogle/connect.php") ?>&client_id=<?= GOOGLE_ID_CLIENT ?>">Connecter</a>
        <?php endif; ?>
    </p>
</body>
</html>