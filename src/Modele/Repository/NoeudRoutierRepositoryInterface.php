<?php

namespace App\PlusCourtChemin\Modele\Repository;

use App\PlusCourtChemin\Modele\DataObject\AbstractDataObject;
use App\PlusCourtChemin\Modele\DataObject\NoeudRoutier;

interface NoeudRoutierRepositoryInterface
{
    public function construireDepuisTableau(array $noeudRoutierTableau): NoeudRoutier;

    public function supprimer(string $valeurClePrimaire): bool;

    public function mettreAJour(AbstractDataObject $object): void;

    public function ajouter(AbstractDataObject $object): bool;

    /**
     * Renvoie le tableau des voisins d'un noeud routier
     *
     * Chaque voisin est un tableau avec les 3 champs
     * `noeud_routier_gid`, `troncon_gid`, `longueur`
     *
     * @param int $noeudRoutierGid
     * @return String[][]
     **/
    public function getVoisins(int $noeudRoutierGid): array;

    public function getTousLesVoisins(): array;

    public function getLongitudeLatitude(int $noeudRoutierGid): array;
}