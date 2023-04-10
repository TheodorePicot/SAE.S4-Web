<?php

namespace App\PlusCourtChemin\Controleur;

use App\PlusCourtChemin\Configuration\ConfigurationBDDPostgreSQL;
use App\PlusCourtChemin\Lib\ConnexionUtilisateurJWT;
use App\PlusCourtChemin\Lib\ConnexionUtilisateurSession;
use App\PlusCourtChemin\Lib\Conteneur;
use App\PlusCourtChemin\Lib\MessageFlash;
use App\PlusCourtChemin\Lib\VerificationEmail;
use App\PlusCourtChemin\Modele\Repository\ConnexionBaseDeDonnees;
use App\PlusCourtChemin\Modele\Repository\HistoriqueRepository;
use App\PlusCourtChemin\Modele\Repository\NoeudCommuneRepository;
use App\PlusCourtChemin\Modele\Repository\NoeudRoutierRepository;
use App\PlusCourtChemin\Modele\Repository\UtilisateurRepository;
use App\PlusCourtChemin\Service\HistoriqueService;
use App\PlusCourtChemin\Service\NoeudCommuneService;
use App\PlusCourtChemin\Service\NoeudRoutierService;
use App\PlusCourtChemin\Service\UtilisateurService;
use Exception;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ContainerControllerResolver;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;


