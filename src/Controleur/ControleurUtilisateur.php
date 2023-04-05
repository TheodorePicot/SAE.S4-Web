<?php

namespace App\PlusCourtChemin\Controleur;

use App\PlusCourtChemin\Configuration\Configuration;
use App\PlusCourtChemin\Lib\ConnexionUtilisateur;
use App\PlusCourtChemin\Lib\MessageFlash;
use App\PlusCourtChemin\Modele\DataObject\Utilisateur;
use App\PlusCourtChemin\Modele\Repository\UtilisateurRepository;
use App\PlusCourtChemin\Service\Exception\ServiceException;
use App\PlusCourtChemin\Service\UtilisateurService;
use Symfony\Component\HttpFoundation\Response;

class ControleurUtilisateur extends ControleurGenerique
{

    public static function afficherErreur($errorMessage = "", $controleur = ""): Response
    {
        return parent::afficherErreur($errorMessage, "utilisateur");
    }


    public static function afficherListe(): Response
    {
        $utilisateurs = (new UtilisateurRepository())->recuperer();     //appel au modèle pour gerer la BD
        return ControleurUtilisateur::afficherVue('vueGenerale.php', [
            "utilisateurs" => $utilisateurs,
            "pagetitle" => "Liste des utilisateurs",
            "cheminVueBody" => "utilisateur/liste.php"
        ]);
    }

    public static function afficherDetail($login): Response
    {
//        if (isset($_REQUEST['login'])) {
//            $login = $_REQUEST['login'];
        $utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($login);
        if ($utilisateur === null) {
            MessageFlash::ajouter("warning", "Login inconnu.");
            return ControleurUtilisateur::rediriger("afficherListeUtilisateur");
        } else {
            return ControleurUtilisateur::afficherVue('vueGenerale.php', [
                "utilisateur" => $utilisateur,
                "pagetitle" => "Détail de l'utilisateur",
                "cheminVueBody" => "utilisateur/detail.php"
            ]);
        }
//        } else {
//            MessageFlash::ajouter("danger", "Login manquant.");
//            ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
//        }
    }

    public static function supprimer($login)
    {
//        if (isset($_REQUEST['login'])) {
//            $login = $_REQUEST['login'];
        $utilisateurRepository = new UtilisateurRepository();
        $deleteSuccessful = $utilisateurRepository->supprimer($login);
        $utilisateurs = $utilisateurRepository->recuperer();
        if ($deleteSuccessful) {
            MessageFlash::ajouter("success", "L'utilisateur a bien été supprimé !");
            return ControleurUtilisateur::rediriger("afficherListeUtilisateur");
        } else {
            MessageFlash::ajouter("warning", "Login inconnu.");
            return ControleurUtilisateur::rediriger("afficherListeUtilisateur");
        }
//        } else {
//            MessageFlash::ajouter("danger", "Login manquant.");
//            ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
//        }
    }

    public static function afficherFormulaireCreation(): Response
    {
        return ControleurUtilisateur::afficherVue('vueGenerale.php', [
            "pagetitle" => "Création d'un utilisateur",
            "cheminVueBody" => "utilisateur/formulaireCreation.php",
            "method" => Configuration::getDebug() ? "get" : "post",
        ]);
    }


    public static function creerDepuisFormulaire(): Response
    {
        $login = $_POST['login'] ?? null;
        $nom = $_POST['nom'] ?? null;
        $prenom = $_POST['prenom'] ?? null;
        $mdp = $_POST['mdp'] ?? null;
        $mdp2 = $_POST['mdp2'] ?? null;
        $email = $_POST['email'] ?? null;
        //Recupérer les différentes variables (login, mot de passe, adresse mail, données photo de profil...)
        try {
            (new UtilisateurService())->creerUtilisateur($login, $nom, $prenom, $mdp, $mdp2, $email);
        } catch (ServiceException $e) {
            MessageFlash::ajouter("error", $e->getMessage());
            return ControleurUtilisateur::rediriger('afficherFormulaireCreation');
        }
        MessageFlash::ajouter("success", "L'utilisateur a bien été créé !");
        return ControleurUtilisateur::rediriger("afficherListeUtilisateur");
    }

