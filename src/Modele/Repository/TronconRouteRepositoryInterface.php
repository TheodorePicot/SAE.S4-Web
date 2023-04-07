<?php

namespace App\PlusCourtChemin\Modele\Repository;

use App\PlusCourtChemin\Modele\DataObject\AbstractDataObject;
use App\PlusCourtChemin\Modele\DataObject\TronconRoute;

interface TronconRouteRepositoryInterface
{
    public function construireDepuisTableau(array $noeudRoutierTableau): TronconRoute;

    public function supprimer(string $valeurClePrimaire): bool;

    public function mettreAJour(AbstractDataObject $object): void;

    public function ajouter(AbstractDataObject $object): bool;
}