<?php

namespace App\PlusCourtChemin\Service;


use App\PlusCourtChemin\Controleur\ControleurUtilisateur;
use App\PlusCourtChemin\Lib\ConnexionUtilisateur;
use App\PlusCourtChemin\Lib\MessageFlash;
use App\PlusCourtChemin\Lib\MotDePasse;
use App\PlusCourtChemin\Lib\VerificationEmail;
use App\PlusCourtChemin\Modele\DataObject\Utilisateur;
use App\PlusCourtChemin\Modele\Repository\NoeudCommuneRepository;
use App\PlusCourtChemin\Modele\Repository\UtilisateurRepository;
use App\PlusCourtChemin\Service\Exception\ServiceException;

class UtilisateurService {

    public static function creerUtilisateur($login, $prenom, $nom, $mdp, $mdp2, $email)
    {
        if (!(isset($login) && isset($prenom) && isset($nom)
            && isset($mdp) && isset($mdp2))
        ) {
            throw new ServiceException("Login, nom, prenom ou mot de passe manquant.");
        }

        if ($mdp !== $mdp2) {
            throw new ServiceException("Mots de passe distincts.");
        }

        if (!ConnexionUtilisateur::estAdministrateur()) {
            unset($_REQUEST["estAdmin"]);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ServiceException("Email non valide");
        }

        $utilisateur = Utilisateur::construireDepuisFormulaire($_REQUEST);

        VerificationEmail::envoiEmailValidation($utilisateur);

        $utilisateurRepository = new UtilisateurRepository();
        $succesSauvegarde = $utilisateurRepository->ajouter($utilisateur);
        if (!$succesSauvegarde) {
            throw new ServiceException("Login existant.");
        }
    }

    public static function mettreAJour($login, $prenom, $nom, $mdp, $mdp2, $mdpAncien, $email)
    {
        if (!(isset($login) && isset($prenom) && isset($nom)
            && isset($mdp) && isset($mdp2) && isset($mdpAncien)
            && isset($email)
        )) {
            throw new ServiceException("Login, nom, prenom, email ou mot de passe manquant.");
        }

        if ($mdp !== $mdp2) {
            throw new ServiceException("Mots de passe distincts.");
        }

        if (!(ConnexionUtilisateur::estConnecte($_REQUEST["login"]) || ConnexionUtilisateur::estAdministrateur())) {
            throw new ServiceException("La mise à jour n'est possible que pour l'utilisateur connecté ou un administrateur");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ServiceException("Email non valide");
        }

        $utilisateurRepository = new UtilisateurRepository();
        /** @var Utilisateur $utilisateur */
        $utilisateur = $utilisateurRepository->recupererParClePrimaire($login);

        if ($utilisateur == null) {
            throw new ServiceException("Login inconnu");
        }

        if (!MotDePasse::verifier($mdpAncien, $utilisateur->getMdpHache())) {
            throw new ServiceException("Ancien mot de passe erroné.");
        }

        $utilisateur->setNom($nom);
        $utilisateur->setPrenom($prenom);
        $utilisateur->setMdpHache($mdp);

        if (ConnexionUtilisateur::estAdministrateur()) {
            $utilisateur->setEstAdmin(isset($_REQUEST["estAdmin"]));
        }

        if ($email !== $utilisateur->getEmail()) {
            $utilisateur->setEmailAValider($email);
            $utilisateur->setNonce(MotDePasse::genererChaineAleatoire());

            VerificationEmail::envoiEmailValidation($utilisateur);
        }

        $utilisateurRepository->mettreAJour($utilisateur);
    }

    public static function connecter($login, $mdp)
    {
        if (!(isset($login) && isset($mdp))) {
            throw new ServiceException("Login ou mot de passe manquant.");
        }
        $utilisateurRepository = new UtilisateurRepository();
        /** @var Utilisateur $utilisateur */
        $utilisateur = $utilisateurRepository->recupererParClePrimaire($login);

        if ($utilisateur == null) {
            throw new ServiceException("Login inconnu.");
        }

        if (!MotDePasse::verifier($mdp, $utilisateur->getMdpHache())) {
            throw new ServiceException("Mot de passe incorrect.");
        }

        if (!VerificationEmail::aValideEmail($utilisateur)) {
            throw new ServiceException("Adresse email non validée.");
        }

        ConnexionUtilisateur::connecter($utilisateur->getLogin());
    }


}
