<?php

namespace App\PlusCourtChemin\Service;

use App\PlusCourtChemin\Modele\DataObject\Trajet;
use App\PlusCourtChemin\Modele\Repository\HistoriqueRepositoryInterface;
use App\PlusCourtChemin\Service\Exception\ServiceException;
use PDOException;
use Symfony\Component\HttpFoundation\Response;

class HistoriqueService implements HistoriqueServiceInterface
{
    public function __construct(private readonly HistoriqueRepositoryInterface $historiqueRepository)
    {
    }

    public function ajouterTrajet($login, $comm_depart, $coords_depart, $comm_arrivee, $coords_arrivee, $distance, $coordonneesDuChemin, $date)
    {
        $trajet = new Trajet(null, $login, $comm_depart, $coords_depart, $comm_arrivee, $coords_arrivee, $distance, $coordonneesDuChemin, $date, false);
        $this->historiqueRepository->ajouter($trajet);
    }

    public function getHistorique($login)
    {
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

    public function supprimerFavoris(int $idTrajet, ?string $idUtilisateurConnecte) {
        $trajet = $this->historiqueRepository->recupererParClePrimaire($idTrajet);
        if (is_null($idUtilisateurConnecte))
            throw new ServiceException("Il faut être connecté pour supprimer un trajet", Response::HTTP_UNAUTHORIZED);

        if ($idTrajet === null)
            throw new ServiceException("Trajet inconnue.", Response::HTTP_NOT_FOUND);

        if ($trajet->getLogin() !== intval($idUtilisateurConnecte))
            throw new ServiceException("Seul l'auteur du trajet peut le supprimer", Response::HTTP_FORBIDDEN);
        $this->historiqueRepository->supprimerFavoris($idTrajet);
    }
}