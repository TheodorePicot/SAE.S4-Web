<?php
namespace App\PlusCourtChemin\Controleur;

use App\PlusCourtChemin\Configuration\ConfigurationBDDPostgreSQL;
use App\PlusCourtChemin\Lib\ConnexionUtilisateur;
use App\PlusCourtChemin\Modele\Repository\ConnexionBaseDeDonnees;
use App\PlusCourtChemin\Modele\Repository\NoeudCommuneRepository;
use App\PlusCourtChemin\Modele\Repository\NoeudRoutierRepository;
use App\PlusCourtChemin\Modele\Repository\UtilisateurRepository;
use App\PlusCourtChemin\Service\NoeudCommuneService;
use App\PlusCourtChemin\Service\NoeudRoutierService;
use App\PlusCourtChemin\Service\UtilisateurService;
use Exception;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\HttpKernel\Controller\ContainerControllerResolver;
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
        $routes = new RouteCollection();

        // Route plusCourtChemin
        $route = new Route("/", [
            "_controller" => "noeud_commune_controleur::plusCourtChemin",
        ]);
        $routes->add("plusCourtChemin", $route);

        // Route afficherFormulaireConnexion
        $route = new Route("/connexion", [
            "_controller" => "utilisateur_controleur::afficherFormulaireConnexion",
        ]);
        $route->setMethods(["GET"]);
        $routes->add("afficherFormulaireConnexion", $route);

        // Route connecter
        $route = new Route("/connexion", [
            "_controller" => "utilisateur_controleur::connecter",
        ]);
        $route->setMethods(["POST"]);
        $routes->add("connecter", $route);

        // Route deconnecter
        $route = new Route("/deconnexion", [
            "_controller" => "utilisateur_controleur::deconnecter",
        ]);
        $route->setMethods(["GET"]);
        $routes->add("deconnecter", $route);

        // Route afficherFormulaireCreation
        $route = new Route("/inscription", [
            "_controller" => "utilisateur_controleur::afficherFormulaireCreation",
        ]);
        $route->setMethods(["GET"]);
        $routes->add("afficherFormulaireCreation", $route);

        // Route creerDepuisFormulaire
        $route = new Route("/inscription", [
            "_controller" => "utilisateur_controleur::creerDepuisFormulaire",
        ]);
        $route->setMethods(["POST"]);
        $routes->add("creerDepuisFormulaire", $route);

        // Route afficherListeUtilisateur
        $route = new Route("/listeUtilisateur", [
            "_controller" => "utilisateur_controleur::afficherListe",
        ]);
        $routes->add("afficherListeUtilisateur", $route);

        // Route afficherDetailUtilisateur
        $route = new Route("/detailUtilisateur/{login}", [
            "_controller" => "utilisateur_controleur::afficherDetail",
        ]);
        $route->setMethods(["GET"]);
        $routes->add("afficherDetailUtilisateur", $route);

        // Route supprimer
        $route = new Route("/supprimer/{login}", [
            "_controller" => "utilisateur_controleur::supprimer",
        ]);
        $routes->add("supprimer", $route);

        // Route afficherFormulaireMiseAJour
        $route = new Route("/miseAJour/{login}", [
            "_controller" => "utilisateur_controleur::afficherFormulaireMiseAJour",
        ]);
        $route->setMethods(["GET"]);
        $routes->add("afficherFormulaireMiseAJour", $route);

        // Route mettreAJour
        $route = new Route("/miseAJour", [
            "_controller" => "utilisateur_controleur::mettreAJour",
        ]);
        $route->setMethods(["POST"]);
        $routes->add("mettreAJour", $route);

        // Route validerEmail
        $route = new Route("/validerEmail", [
            "_controller" => "utilisateur_controleur::validerEmail",
        ]);
        $routes->add("validerEmail", $route);

        // Route afficherListeCommune
        $route = new Route("/listeCommune", [
            "_controller" => "noeud_commune_controleur::afficherListe",
        ]);
        $routes->add("afficherListeCommune", $route);

        // Route afficherDetailCommune
        $route = new Route("/detailCommune/{gid}", [
            "_controller" => "noeud_commune_controleur::afficherDetail",
        ]);
        $route->setMethods(["GET"]);
        $routes->add("afficherDetailCommune", $route);

        // Route d'autocomplétion des communes
        $route = new Route("/autocompletion/{lettre}", [
            "_controller" => "noeud_commune_controleur::autoCompletion",
        ]);
        $routes->add("autocompletion", $route);

