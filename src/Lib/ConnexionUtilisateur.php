<?php

namespace App\PlusCourtChemin\Lib;

use App\PlusCourtChemin\Modele\DataObject\Utilisateur;
use App\PlusCourtChemin\Modele\HTTP\Session;
use App\PlusCourtChemin\Modele\Repository\UtilisateurRepository;
use App\PlusCourtChemin\Service\UtilisateurServiceInterface;

class ConnexionUtilisateur
{
    private static string $cleConnexion = "_utilisateurConnecte";

    public function __construct(
        private readonly UtilisateurServiceInterface $utilisateurService
    )
    {
    }

    public function connecter(string $loginUtilisateur): void
    {
        $session = Session::getInstance();
        $session->enregistrer(ConnexionUtilisateur::$cleConnexion, $loginUtilisateur);
    }

    public function estConnecte(): bool
    {
        $session = Session::getInstance();
        return $session->existeCle(ConnexionUtilisateur::$cleConnexion);
    }

    public function deconnecter()
    {
        $session = Session::getInstance();
        $session->supprimer(ConnexionUtilisateur::$cleConnexion);
    }

    public function getLoginUtilisateurConnecte(): ?string
    {
        $session = Session::getInstance();
        if ($session->existeCle(ConnexionUtilisateur::$cleConnexion)) {
            return $session->lire(ConnexionUtilisateur::$cleConnexion);
        } else
            return null;
    }

    public function estUtilisateur($login): bool
    {
        return (ConnexionUtilisateur::estConnecte() &&
            ConnexionUtilisateur::getLoginUtilisateurConnecte() == $login
        );
    }

    public function estAdministrateur() : bool
    {
        $loginConnecte = ConnexionUtilisateur::getLoginUtilisateurConnecte();

        // Si personne n'est connecté
        if ($loginConnecte === null)
            return false;

        /** @var Utilisateur $utilisateurConnecte */
        $utilisateurConnecte = $this->utilisateurService->recupererUtilisateurParClePrimaire($loginConnecte);

        return ($utilisateurConnecte !== null && $utilisateurConnecte->getEstAdmin());
    }
}
