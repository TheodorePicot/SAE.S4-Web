<?php

namespace App\PlusCourtChemin\Service;

interface HistoriqueServiceInterface
{
    public function ajouterTrajet($login, $comm_depart, $coords_depart, $comm_arrivee, $coords_arrivee, $distance, $coordonneesDuChemin, $date);

    public function getHistorique($login);

    public function getFavoris($login);

    public function supprimerTrajet($idTrajet);

    public function recupererParClePrimaire(int $idTrajet);

    public function ajouterFavoris(int $idTrajet);

    public function supprimerFavoris(int $idTrajet);
}