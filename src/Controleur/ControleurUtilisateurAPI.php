<?php

namespace App\PlusCourtChemin\Controleur;

use App\PlusCourtChemin\Lib\ConnexionUtilisateurInterface;
use App\PlusCourtChemin\Service\Exception\ServiceException;
use App\PlusCourtChemin\Service\UtilisateurServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ControleurUtilisateurAPI
{
    public function __construct(
        private readonly UtilisateurServiceInterface $utilisateurService,
        private readonly ConnexionUtilisateurInterface $connexionUtilisateur,
    )
    {}

    public function afficherDetail($login): Response
    {
        try {
            $utilisateur = $this->utilisateurService->recupererUtilisateurParClePrimaire($login);
            return new JsonResponse($utilisateur, Response::HTTP_OK);
        } catch (ServiceException $exception) {
            return new JsonResponse(["error" => $exception->getMessage()], $exception->getCode());
        }
    }

    public function connecter(Request $request): Response
    {
        try {
            // TODO : Récupération du login et mot de passe
            // depuis le corps de requête au format JSON
            $string = $request->getContent();
            $json = json_decode($string, flags: JSON_THROW_ON_ERROR);
            $login = $json->login ?? "null";
            $password = $json->password ?? "null";


            $idUtilisateur = $this->utilisateurService->verifierIdentifiantUtilisateur($login, $password);
            // TODO : Appel du service connexionUtilisateur
            // pour connecter l'utilisateur avec son identifiant
            $this->connexionUtilisateur->connecter($idUtilisateur);
            return new JsonResponse();
        } catch (ServiceException $exception) {
            return new JsonResponse(["error" => $exception->getMessage()], $exception->getCode());
        } catch (\JsonException $exception) {
            return new JsonResponse(
                ["error" => "Corps de la requête mal formé"],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}



