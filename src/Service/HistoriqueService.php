<?php

namespace App\PlusCourtChemin\Service;

use App\PlusCourtChemin\Modele\DataObject\Trajet;
use App\PlusCourtChemin\Modele\Repository\HistoriqueRepositoryInterface;
use App\PlusCourtChemin\Service\Exception\ServiceException;

class HistoriqueService
{
    public function __construct(private readonly HistoriqueRepositoryInterface $historiqueRepository, private readonly ConnexionUtilisateur $connexionUtilisateur)
    {
    }

    public function ajouterTrajet($login, $comm_depart, $coords_depart, $comm_arrivee, $coords_arrivee, $distance, $coordonneesDuChemin, $date)
    {
        $trajet = new Trajet(null, $login, $comm_depart, $coords_depart, $comm_arrivee, $coords_arrivee, $distance, $coordonneesDuChemin, $date, false);
        $this->historiqueRepository->ajouter($trajet);
    }

    public function getHistorique($login)
    {

        if ($login !== intval($loginUtilisateurConnecte))
            throw new ServiceException("Seul l'utilisateur connecter peut supprimer son compte");
        return $this->historiqueRepository->recupererPar(["login_utilisateur" => $login]);
    }

    public function getFavoris($login)
    {
        return $this->historiqueRepository->recupererFavorisPar(["login_utilisateur" => $login]);
    }

    public function supprimerTrajet($idTrajet)
    {
        $this->historiqueRepository->supprimer($idTrajet);
    }

    public function recupererParClePrimaire(int $idTrajet) {
        return $this->historiqueRepository->recupererParClePrimaire($idTrajet);
    }

    public function ajouterFavoris(int $idTrajet) {
        $this->historiqueRepository->ajouterFavoris($idTrajet);
    }

    public function supprimerFavoris(int $idTrajet) {
        $this->historiqueRepository->supprimerFavoris($idTrajet);
    }
}