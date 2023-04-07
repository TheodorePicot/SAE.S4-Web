<?php

namespace App\PlusCourtChemin\Service;

interface NoeudCommuneServiceInterface
{
    public function recupererNoeudCommune();

    public function recupererNoeudCommuneParClePrimaire($gid);

    public function recupererNoeudCommunePar($nomCommune);

    public function getVillesAutoCompletion($nomCommune);


}