<?php

namespace App\PlusCourtChemin\Lib;

interface ConnexionUtilisateurInterface
{
    public function connecter(string $loginUtilisateur): void;

    public function estConnecte(): bool;

    public function deconnecter();

    public function getLoginUtilisateurConnecte(): ?string;

    public function estUtilisateur($login): bool;

    public function estAdministrateur(): bool;
}