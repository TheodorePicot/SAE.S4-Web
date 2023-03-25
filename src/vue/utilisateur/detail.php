<?php
/** @var \App\PlusCourtChemin\Modele\DataObject\Utilisateur $utilisateur */

use App\PlusCourtChemin\Lib\ConnexionUtilisateur;
use TheFeed\Lib\Conteneur;

$login = $utilisateur->getLogin();
$loginHTML = htmlspecialchars($login);
$prenomHTML = htmlspecialchars($utilisateur->getPrenom());
$nomHTML = htmlspecialchars($utilisateur->getNom());
$loginURL = rawurlencode($login);

$generateurUrl = Conteneur::recupererService("generateurUrl");
$assistantUrl = Conteneur::recupererService("assistantUrl");
?>
<div>
<p>
    Utilisateur <?= "$prenomHTML $nomHTML" ?> de login <?= $loginHTML ?>

    <?php if (ConnexionUtilisateur::estUtilisateur($login) || ConnexionUtilisateur::estAdministrateur()) { ?>
    <a href="<?=$generateurUrl->generate("afficherFormulaireMiseAJour", ["login" => $loginURL])?>">(mettre à jour)</a>
    <a href="<?=$generateurUrl->generate("supprimer", ["login" => $loginURL])?>">(supprimer)</a>
    <?php } ?>
</p>
</div>