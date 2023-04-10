<?php

namespace App\PlusCourtChemin\Controleur;

use App\PlusCourtChemin\Lib\ConnexionUtilisateurSession;
use App\PlusCourtChemin\Service\Exception\ServiceException;
use App\PlusCourtChemin\Service\HistoriqueServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ControleurHistoriqueAPI extends ControleurGenerique
{
    public function __construct (
        private readonly HistoriqueServiceInterface $historiqueService,
        private readonly ConnexionUtilisateurSession $connexionUtilisateur
    ) {}

    public function supprimer($idTrajet): Response
    {
        try {
            $loginUtilisateurConnecte = $this->connexionUtilisateur->getLoginUtilisateurConnecte();
            $this->historiqueService->supprimerFavoris($idTrajet);
            return new JsonResponse('', Response::HTTP_OK);
        } catch (ServiceException $exception) {
            return new JsonResponse(["error" => $exception->getMessage()], $exception->getCode());
        }
    }
}