//        var_dump($contexteRequete);

        $conteneur = new ContainerBuilder();

        $conteneur->register('config_bdd', ConfigurationBDDPostgreSQL::class);

        $connexionBaseService = $conteneur->register('connexion_base', ConnexionBaseDeDonnees::class);
        $connexionBaseService->setArguments([new Reference('config_bdd')]);

        $connexionBaseService = $conteneur->register('connexion_utilisateur', ConnexionUtilisateur::class);
        $connexionBaseService->setArguments([new Reference('config_bdd')]);

        $publicationsRepositoryService = $conteneur->register('noeud_commune_repository',NoeudCommuneRepository::class);
        $publicationsRepositoryService->setArguments([new Reference('connexion_base')]);

        $noeudRoutierRepositoryService = $conteneur->register('noeud_routier_repository',NoeudRoutierRepository::class);
        $noeudRoutierRepositoryService->setArguments([new Reference('connexion_base')]);

        $utilisateurRepositoryService = $conteneur->register('utilisateur_repository',UtilisateurRepository::class);
        $utilisateurRepositoryService->setArguments([new Reference('connexion_base')]);

        $publicationService = $conteneur->register('noeud_commune_service', NoeudCommuneService::class);
        $publicationService->setArguments([new Reference('noeud_commune_repository')]);

        $noeudRoutierService = $conteneur->register('noeud_routier_service', NoeudRoutierService::class);
        $noeudRoutierService->setArguments([new Reference('noeud_routier_repository')]);

        $noeudRoutierService = $conteneur->register('utilisateur_service', UtilisateurService::class);
        $noeudRoutierService->setArguments([new Reference('utilisateur_repository')]);

        $publicationControleurService = $conteneur->register('noeud_commune_controleur',ControleurNoeudCommune::class);
        $publicationControleurService->setArguments([new Reference('noeud_commune_service'), new Reference('noeud_routier_service')]);

        $publicationControleurService = $conteneur->register('utilisateur_controleur',ControleurUtilisateur::class);
        $publicationControleurService->setArguments([new Reference('utilisateur_service')]);

        try {
            $contexteRequete = (new RequestContext())->fromRequest($requete);
            // 3 méthodes qui lèvent des exceptions
            $generateurUrl = new UrlGenerator($routes, $contexteRequete);
            $assistantUrl = new UrlHelper(new RequestStack(), $contexteRequete);
            Conteneur::ajouterService("generateurUrl", $generateurUrl);
            Conteneur::ajouterService("assistantUrl", $assistantUrl);
//        @throws NoConfigurationException  If no routing configuration could be found
//        @throws ResourceNotFoundException If the resource could not be found
//        @throws MethodNotAllowedException If the resource was found but the request method is not allowed

            $associateurUrl = new UrlMatcher($routes, $contexteRequete);
            $donneesRoute = $associateurUrl->match($requete->getPathInfo());

            $requete->attributes->add($donneesRoute);

            $resolveurDeControleur = new ContainerControllerResolver($conteneur);
            $controleur = $resolveurDeControleur->getController($requete);

//        @throws \LogicException If a controller was found based on the request but it is not callable

            $resolveurDArguments = new ArgumentResolver();
            $arguments = $resolveurDArguments->getArguments($requete, $controleur);
//        @throws \RuntimeException When no value could be provided for a required argument
            $reponse = call_user_func_array($controleur, $arguments);
        } catch (ResourceNotFoundException $exception) {
            $reponse = ControleurGenerique::afficherErreur($exception->getMessage(), 404);
        } catch (MethodNotAllowedException $exception) {
            $reponse = ControleurGenerique::afficherErreur($exception->getMessage(), 405);
        } catch (Exception $exception) {
            $reponse = ControleurGenerique::afficherErreur($exception->getMessage()) ;
        }
        $reponse->send();
    }
}