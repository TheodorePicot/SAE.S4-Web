<?php

namespace App\PlusCourtChemin\Modele\Repository;

use App\PlusCourtChemin\Modele\DataObject\Utilisateur;

interface UtilisateurRepositoryInterface
{
    public function construireDepuisTableau(array $utilisateurTableau): Utilisateur;

    public function getNomTable(): string;
}