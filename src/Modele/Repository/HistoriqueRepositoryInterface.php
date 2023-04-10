<?php

namespace App\PlusCourtChemin\Modele\Repository;

interface HistoriqueRepositoryInterface
{
    public function recupererPar(array $critereSelection, $limit = 10): array;

    public function ajouterFavoris(int $idTrajet): void;

    public function supprimerFavoris(int $idTrajet);

    public function recupererFavorisPar(array $critereSelection, $limit = 10): array;
}