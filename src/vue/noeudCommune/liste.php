<div class="container-fluid">

    <div class="row">

        <div class="d-flex justify-content-center">

        <a href="./">Calculer un plus court chemin</a>
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
                        <?php
                        echo " <a href=\"?action=afficherDetail&controleur=noeudCommune&gid={$noeudCommune->getGid()}\">(Détail)</a>" ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

</div>