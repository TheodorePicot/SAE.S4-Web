<?php

namespace App\PlusCourtChemin\Test;

use App\PlusCourtChemin\Lib\ConnexionUtilisateur;
use App\PlusCourtChemin\Lib\VerificationEmail;
use App\PlusCourtChemin\Modele\DataObject\Utilisateur;
use App\PlusCourtChemin\Modele\Repository\UtilisateurRepositoryInterface;
use App\PlusCourtChemin\Service\UtilisateurService;
use Exception;
use PHPUnit\Framework\TestCase;

class UtilisateurServiceTest extends TestCase
{

    private $service;

    private $connexionUtilisateurMock;

    private $utilisateurRepositoryMock;

    private $verificationEmailMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->utilisateurRepositoryMock = $this->createMock(UtilisateurRepositoryInterface::class);
        $this->connexionUtilisateurMock = $this->createMock(ConnexionUtilisateur::class);
        $this->verificationEmailMock = $this->createMock(VerificationEmail::class);
        $this->service = new UtilisateurService($this->utilisateurRepositoryMock, $this->connexionUtilisateurMock, $this->verificationEmailMock);
    }

    public function testCreerUtilisateurSansPrenom()
    {

        $fakeUtilisateur = $this->createMock(Utilisateur::class);
        $fakeUtilisateur->method("getPrenom")->willReturn("");

        $login = "test";
        $prenom = null;
        $nom = "test";
        $mdp = "12345678";
        $mdp2 = "12345678";
        $email = "test@yopmail.com";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Login, nom, prenom ou mot de passe manquant!");
        $this->service->creerUtilisateur($login, $prenom, $nom, $mdp, $mdp2, $email);
    }


    public function testCreerUtilisateurMdpDistinxts()
    {
        $login = "test";
        $prenom = 'test';
        $nom = "test";
        $mdp = "12345678";
        $mdp2 = "123456789";
        $email = "test@yopmail.com";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Mots de passe distincts!");
        $this->service->creerUtilisateur($login, $prenom, $nom, $mdp, $mdp2, $email);
    }


    public function testCreerUtilisateurEmailInvalide()
    {

        $fakeUtilisateur = $this->createMock(Utilisateur::class);
        $fakeUtilisateur->method("getEmail")->willReturn("testyopmail.com");

        $login = "test";
        $prenom = "test";
        $nom = "test";
        $mdp = "12345678";
        $mdp2 = "12345678";
        $email = "testyopmail.com";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Email non valide!");
        $this->service->creerUtilisateur($login, $prenom, $nom, $mdp, $mdp2, $email);
    }

    public function testCreerUtilisateur()
    {

    }

    public function testMettreAJourSansPrenom()
    {

        $fakeUtilisateur = $this->createMock(Utilisateur::class);
        $fakeUtilisateur->method("getPrenom")->willReturn("");

        $login = "test";
        $prenom = null;
        $nom = "test";
        $mdp = "12345678";
        $mdp2 = "12345678";
        $mdpAncien = "123";
        $email = "test@yopmail.com";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Login, nom, prenom, email ou mot de passe manquant.");
        $this->service->mettreAJour($login, $prenom, $nom, $mdp, $mdp2, $mdpAncien, $email);
    }


    public function testMettreAJourMdpDistinxts()
    {
        $login = "test";
        $prenom = 'test';
        $nom = "test";
        $mdp = "12345678";
        $mdp2 = "123456789";
        $mdpAncien = "123";
        $email = "test@yopmail.com";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Mots de passe distincts.");
        $this->service->mettreAJour($login, $prenom, $nom, $mdp, $mdp2, $mdpAncien, $email);
    }


    public function testMettreAJourEmailInvalid()
    {
        $fakeUtilisateur = $this->createMock(Utilisateur::class);
        $fakeUtilisateur->method("getEmail")->willReturn("testyopmail.com");

        $login = "test";
        $prenom = "test";
        $nom = "test";
        $mdp = "12345678";
        $mdp2 = "12345678";
        $mdpAncien = "12345678";
        $email = "testyopmail.com";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Email non valide");
        $this->service->mettreAJour($login, $prenom, $nom, $mdp, $mdp2, $mdpAncien, $email);
    }

//Todo fonction pas
    public function testMettreAJourUtilisateurInexistant()
    {
        $fakeUtilisateur = $this->createMock(Utilisateur::class);
        $this->utilisateurRepositoryMock->method("recupererParClePrimaire")->willReturn(null);

        $login = "test";
        $prenom = "test";
        $nom = "test";
        $mdp = "12345678";
        $mdp2 = "12345678";
        $mdpAncien = "123";
        $email = "test@yopmail.com";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Login inconnu");
        $this->service->mettreAJour($login, $prenom, $nom, $mdp, $mdp2, $mdpAncien, $email);
    }


//Todo test Mot de passe eroné

//Todo fonctionne pas a cause methode
    public function testMettreAJourPasConnecte()
    {
        $fakeUtilisateur = $this->createMock(Utilisateur::class);
        $this->utilisateurRepositoryMock->method("recupererParClePrimaire")->willReturn($fakeUtilisateur->getLogin());
        $this->connexionUtilisateurMock->method("getLoginUtilisateurConnecte")->willReturn(null);

        $login = "test";
        $prenom = 'test';
        $nom = "test";
        $mdp = "12345678";
        $mdp2 = "12345678";
        $mdpAncien = "123";
        $email = "test@yopmail.com";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("La mise à jour n'est possible que pour l'utilisateur connecté ou un administrateur");
        $this->service->mettreAJour($login, $prenom, $nom, $mdp, $mdp2, $mdpAncien, $email);
    }

    //Todo test mettre a jour
    public function testMettreAJour()
    {

    }

    public function testConnecterSansLogin()
    {
        $fakeUtilisateur = $this->createMock(Utilisateur::class);
        $fakeUtilisateur->method("getLogin")->willReturn("");

        $login = null;
        $mdp = "12345678";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Login ou mot de passe manquant.");
        $this->service->connecter($login, $mdp);
    }

    public function testConecterutilisateurInexistant()
    {
        $fakeUtilisateur = $this->createMock(Utilisateur::class);
        $this->utilisateurRepositoryMock->method("recupererParClePrimaire")->willReturn(null);
        $login = 'test';
        $mdp = "12345678";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Login ou mot de passe manquant.");
        $this->service->connecter($login, $mdp);
    }
}


