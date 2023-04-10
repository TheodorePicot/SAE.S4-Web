<?php

namespace App\PlusCourtChemin\Lib;

use App\PlusCourtChemin\Modele\DataObject\Utilisateur;
use App\PlusCourtChemin\Modele\HTTP\Cookie;

class ConnexionUtilisateurJWT implements ConnexionUtilisateurInterface
{

    public function connecter(string $login): void
    {
        Cookie::enregistrer("auth_token", JsonWebToken::encoder(["login" => $login]));
    }

    public function estConnecte(): bool
    {
        return !is_null($this->getLoginUtilisateurConnecte());
    }

    public function deconnecter(): void
    {
        if (Cookie::existeCle("auth_token"))
            Cookie::supprimer("auth_token");
    }

    public function getLoginUtilisateurConnecte(): ?string
    {
        if (Cookie::existeCle("auth_token")) {
            $jwt = Cookie::lire("auth_token");
            $donnees = JsonWebToken::decoder($jwt);
            return $donnees["login"] ?? null;
        } else
            return null;
    }

    public function estUtilisateur($login): bool
    {
        return (self::estConnecte() &&
            self::getLoginUtilisateurConnecte() == $login
        );
    }


    public function estAdministrateur(): bool
    {
        return true;
    }
}