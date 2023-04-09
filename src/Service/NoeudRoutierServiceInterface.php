<?php

namespace App\PlusCourtChemin\Service;

interface NoeudRoutierServiceInterface
{

    public function recupererNoeudRoutierPar($id_rte500);
    public function getVoisins(int $noeudRoutierGid);
    public function getTousLesVoisins();
    public function getTousLesVoisinsV2();
    public function getLongitudeLatitude(int $noeudRoutierGid);

}
