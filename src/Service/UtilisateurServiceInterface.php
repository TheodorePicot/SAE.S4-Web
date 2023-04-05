<?php

namespace App\PlusCourtChemin\Service;

interface UtilisateurServiceInterface
{
    public function creerUtilisateur($login, $prenom, $nom, $mdp, $mdp2, $email);

    public function mettreAJour($login, $prenom, $nom, $mdp, $mdp2, $mdpAncien, $email);

    public function connecter($login, $mdp);

    public function deconnecter();

    public function validerEmail($login, $nonce);

    public function recupererUtilisateur();

    public function recupererUtilisateurParClePrimaire($login);

    public function supprimerUtilisateur(string $login, ?string $loginUtilisateurConnecte);
}