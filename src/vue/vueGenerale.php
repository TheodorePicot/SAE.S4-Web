<?php

use App\PlusCourtChemin\Lib\ConnexionUtilisateur;
use TheFeed\Lib\Conteneur;

$loginHTML = htmlspecialchars(ConnexionUtilisateur::getLoginUtilisateurConnecte());
$loginURL = rawurlencode(ConnexionUtilisateur::getLoginUtilisateurConnecte());

$generateurUrl = Conteneur::recupererService("generateurUrl");
$assistantUrl = Conteneur::recupererService("assistantUrl");?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $pagetitle ?></title>


    <!-- css -->
    <link rel="stylesheet" href="<?=$assistantUrl->getAbsoluteUrl("assets/css/bootstrap.min.css");?>">
    <link rel="stylesheet" href="<?=$assistantUrl->getAbsoluteUrl("assets/css/styles.css");?>">


    <!-- Optional JavaScript -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Raleway:wght@100;200&display=swap" rel="stylesheet">
</head>
<body>

<header>


    <nav class="navbar navbar-expand-lg navbar-dark  mx-4 fs-5">
        <a></a>
        <button class="navbar-toggler collapsed d-flex d-lg-none flex-column justify-content-around" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="toggler-icon top-bar"></span>
            <span class="toggler-icon middle-bar"></span>
            <span class="toggler-icon bottom-bar"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?=$generateurUrl->generate("afficherListeUtilisateur");?>">Utilisateurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?=$generateurUrl->generate("afficherListeCommune");?>">Communes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?=$generateurUrl->generate("plusCourtChemin");?>">Chemin</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <?php if (!ConnexionUtilisateur::estConnecte()) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=$generateurUrl->generate("afficherFormulaireConnexion");?>">Se connecter</a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="<?=$generateurUrl->generate("afficherDetailUtilisateur", ["login" => $loginURL])?>">
                            <img alt="user" src="<?=$assistantUrl->getAbsoluteUrl("../ressources/img/user.png");?>">
                            <?php $loginHTML ?>
                        </a>
                    </li>


                <?php endif ?>
            </ul>

            <?php
            foreach (["success", "info", "warning", "danger"] as $type) {
                foreach ($messagesFlash[$type] as $messageFlash) {
                    echo <<<HTML
                    <div class="alert alert-$type">
                        $messageFlash
                    </div>
                    HTML;
                }
            }
            ?>
        </div>
</header>

<main>
    <?php
    /**
     * @var string $cheminVueBody
     */
    require __DIR__ . "/{$cheminVueBody}";
    ?>
</main>
<footer>
    <p>
        <!--        Copyleft Romain Lebreton-->
    </p>
</footer>
</body>

</html>