<?php

namespace App\PlusCourtChemin\Modele\Repository;

use App\PlusCourtChemin\Modele\DataObject\AbstractDataObject;

interface AbstractRepositoryInterface
{
    /**
     * @param int|string $limit Nombre de réponses ("ALL" pour toutes les réponses)
     * @return AbstractDataObject[]
     */
    public function recuperer($limit = 200): array;

    /**
     * @param array $critereSelection ex: ["nomColonne" => valeurDeRecherche]
     * @return AbstractDataObject[]
     */
    public function recupererPar(array $critereSelection, $limit = 200): array;

    public function recupererParClePrimaire(string $valeurClePrimaire): ?AbstractDataObject;

    public function supprimer(string $valeurClePrimaire): bool;

    public function mettreAJour(AbstractDataObject $object): void;

    public function ajouter(AbstractDataObject $object): bool;
}