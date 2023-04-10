<?php

namespace App\PlusCourtChemin\Service;

use App\PlusCourtChemin\Lib\ConnexionUtilisateur;
use App\PlusCourtChemin\Lib\MotDePasse;
use App\PlusCourtChemin\Lib\VerificationEmail;
use App\PlusCourtChemin\Modele\DataObject\Utilisateur;
use App\PlusCourtChemin\Modele\Repository\UtilisateurRepository;
use App\PlusCourtChemin\Modele\Repository\UtilisateurRepositoryInterface;
use App\PlusCourtChemin\Service\Exception\ServiceException;

class UtilisateurService implements UtilisateurServiceInterface
{

    public function __construct(private readonly UtilisateurRepositoryInterface $utilisateurRepository, private readonly ConnexionUtilisateur $connexionUtilisateur, private readonly VerificationEmail $verificationEmail)
    {

    }

    public function creerUtilisateur($login, $prenom, $nom, $mdp, $mdp2, $email)
    {
        $utilisateur = $this->utilisateurRepository->recupererParClePrimaire($login);
        if ($utilisateur != null) {
            throw new ServiceException("Ce login est déjà pris!");
        }

        $utilisateur = $this->utilisateurRepository->recupererPar(["email" => $email]);
        if ($utilisateur != null) {
            throw new ServiceException("Un compte est déjà enregistré avec cette adresse mail!");
        }
        if (!(isset($login) && isset($prenom) && isset($nom) && isset($mdp) && isset($mdp2))) {
            throw new ServiceException("Login, nom, prenom ou mot de passe manquant!");
        }

        /* TODO : se mettre d'accord pour les règles de validation
        if (strlen($login) < 8 || strlen($login) > 20) {
            throw new ServiceException("Le login doit être compris entre 8 et 20 caractères!");
        }
        if (!preg_match("#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,20}$#", $mdp)) {
            throw new ServiceException("Mot de passe invalide!");
        }
        */

        if ($mdp !== $mdp2) {
            throw new ServiceException("Mots de passe distincts!");
        }
        if (!$this->connexionUtilisateur->estAdministrateur()) {
            unset($_REQUEST["estAdmin"]);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ServiceException("Email non valide!");
        }

        $utilisateur = Utilisateur::construireDepuisFormulaire($_REQUEST);
        $this->verificationEmail->envoiEmailValidation($utilisateur);
        $succesSauvegarde = $this->utilisateurRepository->ajouter($utilisateur);

        if (!$succesSauvegarde) {
            throw new ServiceException("Login existant.");
        }
    }

    public function mettreAJour($login, $prenom, $nom, $mdp, $mdp2, $mdpAncien, $email)
    {
        if (!(isset($login) && isset($prenom) && isset($nom)
            && isset($mdp) && isset($mdp2) && isset($mdpAncien)
            && isset($email))) {
            throw new ServiceException("Login, nom, prenom, email ou mot de passe manquant.");
        }

        if ($mdp !== $mdp2) {
            throw new ServiceException("Mots de passe distincts.");
        }
        /* TODO : se mettre d'accord pour les règles de validation
        if (!preg_match("#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,20}$#", $mdp)) {
            throw new ServiceException("Mot de passe invalide!");
        }
        */


        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ServiceException("Email non valide");
        }

        /** @var Utilisateur $utilisateur */
        $utilisateur = $this->utilisateurRepository->recupererParClePrimaire($login);

        if ($utilisateur == null) {
            throw new ServiceException("Login inconnu");
        }

        if (!MotDePasse::verifier($mdpAncien, $utilisateur->getMdpHache())) {
            throw new ServiceException("Ancien mot de passe erroné.");
        }

        if (!($this->connexionUtilisateur->estConnecte() || $this->connexionUtilisateur->estAdministrateur())) {
            throw new ServiceException("La mise à jour n'est possible que pour l'utilisateur connecté ou un administrateur");
        }

        $utilisateur->setNom($nom);
        $utilisateur->setPrenom($prenom);
        $utilisateur->setMdpHache($mdp);

        if ($this->connexionUtilisateur->estAdministrateur()) {
            $utilisateur->setEstAdmin(isset($_REQUEST["estAdmin"]));
        }

        if ($email !== $utilisateur->getEmail()) {
            $utilisateur->setEmailAValider($email);
            $utilisateur->setNonce(MotDePasse::genererChaineAleatoire());

            $this->verificationEmail->envoiEmailValidation($utilisateur);
        }

        $this->utilisateurRepository->mettreAJour($utilisateur);
    }

    public function connecter($login, $mdp)
    {
        if (!(isset($login) && isset($mdp))) {
            throw new ServiceException("Login ou mot de passe manquant.");
        }
        /** @var Utilisateur $utilisateur */
        $utilisateur = $this->utilisateurRepository->recupererParClePrimaire($login);

        if ($utilisateur == null) {
            throw new ServiceException("Login inconnu.");
        }

        if (!MotDePasse::verifier($mdp, $utilisateur->getMdpHache())) {
            throw new ServiceException("Mot de passe incorrect.");
        }

        if (!$this->verificationEmail->aValideEmail($utilisateur)) {
            throw new ServiceException("Adresse email non validée.");
        }

        $this->connexionUtilisateur->connecter($utilisateur->getLogin());
    }

    public function deconnecter()
    {
        if (!$this->connexionUtilisateur->estConnecte()) {
            throw new ServiceException("Utilisateur non connecté.");
        }
        $this->connexionUtilisateur->deconnecter();
    }

    public function validerEmail($login, $nonce)
    {
        if (!(isset($login) && isset($nonce))) {
            throw new ServiceException("Login ou nonce manquant.");
        }

        $succesValidation = $this->verificationEmail->traiterEmailValidation($login, $nonce);

        if (!$succesValidation) {
            throw new ServiceException("Email de validation incorrect.");
        }
    }

    public function recupererUtilisateur() {
        $utilisateur = $this->utilisateurRepository->recuperer();
        return $utilisateur;
    }

    public function recupererUtilisateurParClePrimaire($login) {
        $utilisateur = $this->utilisateurRepository->recupererParClePrimaire($login);
        if($utilisateur == null) {
            throw new ServiceException("L’utilisateur n’existe pas!");
        }
        return $utilisateur;
    }

    public function supprimerUtilisateur(string $login, ?string $loginUtilisateurConnecte) {
        $utilisateur = $this->utilisateurRepository->recupererParClePrimaire($login);

        if ($utilisateur === null)
            throw new ServiceException("Utilisateur inconnue.");

        if ($utilisateur->getLogin() !== intval($loginUtilisateurConnecte))
            throw new ServiceException("Seul l'utilisateur connecter peut supprimer son compte");

        return $this->utilisateurRepository->supprimer($login);
    }
}