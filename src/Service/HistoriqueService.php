<?php

namespace App\PlusCourtChemin\Service;

use App\PlusCourtChemin\Lib\ConnexionUtilisateurSession;
use App\PlusCourtChemin\Modele\DataObject\Trajet;
use App\PlusCourtChemin\Modele\Repository\HistoriqueRepositoryInterface;
use App\PlusCourtChemin\Service\Exception\ServiceException;
use PDOException;
use Symfony\Component\HttpFoundation\Response;

class HistoriqueService implements HistoriqueServiceInterface
{
    public function __construct(private readonly HistoriqueRepositoryInterface $historiqueRepository, private  readonly ConnexionUtilisateurSession $connexionUtilisateur)
    {
    }

    public function ajouterTrajet($login, $comm_depart, $coords_depart, $comm_arrivee, $coords_arrivee, $distance, $coordonneesDuChemin, $date)
    {
        $trajet = new Trajet(null, $login, $comm_depart, $coords_depart, $comm_arrivee, $coords_arrivee, $distance, $coordonneesDuChemin, $date, false);
        $this->historiqueRepository->ajouter($trajet);
    }

    public function getHistorique($login)
    {
        if ($login !== $this->connexionUtilisateur->getLoginUtilisateurConnecte())
            throw new ServiceException("Seul l'utilisateur connecter peut récupérer son historique", Response::HTTP_UNAUTHORIZED);
        if ($login === null)
            throw new ServiceException("L'utilisateur n'est pas connecté");
        return $this->historiqueRepository->recupererPar(["login_utilisateur" => $login]);
    }

    public function getFavoris($login)
    {
        if ($login !== $this->connexionUtilisateur->getLoginUtilisateurConnecte())
            throw new ServiceException("Seul l'utilisateur connecter peut récupérer ses favoris",Response::HTTP_UNAUTHORIZED);
        if ($login === null)
            throw new ServiceException("L'utilisateur n'est pas connecté");
        return $this->historiqueRepository->recupererFavorisPar(["login_utilisateur" => $login]);
    }

    public function supprimerTrajet($idTrajet)
    {
        if ($this->connexionUtilisateur->getLoginUtilisateurConnecte() === null)
            throw new ServiceException("L'utilisateur n'est pas connecté", Response::HTTP_UNAUTHORIZED);
        $trajet = $this->historiqueRepository->recupererParClePrimaire($idTrajet);
        if ($trajet->getLogin() != $this->connexionUtilisateur->getLoginUtilisateurConnecte())
            throw new ServiceException("Seul l'utilisateur connecter peut supprimer ses trajets");
        $this->historiqueRepository->supprimer($idTrajet);
    }

    public function recupererParClePrimaire(int $idTrajet) {
        $trajet = $this->historiqueRepository->recupererParClePrimaire($idTrajet);
        if ($this->connexionUtilisateur->getLoginUtilisateurConnecte() === null)
            throw new ServiceException("L'utilisateur n'est pas connecté", Response::HTTP_UNAUTHORIZED);
        if ($trajet === null)
            throw new ServiceException("Trajet inconnue.");
        if ($trajet->getLogin() != $this->connexionUtilisateur->getLoginUtilisateurConnecte())
            throw new ServiceException("Seul l'utilisateur connecter peut recuperer un de ces trajets");
        return $trajet;
    }

    public function ajouterFavoris(int $idTrajet) {
        $trajet = $this->historiqueRepository->recupererParClePrimaire($idTrajet);
        if ($this->connexionUtilisateur->getLoginUtilisateurConnecte() === null)
            throw new ServiceException("L'utilisateur n'est pas connecté", Response::HTTP_UNAUTHORIZED);
        if ($trajet === null)
            throw new ServiceException("Trajet inconnue.");
        if ($trajet->getLogin() != $this->connexionUtilisateur->getLoginUtilisateurConnecte())
            throw new ServiceException("Seul l'utilisateur connecter peut ajouter mettre en favoris un de ses trajets");
        $this->historiqueRepository->ajouterFavoris($idTrajet);
    }

    public function supprimerFavoris(int $idTrajet) {
        $trajet = $this->historiqueRepository->recupererParClePrimaire($idTrajet);
        if (is_null($this->connexionUtilisateur->getLoginUtilisateurConnecte())) {
            throw new ServiceException("Il faut être connecté pour supprimer un trajet", Response::HTTP_UNAUTHORIZED);
        }
        if ($idTrajet === null) {
            throw new ServiceException("Trajet inconnue.", Response::HTTP_NOT_FOUND);
        }

        $this->historiqueRepository->supprimerFavoris($idTrajet);
    }
}