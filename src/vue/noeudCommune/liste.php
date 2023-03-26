<?php

use App\PlusCourtChemin\Lib\Conteneur;

$generateurUrl = Conteneur::recupererService("generateurUrl");
$assistantUrl = Conteneur::recupererService("assistantUrl");?>
<div class="container-fluid">

    <div class="row">

        <div class="d-flex justify-content-center">

        <a href="<?=$generateurUrl->generate("plusCourtChemin");?>">Calculer un plus court chemin</a>
        </div>

        <div class="d-flex justify-content-center my-5">
            <h3>Liste des noeuds communes :</h3>
        </div>
        <div class="d-flex justify-content-center">
            <ul>
                <?php foreach ($noeudsCommunes

                               as $noeudCommune) :
                    require __DIR__ . "/detail.php" ?>
                    <li class="">
                        <a href="<?=$generateurUrl->generate("afficherDetailCommune", ["gid" => $noeudCommune->getGid()])?>">(Détail)</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

</div>