<?php

namespace App\PlusCourtChemin\Service;


use App\PlusCourtChemin\Modele\Repository\NoeudRoutierRepositoryInterface;
use App\PlusCourtChemin\Service\Exception\ServiceException;

class NoeudRoutierService implements NoeudRoutierServiceInterface
{
    public function __construct(private readonly NoeudRoutierRepositoryInterface $noeudRoutierRepository)
    {

    }

    public function recupererNoeudRoutierPar($id_rte500)
    {
        $noeudRoutier = $this->noeudRoutierRepository->recupererPar($id_rte500);
        return $noeudRoutier;
    }

    public function getVoisins(int $noeudRoutierGid)
    {
        $noeudRoutier = $this->noeudRoutierRepository->getVoisins($noeudRoutierGid);
        return $noeudRoutier;
    }

    public function getTousLesVoisins()
    {
        $noeudRoutier = $this->noeudRoutierRepository->getTousLesVoisins();
        return $noeudRoutier;
    }

    public function getTousLesVoisinsV2()
    {
        $noeudRoutier = $this->noeudRoutierRepository->getTousLesVoisinsV2();
        return $noeudRoutier;
    }

    public function getLongitudeLatitude(int $noeudRoutierGid)
    {
        $noeudRoutier = $this->noeudRoutierRepository->getLongitudeLatitude($noeudRoutierGid);
        return $noeudRoutier;
    }
}


