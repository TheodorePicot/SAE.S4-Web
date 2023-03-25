<?php
namespace App\PlusCourtChemin\Controleur;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use App\PlusCourtChemin\Controleur\ControleurUtilisateur;


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
        $routes->add("afficherFormulaireConnexion", $route);

        $contexteRequete = (new RequestContext())->fromRequest($requete);
//        var_dump($contexteRequete);

        $associateurUrl = new UrlMatcher($routes, $contexteRequete);
        $donneesRoute = $associateurUrl->match($requete->getPathInfo());
//        var_dump($donneesRoute);




        call_user_func($donneesRoute["_controller"]);



    }
}