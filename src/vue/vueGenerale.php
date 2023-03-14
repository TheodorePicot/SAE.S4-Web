<?php

use App\PlusCourtChemin\Lib\ConnexionUtilisateur;

$loginHTML = htmlspecialchars(ConnexionUtilisateur::getLoginUtilisateurConnecte());
$loginURL = rawurlencode(ConnexionUtilisateur::getLoginUtilisateurConnecte()); ?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $pagetitle ?></title>

    <!--    <link rel="stylesheet" href="../ressources/css/navstyle.css">-->


    <!-- css -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>

<!-- Optional JavaScript -->
<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<header>

    <nav class="navbar navbar-expand-lg navbar-light bg-light mx-4 fs-5 ">
        <a href="#" class="navbar-brand">Navbar</a>
        <button class="navbar-toggler collapsed d-flex d-lg-none flex-column justify-content-around" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="toggler-icon top-bar"></span>
            <span class="toggler-icon middle-bar"></span>
            <span class="toggler-icon bottom-bar"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="controleurFrontal.php?action=afficherListe&controleur=utilisateur">Utilisateurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="controleurFrontal.php?action=afficherListe&controleur=noeudCommune">Communes</a>
                </li>
                <?php if (!ConnexionUtilisateur::estConnecte()) : ?>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="controleurFrontal.php?action=afficherFormulaireConnexion&controleur=utilisateur">
                            <img alt="login" src="../ressources/img/enter.png" width="18">
                        </a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="controleurFrontal.php?action=afficherDetail&controleur=utilisateur&login=$loginURL">
                            <img alt="user" src="../ressources/img/user.png" width="18">
                            <?php $loginHTML ?>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="controleurFrontal.php?action=deconnecter&controleur=utilisateur">
                            <img alt="logout" src="../ressources/img/logout.png" width="18">
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
        Copyleft Romain Lebreton
    </p>
</footer>
</body>

</html>