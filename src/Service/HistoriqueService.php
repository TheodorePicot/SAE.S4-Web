<?php

namespace App\PlusCourtChemin\Service;

use App\PlusCourtChemin\Modele\DataObject\Trajet;
use App\PlusCourtChemin\Modele\Repository\HistoriqueRepositoryInterface;
use App\PlusCourtChemin\Service\Exception\ServiceException;
use PDOException;

class HistoriqueService
{
    public function __construct(private readonly HistoriqueRepositoryInterface $historiqueRepository)
    {
    }

    public function ajouterTrajet($login, $comm_depart, $coords_depart, $comm_arrivee, $coords_arrivee, $distance, $coordonneesDuChemin, $date)
    {
        $trajet = new Trajet(null, $login, $comm_depart, $coords_depart, $comm_arrivee, $coords_arrivee, $distance, $coordonneesDuChemin, $date);
        $this->historiqueRepository->ajouter($trajet);
    }

    public function getHistorique($login)
    {
        return $this->historiqueRepository->recupererPar(["loginUtilisateur" =>$login]);
    }

    public function supprimerTrajet($idTrajet)
    {
        $this->historiqueRepository->supprimer($idTrajet);
    }
}