<?php
namespace App\PlusCourtChemin\Controleur;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use App\PlusCourtChemin\Lib\Conteneur;


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
        $route = new Route("/supprimer/{login}", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::supprimer",
        ]);
        $routes->add("supprimer", $route);

        // Route afficherFormulaireMiseAJour
        $route = new Route("/miseAJour/{login}", [
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

        // Route d'autocomplétion des communes
        $route = new Route("/autocompletion/{lettre}", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurNoeudCommune::autoCompletion",
        ]);
        $routes->add("afficherDetailCommune", $route);

        $contexteRequete = (new RequestContext())->fromRequest($requete);
//        var_dump($contexteRequete);

        try {
            // 3 méthodes qui lèvent des exceptions
            $associateurUrl = new UrlMatcher($routes, $contexteRequete);
            $donneesRoute = $associateurUrl->match($requete->getPathInfo());
//        @throws NoConfigurationException  If no routing configuration could be found
//        @throws ResourceNotFoundException If the resource could not be found
//        @throws MethodNotAllowedException If the resource was found but the request method is not allowed

            $requete->attributes->add($donneesRoute);

            $resolveurDeControleur = new ControllerResolver();
            $controleur = $resolveurDeControleur->getController($requete);
//        @throws \LogicException If a controller was found based on the request but it is not callable

            $resolveurDArguments = new ArgumentResolver();
            $arguments = $resolveurDArguments->getArguments($requete, $controleur);
//        @throws \RuntimeException When no value could be provided for a required argument

        } catch (ResourceNotFoundException $exception) {
            echo "a";
            $reponse = ControleurGenerique::afficherErreur($exception->getMessage(), 404);
            $reponse->send();
        } catch (MethodNotAllowedException $exception) {
            echo "b";
            $reponse = ControleurGenerique::afficherErreur($exception->getMessage(), 405);
            $reponse->send();
        } catch (Exception $exception) {
            echo "c";
            $reponse = ControleurGenerique::afficherErreur($exception->getMessage()) ;
            $reponse->send();
        }

        $generateurUrl = new UrlGenerator($routes, $contexteRequete);
        $assistantUrl = new UrlHelper(new RequestStack(), $contexteRequete);

        Conteneur::ajouterService("generateurUrl", $generateurUrl);
        Conteneur::ajouterService("assistantUrl", $assistantUrl);


        $reponse = call_user_func_array($controleur, $arguments);
        $reponse->send();



    }
}