<?php

namespace App\PlusCourtChemin\Modele\Repository;

use App\PlusCourtChemin\Modele\DataObject\AbstractDataObject;
use App\PlusCourtChemin\Modele\DataObject\NoeudCommune;

interface NoeudCommuneRepositoryInterface
{
    public function construireDepuisTableau(array $noeudRoutierTableau): NoeudCommune;

    public function supprimer(string $valeurClePrimaire): bool;

    public function mettreAJour(AbstractDataObject $object): void;

    public function ajouter(AbstractDataObject $object): bool;

    public function getVillesAutoCompletion(string $nomCommune): array;
}