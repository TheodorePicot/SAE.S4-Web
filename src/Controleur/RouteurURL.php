<?php
namespace App\PlusCourtChemin\Controleur;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use App\PlusCourtChemin\Controleur\ControleurUtilisateur;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;


class RouteurURL
{
    public static function traiterRequete() {

        $requete = Request::createFromGlobals();
//        var_dump($requete->getPathInfo());

        $routes = new RouteCollection();

        // Route plusCourtChemin
        $route = new Route("/", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurNoeudCommune::plusCourtChemin",
        ]);
        $routes->add("plusCourtChemin", $route);

        // Route afficherFormulaireConnexion
        $route = new Route("/connexion", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::afficherFormulaireConnexion",
        ]);
        $route->setMethods(["GET"]);
        $routes->add("afficherFormulaireConnexion", $route);

        // Route connecter
        $route = new Route("/connexion", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::connecter",
        ]);
        $route->setMethods(["POST"]);
        $routes->add("connecter", $route);

        // Route deconnecter
        $route = new Route("/deconnexion", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::deconnecter",
        ]);
        $route->setMethods(["GET"]);
        $routes->add("deconnecter", $route);

        // Route afficherFormulaireCreation
        $route = new Route("/inscription", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::afficherFormulaireCreation",
        ]);
        $route->setMethods(["GET"]);
        $routes->add("afficherFormulaireCreation", $route);

        // Route creerDepuisFormulaire
        $route = new Route("/inscription", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::creerDepuisFormulaire",
        ]);
        $route->setMethods(["POST"]);
        $routes->add("creerDepuisFormulaire", $route);

        // Route afficherListeUtilisateur
        $route = new Route("/listeUtilisateur", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::afficherListe",
        ]);
        $routes->add("afficherListeUtilisateur", $route);

        // Route afficherDetailUtilisateur
        $route = new Route("/detailUtilisateur/{login}", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::afficherDetail",
        ]);
        $route->setMethods(["GET"]);
        $routes->add("afficherDetailUtilisateur", $route);

        // Route supprimer
        $route = new Route("/supprimer", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::supprimer",
        ]);
        $routes->add("supprimer", $route);

        // Route afficherFormulaireMiseAJour
        $route = new Route("/miseAJour", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::afficherFormulaireMiseAJour",
        ]);
        $route->setMethods(["GET"]);
        $routes->add("afficherFormulaireMiseAJour", $route);

        // Route mettreAJour
        $route = new Route("/miseAJour", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::mettreAJour",
        ]);
        $route->setMethods(["POST"]);
        $routes->add("mettreAJour", $route);

        // Route validerEmail
        $route = new Route("/validerEmail", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::validerEmail",
        ]);
        $routes->add("validerEmail", $route);

        // Route afficherListeCommune
        $route = new Route("/listeCommune", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurNoeudCommune::afficherListe",
        ]);
        $routes->add("afficherListeCommune", $route);

        // Route afficherDetailCommune
        $route = new Route("/detailCommune/{gid}", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurNoeudCommune::afficherDetail",
        ]);
        $route->setMethods(["GET"]);
        $routes->add("afficherDetailCommune", $route);


        $contexteRequete = (new RequestContext())->fromRequest($requete);
//        var_dump($contexteRequete);

        $associateurUrl = new UrlMatcher($routes, $contexteRequete);
        $donneesRoute = $associateurUrl->match($requete->getPathInfo());
//        var_dump($donneesRoute);

        $requete->attributes->add($donneesRoute);

        $resolveurDeControleur = new ControllerResolver();
        $controleur = $resolveurDeControleur->getController($requete);

        $resolveurDArguments = new ArgumentResolver();
        $arguments = $resolveurDArguments->getArguments($requete, $controleur);


        call_user_func_array($controleur, $arguments);



    }
}