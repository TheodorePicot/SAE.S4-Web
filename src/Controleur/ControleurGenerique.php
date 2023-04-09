<?php

namespace App\PlusCourtChemin\Controleur;

use App\PlusCourtChemin\Lib\Conteneur;
use App\PlusCourtChemin\Lib\MessageFlash;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ControleurGenerique
{
    protected static function afficherVue(string $cheminVue, array $parametres = []): Response
    {
        extract($parametres);
        $messagesFlash = MessageFlash::lireTousMessages();
        ob_start();
        require __DIR__ . "/../vue/$cheminVue";
        $corpsReponse = ob_get_clean();
        return new Response($corpsReponse);
    }

    protected static function rediriger(string $name, ?array $tab = []): RedirectResponse
    {
        $UrlGenerator = Conteneur::recupererService("generateurUrl");

        return new RedirectResponse($UrlGenerator->generate($name, $tab));
    }

    public static function afficherErreur($errorMessage = "", $statusCode = 400): Response
    {
        $reponse = ControleurGenerique::afficherTwig('base.html.twig', [

            "errorMessage" => $errorMessage
        ]);

        $reponse->setStatusCode($statusCode);
        return $reponse;
    }

    protected static function afficherTwig(string $cheminVue, array $parametres = []): Response
    {
        /** @var Environment $twig */
        $twig = Conteneur::recupererService("twig");

        return new Response($twig->render($cheminVue, $parametres));
    }
}

