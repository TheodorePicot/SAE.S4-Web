<?php

namespace App\PlusCourtChemin\Service;

use App\PlusCourtChemin\Modele\Repository\NoeudCommuneRepository;
use App\PlusCourtChemin\Modele\Repository\NoeudRoutierRepositoryInterface;

class NoeudCommuneService implements NoeudCommuneServiceInterface
{

    private NoeudRoutierRepositoryInterface $noeudRoutierRepository;

    public function __construct(NoeudRoutierRepositoryInterface $noeudRoutierRepository)
    {
        $this->noeudRoutierRepository = $noeudRoutierRepository;
    }

    public function recuperer()
    {
        $noeudsCommunes = $this->noeudRoutierRepository->recuperer();
        return $noeudsCommunes;
    }
}

