<?php

namespace App\PlusCourtChemin\Controleur;

use App\PlusCourtChemin\Configuration\Configuration;
use App\PlusCourtChemin\Lib\ConnexionUtilisateur;
use App\PlusCourtChemin\Lib\MessageFlash;
use App\PlusCourtChemin\Lib\MotDePasse;
use App\PlusCourtChemin\Lib\VerificationEmail;
use App\PlusCourtChemin\Modele\DataObject\Utilisateur;
use App\PlusCourtChemin\Modele\Repository\UtilisateurRepository;
use App\PlusCourtChemin\Service\Exception\ServiceException;
use App\PlusCourtChemin\Service\UtilisateurService;
use Symfony\Component\HttpFoundation\Response;

class ControleurUtilisateur extends ControleurGenerique
{

    public static function afficherErreur($errorMessage = "", $controleur = ""): Response
    {
        parent::afficherErreur($errorMessage, "utilisateur");
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

//    public static function creerDepuisFormulaire(): Response
//    {
//        if (
//            isset($_REQUEST['login']) && isset($_REQUEST['prenom']) && isset($_REQUEST['nom'])
//            && isset($_REQUEST['mdp']) && isset($_REQUEST['mdp2'])
//        ) {
//            if ($_REQUEST["mdp"] !== $_REQUEST["mdp2"]) {
//                MessageFlash::ajouter("warning", "Mots de passe distincts.");
//                return ControleurUtilisateur::rediriger("afficherFormulaireCreation");
//            }
//
//            if (!ConnexionUtilisateur::estAdministrateur()) {
//                unset($_REQUEST["estAdmin"]);
//            }
//
//            if (!filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL)) {
//                MessageFlash::ajouter("warning", "Email non valide");
//                return ControleurUtilisateur::rediriger( "afficherFormulaireCreation");
//            }
//
//            $utilisateur = Utilisateur::construireDepuisFormulaire($_REQUEST);
//
//            VerificationEmail::envoiEmailValidation($utilisateur);
//
//            $utilisateurRepository = new UtilisateurRepository();
//            $succesSauvegarde = $utilisateurRepository->ajouter($utilisateur);
//            if ($succesSauvegarde) {
//                MessageFlash::ajouter("success", "L'utilisateur a bien été créé !");
//                return ControleurUtilisateur::rediriger("afficherListeUtilisateur");
//            } else {
//                MessageFlash::ajouter("warning", "Login existant.");
//                return ControleurUtilisateur::rediriger("afficherFormulaireCreation");
//            }
//        } else {
//            MessageFlash::ajouter("danger", "Login, nom, prenom ou mot de passe manquant.");
//            return ControleurUtilisateur::rediriger("afficherFormulaireCreation");
//        }
//    }

    public static function creerDepuisFormulaire(): Response {
        $login = $_POST['login'] ?? null;
        $nom = $_POST['nom'] ?? null;
        $prenom = $_POST['prenom'] ?? null;
        $mdp = $_POST['mdp'] ?? null;
        $mdp2 = $_POST['mdp2'] ?? null;
        $email = $_POST['email'] ?? null;
        //Recupérer les différentes variables (login, mot de passe, adresse mail, données photo de profil...)
        try {
            (new UtilisateurService())->creerUtilisateur($login, $nom, $prenom, $mdp, $mdp2, $email);
        }
        catch(ServiceException $e) {
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

//    public static function mettreAJour(): Response
//    {
//        if (!(isset($_REQUEST['login']) && isset($_REQUEST['prenom']) && isset($_REQUEST['nom'])
//            && isset($_REQUEST['mdp']) && isset($_REQUEST['mdp2']) && isset($_REQUEST['mdpAncien'])
//            && isset($_REQUEST['email'])
//        )) {
//            MessageFlash::ajouter("danger", "Login, nom, prenom, email ou mot de passe manquant.");
//            return ControleurUtilisateur::rediriger("afficherListeUtilisateur");
//        }
//
//        if ($_REQUEST["mdp"] !== $_REQUEST["mdp2"]) {
//            MessageFlash::ajouter("warning", "Mots de passe distincts.");
//            return ControleurUtilisateur::rediriger("afficherFormulaireMiseAJour", ["login" => $_REQUEST["login"]]);
//        }
//
//        if (!(ConnexionUtilisateur::estConnecte($_REQUEST["login"]) || ConnexionUtilisateur::estAdministrateur())) {
//            MessageFlash::ajouter("danger", "La mise à jour n'est possible que pour l'utilisateur connecté ou un administrateur");
//            return ControleurUtilisateur::rediriger( "afficherListeUtilisateur");
//        }
//
//        if (!filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL)) {
//            MessageFlash::ajouter("warning", "Email non valide");
//            return ControleurUtilisateur::rediriger( "afficherFormulaireMiseAJour", ["login" => $_REQUEST["login"]]);
//        }
//
//        $utilisateurRepository = new UtilisateurRepository();
//        /** @var Utilisateur $utilisateur */
//        $utilisateur = $utilisateurRepository->recupererParClePrimaire($_REQUEST['login']);
//
//        if ($utilisateur == null) {
//            MessageFlash::ajouter("danger", "Login inconnu");
//            return ControleurUtilisateur::rediriger("afficherListeUtilisateur");
//        }
//
//        if (!MotDePasse::verifier($_REQUEST["mdpAncien"], $utilisateur->getMdpHache())) {
//            MessageFlash::ajouter("warning", "Ancien mot de passe erroné.");
//            return ControleurUtilisateur::rediriger("afficherFormulaireMiseAJour", ["login" => $_REQUEST["login"]]);
//        }
//
//        $utilisateur->setNom($_REQUEST["nom"]);
//        $utilisateur->setPrenom($_REQUEST["prenom"]);
//        $utilisateur->setMdpHache($_REQUEST["mdp"]);
//
//        if (ConnexionUtilisateur::estAdministrateur()) {
//            $utilisateur->setEstAdmin(isset($_REQUEST["estAdmin"]));
//        }
//
//        if ($_REQUEST["email"] !== $utilisateur->getEmail()) {
//            $utilisateur->setEmailAValider($_REQUEST["email"]);
//            $utilisateur->setNonce(MotDePasse::genererChaineAleatoire());
//
//            VerificationEmail::envoiEmailValidation($utilisateur);
//        }
//
//        $utilisateurRepository->mettreAJour($utilisateur);
//
//        MessageFlash::ajouter("success", "L'utilisateur a bien été modifié !");
//        return ControleurUtilisateur::rediriger("afficherListeUtilisateur");
//    }

    public static function mettreAJour(): Response {
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
        }
        catch(ServiceException $e) {
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

//    public static function connecter(): Response
//    {
//        if (!(isset($_REQUEST['login']) && isset($_REQUEST['mdp']))) {
//            MessageFlash::ajouter("danger", "Login ou mot de passe manquant.");
//            return ControleurUtilisateur::rediriger("afficherFormulaireConnexion");
//        }
//        $utilisateurRepository = new UtilisateurRepository();
//        /** @var Utilisateur $utilisateur */
//        $utilisateur = $utilisateurRepository->recupererParClePrimaire($_REQUEST["login"]);
//
//        if ($utilisateur == null) {
//            MessageFlash::ajouter("warning", "Login inconnu.");
//            return ControleurUtilisateur::rediriger("afficherFormulaireConnexion");
//        }
//
//        if (!MotDePasse::verifier($_REQUEST["mdp"], $utilisateur->getMdpHache())) {
//            MessageFlash::ajouter("warning", "Mot de passe incorrect.");
//            return ControleurUtilisateur::rediriger("afficherFormulaireConnexion");
//        }
//
//        if (!VerificationEmail::aValideEmail($utilisateur)) {
//            MessageFlash::ajouter("warning", "Adresse email non validée.");
//            return ControleurUtilisateur::rediriger( "afficherFormulaireConnexion");
//        }
//
//        ConnexionUtilisateur::connecter($utilisateur->getLogin());
//        MessageFlash::ajouter("success", "Connexion effectuée.");
//        return ControleurUtilisateur::rediriger( "afficherDetailUtilisateur", ["login" => $_REQUEST["login"]]);
//    }

    public function connecter(): Response
    {
        $login = $_POST['login'] ?? null;
        $password = $_POST['password'] ?? null;
        //Recupérer les différentes variables (login, mot de passe)
        try {
            (new UtilisateurService())->connecter($login, $password);
        }
        catch(ServiceException $e) {
            MessageFlash::ajouter("error", $e->getMessage());
            return ControleurUtilisateur::rediriger('afficherFormulaireConnexion');
        }
        MessageFlash::ajouter("success", "Connexion effectuée.");
        return ControleurUtilisateur::rediriger( "afficherDetailUtilisateur", ["login" => $login]);
    }

    public static function deconnecter(): Response
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            MessageFlash::ajouter("danger", "Utilisateur non connecté.");
            return ControleurUtilisateur::rediriger( "afficherListeUtilisateur");
        }
        ConnexionUtilisateur::deconnecter();
        MessageFlash::ajouter("success", "L'utilisateur a bien été déconnecté.");
        return ControleurUtilisateur::rediriger("afficherListeUtilisateur");
    }

    public static function validerEmail()
    {
        if (isset($_REQUEST['login']) && isset($_REQUEST['nonce'])) {
            $succesValidation = VerificationEmail::traiterEmailValidation($_REQUEST["login"], $_REQUEST["nonce"]);

            if (!$succesValidation) {
                MessageFlash::ajouter("warning", "Email de validation incorrect.");
                return ControleurUtilisateur::rediriger("afficherListeUtilisateur");
            }

            $utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($_REQUEST["login"]);
            MessageFlash::ajouter("warning", "Validation d'email réussie");
            return ControleurUtilisateur::rediriger("afficherDetailUtilisateur", ["login" => $_REQUEST["login"]]);
        } else {
            MessageFlash::ajouter("danger", "Login ou nonce manquant.");
            return ControleurUtilisateur::rediriger("afficherListeUtilisateur");
        }
    }


}