class RouteurURL
{
    public static function traiterRequete()
    {

        $twigLoader = new FilesystemLoader(__DIR__ . '/../vue/');
        $twig = new Environment(
            $twigLoader,
            [
                'autoescape' => 'html',
                'strict_variables' => true
            ]
        );
        Conteneur::ajouterService("twig", $twig);

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

        // Route aPropos
        $route = new Route("/aPropos", [
            "_controller" => "utilisateur_controleur::aPropos",
        ]);
        $routes->add("aPropos", $route);

        // Route vosTrajets
        $route = new Route("/vosTrajets", [
            "_controller" => "utilisateur_controleur::vosTrajets",
        ]);
        $routes->add("trajets", $route);

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

        // Route d'autocomplétion des communes
        $route = new Route("/autocompletion/{lettre}", [
            "_controller" => "noeud_commune_controleur::autoCompletion",
        ]);
        $routes->add("autocompletion", $route);

    // Route afficher trajet historique
        $route = new Route("/afficherTrajet/{idTrajet}", [
            "_controller" => "noeud_commune_controleur::afficherTrajet",
        ]);
        $routes->add("afficherTrajet", $route);

        // Route afficher trajet favoris
        $route = new Route("/afficherFavoris", [
            "_controller" => "utilisateur_controleur::afficherFavoris",
        ]);
        $routes->add("afficherFavoris", $route);

        $route = new Route("/ajouterFavoris/{idTrajet}", [
            "_controller" => "utilisateur_controleur::ajouterFavoris",
        ]);
        $routes->add("ajouterFavoris", $route);

        $route = new Route("/api/supprimerFavoris/{idTrajet}", [
            "_controller" => "utilisateur_controleur::supprimerFavoris",
        ]);
//        $route->setMethods(["DELETE"]);
        $routes->add("supprimerFavoris", $route);

        $route = new Route("/api/utilisateurs/{login}", [
            "_controller" => "controleur_utilisateur_api::afficherDetail",
        ]);
        $route->setMethods(["GET"]);
        $routes->add("afficherDetailUtilisateurAPI", $route);

        $route = new Route("/api/auth", [
            "_controller" => "controleur_utilisateur_api::connecter",
        ]);
        $route->setMethods(["POST"]);
        $routes->add("connecterAPI", $route);

        $conteneur = new ContainerBuilder();

        $conteneur->register('config_bdd', ConfigurationBDDPostgreSQL::class);


        $connexionBaseService = $conteneur->register('connexion_base', ConnexionBaseDeDonnees::class);
        $connexionBaseService->setArguments([new Reference('config_bdd')]);

        $noeudCommuneRepository = $conteneur->register('noeud_commune_repository', NoeudCommuneRepository::class);
        $noeudCommuneRepository->setArguments([new Reference('connexion_base')]);

        $noeudRoutierRepository = $conteneur->register('noeud_routier_repository', NoeudRoutierRepository::class);
        $noeudRoutierRepository->setArguments([new Reference('connexion_base')]);

        $utilisateurRepository = $conteneur->register('utilisateur_repository', UtilisateurRepository::class);
        $utilisateurRepository->setArguments([new Reference('connexion_base')]);

        $historiqueRepository = $conteneur->register('historique_repository', HistoriqueRepository::class);
        $historiqueRepository->setArguments([new Reference('connexion_base')]);

        $connexionUtilisateur = $conteneur->register('connexion_utilisateur', ConnexionUtilisateurSession::class);
        $connexionUtilisateur->setArguments([new Reference('utilisateur_repository')]);

        $connexionUtilisateurJWT = $conteneur->register('connexion_utilisateur_JWT',ConnexionUtilisateurJWT::class);

        $verificationEmail = $conteneur->register('verification_email', VerificationEmail::class);
        $verificationEmail->setArguments([new Reference('utilisateur_repository')]);

        $noeudCommuneService = $conteneur->register('historique_service', HistoriqueService::class);
        $noeudCommuneService->setArguments([new Reference('historique_repository'), new Reference('connexion_utilisateur')]);

        $noeudCommuneService = $conteneur->register('noeud_commune_service', NoeudCommuneService::class);
        $noeudCommuneService->setArguments([new Reference('noeud_commune_repository')]);

        $noeudRoutierService = $conteneur->register('noeud_routier_service', NoeudRoutierService::class);
        $noeudRoutierService->setArguments([new Reference('noeud_routier_repository')]);

        $utilisateurService = $conteneur->register('utilisateur_service', UtilisateurService::class);
        $utilisateurService->setArguments([new Reference('utilisateur_repository'), new Reference('connexion_utilisateur'), new Reference('verification_email')]);

        $publicationControleurService = $conteneur->register('noeud_commune_controleur', ControleurNoeudCommune::class);
        $publicationControleurService->setArguments([new Reference('noeud_commune_service'), new Reference('noeud_routier_service'), new Reference('historique_service'), new Reference('connexion_utilisateur')]);

        $publicationControleurService = $conteneur->register('utilisateur_controleur', ControleurUtilisateur::class);
        $publicationControleurService->setArguments([new Reference('utilisateur_service'), new Reference('connexion_utilisateur'), new Reference('historique_service'), new Reference('connexion_utilisateur')]);

        $publicationControleurService = $conteneur->register('controleur_historique_api', ControleurHistoriqueAPI::class);
        $publicationControleurService->setArguments([new Reference('historique_service')]);

        $publicationControleurAPIService = $conteneur->register('controleur_utilisateur_api',ControleurUtilisateurAPI::class);
        $publicationControleurAPIService->setArguments([new Reference('utilisateur_service'), new Reference('connexion_utilisateur_JWT')]);
        //        var_dump($contexteRequete);

        $contexteRequete = (new RequestContext())->fromRequest($requete);


        $generateurUrl = new UrlGenerator($routes, $contexteRequete);
        $assistantUrl = new UrlHelper(new RequestStack(), $contexteRequete);
        Conteneur::ajouterService("generateurUrl", $generateurUrl);
        Conteneur::ajouterService("assistantUrl", $assistantUrl);

        //            TWIG Config

        $fonctionAsset = $assistantUrl->getAbsoluteUrl(...);
        $fonctionRoute = $generateurUrl->generate(...);

        $twig->addFunction(new TwigFunction("assets", $fonctionAsset));
        $twig->addFunction(new TwigFunction("route", $fonctionRoute));
        $twig->addGlobal('idUtilisateurCo', $conteneur->get("connexion_utilisateur")->estConnecte());
        $twig->addGlobal('utilisateurLogin', $conteneur->get("connexion_utilisateur")->getLoginUtilisateurConnecte());
        $twig->addGlobal('idUtilisateurAdmin', $conteneur->get("connexion_utilisateur")->estAdministrateur());
        $twig->addGlobal('messagesFlash', new MessageFlash());


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
            $reponse = ControleurGenerique::afficherErreur($exception->getMessage());
        }

//        $reponse = call_user_func_array($controleur, $a rguments);
        $reponse->send();
    }
}