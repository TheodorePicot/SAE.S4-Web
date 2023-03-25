<?php

use TheFeed\Lib\Conteneur;

$generateurUrl = Conteneur::recupererService("generateurUrl");
$assistantUrl = Conteneur::recupererService("assistantUrl");?>
<div class="container-fluid">

    <div class="row">

        <div class="d-flex justify-content-center my-5">
            <h3>Liste des utilisateurs :</h3>
        </div>
        <div class="d-flex justify-content-center">
            <ul>
            /** @var \App\PlusCourtChemin\Modele\DataObject\Utilisateur[] $utilisateurs */
                <?php foreach ($utilisateurs as $utilisateur) :
                        $loginHTML = htmlspecialchars($utilisateur->getLogin());
                        $loginURL = rawurlencode($utilisateur->getLogin());?>
                    <li class="">
                        <?="Utilisateur de login".$loginHTML ?>
                        <a href="<?=$generateurUrl->generate("afficherDetailUtilisateur", ["login" => $loginURL])?>">(+ d'info)</a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <a href="<?=$generateurUrl->generate("afficherFormulaireCreation");?>">Créer un utilisateur</a>
        </div>
    </div>

</div>