<?php

namespace App\PlusCourtChemin\Service;


use App\PlusCourtChemin\Modele\Repository\NoeudCommuneRepository;

class NoeudCommuneService {


    public static function recuperer()
    {
        $noeudsCommunes = (new NoeudCommuneRepository())->recuperer();
        return $noeudsCommunes;
    }
}

