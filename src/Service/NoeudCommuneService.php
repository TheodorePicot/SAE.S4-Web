<?php

namespace App\PlusCourtChemin\Service;

use App\PlusCourtChemin\Modele\Repository\NoeudCommuneRepository;
use App\PlusCourtChemin\Modele\Repository\NoeudCommuneRepositoryInterface;
use App\PlusCourtChemin\Service\Exception\ServiceException;

class NoeudCommuneService implements NoeudCommuneServiceInterface
{

    public function __construct(private readonly NoeudCommuneRepositoryInterface $noeudCommuneRepository)
    {

    }

    public function recupererNoeudCommune()
    {
        $noeudsCommunes = $this->noeudCommuneRepository->recuperer();
        return $noeudsCommunes;
    }

    public function recupererNoeudCommuneParClePrimaire($gid)
    {
        $noeud = $this->noeudCommuneRepository->recupererParClePrimaire($gid);
        if($noeud == null) {
            throw new ServiceException("Gid inconnue!");
        }
        return $noeud;
    }

    public function recupererNoeudCommunePar($nomCommune)
    {
        $noeudsCommunes = $this->noeudCommuneRepository->recupererPar($nomCommune);
        return $noeudsCommunes;
    }

    public function getVillesAutoCompletion($nomCommune)
    {
        $noeudsCommunes = $this->noeudCommuneRepository->getVillesAutoCompletion($nomCommune);
        return $noeudsCommunes;
    }
}

