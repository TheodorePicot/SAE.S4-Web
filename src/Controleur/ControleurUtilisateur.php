<?php

namespace App\PlusCourtChemin\Controleur;

use App\PlusCourtChemin\Configuration\Configuration;
use App\PlusCourtChemin\Lib\ConnexionUtilisateurSession;
use App\PlusCourtChemin\Lib\MessageFlash;
use App\PlusCourtChemin\Modele\DataObject\Utilisateur;
use App\PlusCourtChemin\Service\Exception\ServiceException;
use App\PlusCourtChemin\Service\HistoriqueServiceInterface;
use App\PlusCourtChemin\Service\UtilisateurServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class ControleurUtilisateur extends ControleurGenerique
{
    public function __construct(private readonly UtilisateurServiceInterface $utilisateurService,
                                private readonly ConnexionUtilisateurSession $connexionUtilisateur,
                                private readonly HistoriqueServiceInterface $historiqueService,
                                private readonly ConnexionUtilisateurSession $connexionUtilisateurJWT)
    {

    }

    public static function afficherErreur($errorMessage = "", $controleur = ""): Response
    {
        return parent::afficherErreur($errorMessage, "utilisateur");
    }


    public function afficherListe(): Response
    {
        $utilisateurs = $this->utilisateurService->recupererUtilisateur();     //appel au modèle pour gerer la BD

        return ControleurUtilisateur::afficherTwig('utilisateur/list.html.twig', [
            "utilisateurs" => $utilisateurs,
        ]);
    }

    public function afficherDetail($login): Response
    {
//        if (isset($_REQUEST['login'])) {
//            $login = $_REQUEST['login'];
        $utilisateur = $this->utilisateurService->recupererUtilisateurParClePrimaire($login);
        if ($utilisateur === null) {
            MessageFlash::ajouter("warning", "Login inconnu.");
            return ControleurUtilisateur::rediriger("plusCourtChemin");
        } else {
            return ControleurUtilisateur::afficherTwig('utilisateur/read.html.twig', [
                "utilisateur" => $utilisateur
            ]);
        }
//        } else {
//            MessageFlash::ajouter("danger", "Login manquant.");
//            ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
//        }
    }

    public function supprimer($login)
    {
//        if (isset($_REQUEST['login'])) {
//            $login = $_REQUEST['login'];*
        $idUtilisateurConnecte = $this->connexionUtilisateur->getLoginUtilisateurConnecte();
        $deleteSuccessful = $this->utilisateurService->supprimerUtilisateur($login, $idUtilisateurConnecte);
        $utilisateurs = $this->utilisateurService->recupererUtilisateur();
        if ($deleteSuccessful) {
            MessageFlash::ajouter("success", "L'utilisateur a bien été supprimé !");
            return ControleurUtilisateur::rediriger("plusCourtChemin");
        } else {
            MessageFlash::ajouter("warning", "Login inconnu.");
            return ControleurUtilisateur::rediriger("plusCourtChemin");
        }
//        } else {
//            MessageFlash::ajouter("danger", "Login manquant.");
//            ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
//        }
    }


    public static function afficherFormulaireCreation(): Response
    {
        return ControleurUtilisateur::afficherTwig("utilisateur/inscription.html.twig", [
            "method" => Configuration::getDebug() ? "get" : "post",
        ]);
    }

    public static function aPropos(): Response
    {
        return ControleurUtilisateur::afficherTwig("utilisateur/aPropos.html.twig", [

        ]);
    }

    public function vosTrajets(): Response
    {
        $idUtilisateurConnecte = $this->connexionUtilisateur->getLoginUtilisateurConnecte();
        try {
            $trajets = $this->historiqueService->getHistorique($idUtilisateurConnecte);
        } catch (ServiceException $e) {
            MessageFlash::ajouter("error", $e->getMessage());
            return ControleurUtilisateur::rediriger('plusCourtChemin');
        }
        return ControleurUtilisateur::afficherTwig("utilisateur/vosTrajets.html.twig", [
            "trajets" => $trajets]);
    }

    public function afficherFavoris(): Response
    {
        $idUtilisateurConnecte = $this->connexionUtilisateur->getLoginUtilisateurConnecte();
        try {
            $trajets = $this->historiqueService->getFavoris($idUtilisateurConnecte);
        } catch (ServiceException $e) {
            MessageFlash::ajouter("error", $e->getMessage());
            return ControleurUtilisateur::rediriger('plusCourtChemin');
        }
        return ControleurUtilisateur::afficherTwig("utilisateur/favoris.html.twig", [
            "trajets" => $trajets
        ]);
    }

    public function ajouterFavoris($idTrajet) {
        $idUtilisateurConnecte = $this->connexionUtilisateur->getLoginUtilisateurConnecte();


        try {
            $trajets = $this->historiqueService->getHistorique($idUtilisateurConnecte);
            $this->historiqueService->ajouterFavoris($idTrajet);
        } catch (ServiceException $e) {
            MessageFlash::ajouter("error", $e->getMessage());
            return ControleurUtilisateur::rediriger('plusCourtChemin');
        }
        return ControleurUtilisateur::rediriger("trajets", [
            "trajets" => $trajets
        ]);
    }

    public function supprimerFavoris($idTrajet) {
        $idUtilisateurConnecte = $this->connexionUtilisateur->getLoginUtilisateurConnecte();
        try {
            $trajets = $this->historiqueService->getHistorique($idUtilisateurConnecte);
            $this->historiqueService->supprimerFavoris($idTrajet);
        } catch (ServiceException $e) {
            MessageFlash::ajouter("error", $e->getMessage());
            return ControleurUtilisateur::rediriger('plusCourtChemin');
        }
        return ControleurUtilisateur::rediriger("afficherFavoris", [
            "trajets" => $trajets
        ]);
    }


    public function creerDepuisFormulaire(): Response
    {
        $login = $_POST['login'] ?? null;
        $nom = $_POST['nom'] ?? null;
        $prenom = $_POST['prenom'] ?? null;
        $mdp = $_POST['mdp'] ?? null;
        $mdp2 = $_POST['mdp2'] ?? null;
        $email = $_POST['email'] ?? null;
        //Recupérer les différentes variables (login, mot de passe, adresse mail, données photo de profil...)
        try {
            $this->utilisateurService->creerUtilisateur($login, $nom, $prenom, $mdp, $mdp2, $email);
        } catch (ServiceException $e) {
            MessageFlash::ajouter("error", $e->getMessage());
            return ControleurUtilisateur::rediriger('afficherFormulaireCreation');
        }
        MessageFlash::ajouter("success", "L'utilisateur a bien été créé !");
        return ControleurUtilisateur::rediriger("plusCourtChemin");
    }

    public function afficherFormulaireMiseAJour($login): Response
    {
//        if (isset($_REQUEST['login'])) {
//            $login = $_REQUEST['login'];
        /** @var Utilisateur $utilisateur */
        $utilisateur = $this->utilisateurService->recupererUtilisateurParClePrimaire($login);
        if ($utilisateur === null) {
            MessageFlash::ajouter("danger", "Login inconnu.");
            return ControleurUtilisateur::rediriger("plusCourtChemin");
        }
        if (!($this->connexionUtilisateur->estUtilisateur($login) || $this->connexionUtilisateur->estAdministrateur())) {
            MessageFlash::ajouter("danger", "La mise à jour n'est possible que pour l'utilisateur connecté ou un administrateur");
            return ControleurUtilisateur::rediriger("plusCourtChemin");
        }

        $loginHTML = htmlspecialchars($login);
        $prenomHTML = htmlspecialchars($utilisateur->getPrenom());
        $nomHTML = htmlspecialchars($utilisateur->getNom());
        $emailHTML = htmlspecialchars($utilisateur->getEmail());
        return ControleurUtilisateur::afficherTwig('utilisateur/update.html.twig', [
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

    public function mettreAJour(): Response
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
            $this->utilisateurService->mettreAJour($login, $nom, $prenom, $mdp, $mdp2, $mdpAncien, $email);
        } catch (ServiceException $e) {
            MessageFlash::ajouter("error", $e->getMessage());
            return ControleurUtilisateur::rediriger('plusCourtChemin');
        }
        MessageFlash::ajouter("success", "L'utilisateur a bien été modifié !");
        return ControleurUtilisateur::rediriger("plusCourtChemin");
    }

//    public static function afficherFormulaireConnexion(): Response
//    {
//        return ControleurUtilisateur::afficherVue('vueGenerale.php', [
//            "pagetitle" => "Formulaire de connexion",
//            "cheminVueBody" => "utilisateur/formulaireConnexion.php",
//            "method" => Configuration::getDebug() ? "get" : "post",
//        ]);
//    }


    public static function afficherFormulaireConnexion(): Response
    {
        return ControleurUtilisateur::afficherTwig('utilisateur/connexion.html.twig', [

            "method" => Configuration::getDebug() ? "get" : "post",
        ]);
    }

    public function connecter(): Response
    {
        $login = $_POST['login'] ?? null;
        $password = $_POST['mdp'] ?? null;
        try {
            $login = $this->utilisateurService->verifierIdentifiantUtilisateur($login, $password);
        } catch (ServiceException $e) {
            MessageFlash::ajouter("danger", $e->getMessage());
            var_dump($e->getMessage());
            return ControleurUtilisateur::rediriger('afficherFormulaireConnexion');
        }
        $this->connexionUtilisateur->connecter($login);
        $this->connexionUtilisateurJWT->connecter($login);
        MessageFlash::ajouter("success", "Connexion effectuée.");
        return ControleurUtilisateur::rediriger("plusCourtChemin");
    }

//    public function deconnecter(): Response
//    {
//        try {
//            $this->utilisateurService->deconnecter();
//        } catch (ServiceException $e) {
//
//            MessageFlash::ajouter("danger", $e->getMessage());
//            return ControleurUtilisateur::rediriger('afficherListeUtilisateur');
//        }
//        MessageFlash::ajouter("success", "Deconnexion effectuée.");
//        return ControleurUtilisateur::rediriger('afficherListeUtilisateur');
//
//    }

    public function deconnecter(): Response
    {
        if (!$this->connexionUtilisateur->estConnecte()) {
            MessageFlash::ajouter("error", "Utilisateur non connecté.");
            return ControleurUtilisateur::rediriger('afficherListeUtilisateur');
        }
        $this->connexionUtilisateur->deconnecter();
        $this->connexionUtilisateurJWT->deconnecter();
        MessageFlash::ajouter("success", "L'utilisateur a bien été déconnecté.");
        return ControleurUtilisateur::rediriger('afficherListeUtilisateur');
    }

    public function validerEmail(): Response
    {
        $login = $_POST['login'] ?? null;
        $nonce = $_POST['nonce'] ?? null;
        try {
            $this->utilisateurService->validerEmail($login, $nonce);
        } catch (ServiceException $e) {
            MessageFlash::ajouter("danger", $e->getMessage());
            return ControleurUtilisateur::rediriger('plusCourtChemin');
        }
        MessageFlash::ajouter("success", "Validation d'email réussie");
        return ControleurUtilisateur::rediriger("plusCourtChemin", ["login" => $login]);
    }
}