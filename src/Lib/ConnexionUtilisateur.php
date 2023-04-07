<?php

namespace App\PlusCourtChemin\Lib;

use App\PlusCourtChemin\Modele\DataObject\Utilisateur;
use App\PlusCourtChemin\Modele\HTTP\Session;
use App\PlusCourtChemin\Modele\Repository\UtilisateurRepository;
use App\PlusCourtChemin\Modele\Repository\UtilisateurRepositoryInterface;
use App\PlusCourtChemin\Service\UtilisateurServiceInterface;

class ConnexionUtilisateur
{
    private static string $cleConnexion = "_utilisateurConnecte";

    public function __construct(
        private readonly UtilisateurRepositoryInterface $utilisateurRepository
    )
    {
    }

    public function connecter(string $loginUtilisateur): void
    {
        $session = Session::getInstance();
        $session->enregistrer(self::$cleConnexion, $loginUtilisateur);
    }

    public function estConnecte(): bool
    {
        $session = Session::getInstance();
        return $session->existeCle(self::$cleConnexion);
    }

    public function deconnecter()
    {
        $session = Session::getInstance();
        $session->supprimer(self::$cleConnexion);
    }

    public function getLoginUtilisateurConnecte(): ?string
    {
        $session = Session::getInstance();
        if ($session->existeCle(self::$cleConnexion)) {
            return $session->lire(self::$cleConnexion);
        } else
            return null;
    }

    public function estUtilisateur($login): bool
    {
        return (self::estConnecte() &&
            self::getLoginUtilisateurConnecte() == $login
        );
    }

    public function estAdministrateur() : bool
    {
        $loginConnecte = self::getLoginUtilisateurConnecte();

        // Si personne n'est connecté
        if ($loginConnecte === null)
            return false;

        /** @var Utilisateur $utilisateurConnecte */
        $utilisateurConnecte = $this->utilisateurRepository->recupererParClePrimaire($loginConnecte);

        return ($utilisateurConnecte !== null && $utilisateurConnecte->getEstAdmin());
    }
}