    public static function afficherFormulaireMiseAJour($login): Response
    {
//        if (isset($_REQUEST['login'])) {
//            $login = $_REQUEST['login'];
        /** @var Utilisateur $utilisateur */
        $utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($login);
        if ($utilisateur === null) {
            MessageFlash::ajouter("danger", "Login inconnu.");
            return ControleurUtilisateur::rediriger("afficherListeUtilisateur");
        }
        if (!(ConnexionUtilisateur::estUtilisateur($login) || ConnexionUtilisateur::estAdministrateur())) {
            MessageFlash::ajouter("danger", "La mise à jour n'est possible que pour l'utilisateur connecté ou un administrateur");
            return ControleurUtilisateur::rediriger("afficherListeUtilisateur");
        }

        $loginHTML = htmlspecialchars($login);
        $prenomHTML = htmlspecialchars($utilisateur->getPrenom());
        $nomHTML = htmlspecialchars($utilisateur->getNom());
        $emailHTML = htmlspecialchars($utilisateur->getEmail());
        return ControleurUtilisateur::afficherVue('vueGenerale.php', [
            "pagetitle" => "Mise à jour d'un utilisateur",
            "cheminVueBody" => "utilisateur/formulaireMiseAJour.php",
            "loginHTML" => $loginHTML,
            "prenomHTML" => $prenomHTML,
            "nomHTML" => $nomHTML,
            "emailHTML" => $emailHTML,
            "estAdmin" => $utilisateur->getEstAdmin(),
            "method" => Configuration::getDebug() ? "get" : "post",
        ]);
//        } else {
//            MessageFlash::ajouter("danger", "Login manquant.");
//            ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
//        }
    }

    public static function mettreAJour(): Response
    {
        $login = $_POST['login'] ?? null;
        $nom = $_POST['nom'] ?? null;
        $prenom = $_POST['prenom'] ?? null;
        $mdp = $_POST['mdp'] ?? null;
        $mdp2 = $_POST['mdp2'] ?? null;
        $mdpAncien = $_POST['mdpAncien'] ?? null;
        $email = $_POST['email'] ?? null;
        //Recupérer les différentes variables (login, mot de passe, adresse mail, données photo de profil...)
        try {
            (new UtilisateurService())->mettreAJour($login, $nom, $prenom, $mdp, $mdp2, $mdpAncien, $email);
        } catch (ServiceException $e) {
            MessageFlash::ajouter("error", $e->getMessage());
            return ControleurUtilisateur::rediriger('afficherListeUtilisateur');
        }
        MessageFlash::ajouter("success", "L'utilisateur a bien été modifié !");
        return ControleurUtilisateur::rediriger("afficherListeUtilisateur");
    }

    public static function afficherFormulaireConnexion(): Response
    {
        return ControleurUtilisateur::afficherVue('vueGenerale.php', [
            "pagetitle" => "Formulaire de connexion",
            "cheminVueBody" => "utilisateur/formulaireConnexion.php",
            "method" => Configuration::getDebug() ? "get" : "post",
        ]);
    }

    public function connecter(): Response
    {
        $login = $_POST['login'] ?? null;
        $password = $_POST['mdp'] ?? null;
        try {
            (new UtilisateurService())->connecter($login, $password);
        } catch (ServiceException $e) {
            MessageFlash::ajouter("danger", $e->getMessage());
            return ControleurUtilisateur::rediriger('afficherFormulaireConnexion');
        }
        MessageFlash::ajouter("success", "Connexion effectuée.");
        return ControleurUtilisateur::rediriger("afficherDetailUtilisateur", ["login" => $login]);
    }

    public function deconnecter(): Response
    {
        try {
            (new UtilisateurService())->deconnecter();
        } catch (ServiceException $e) {

            MessageFlash::ajouter("danger", $e->getMessage());
            return ControleurUtilisateur::rediriger('afficherListeUtilisateur');
        }
        MessageFlash::ajouter("success", "Deconnexion effectuée.");
        return ControleurUtilisateur::rediriger('afficherListeUtilisateur');

    }

    public function validerEmail(): Response
    {
        $login = $_POST['login'] ?? null;
        $nonce = $_POST['nonce'] ?? null;
        try {
            (new UtilisateurService())->validerEmail($login, $nonce);
        } catch (ServiceException $e) {
            MessageFlash::ajouter("danger", $e->getMessage());
            return ControleurUtilisateur::rediriger('afficherListeUtilisateur');
        }
        MessageFlash::ajouter("success", "Validation d'email réussie");
        return ControleurUtilisateur::rediriger("afficherDetailUtilisateur", ["login" => $login]);
    }
}
