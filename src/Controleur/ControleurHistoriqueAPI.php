<?php

namespace App\PlusCourtChemin\Controleur;

use App\PlusCourtChemin\Service\Exception\ServiceException;
use App\PlusCourtChemin\Service\HistoriqueServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ControleurHistoriqueAPI extends ControleurGenerique
{
    public function __construct(
        private readonly HistoriqueServiceInterface $historiqueService,
    )
    {
    }

    public function supprimer($idTrajet): Response
    {
        try {
            $this->historiqueService->supprimerFavoris($idTrajet);
            return new JsonResponse('', Response::HTTP_OK);
        } catch (ServiceException $exception) {
            return new JsonResponse(["error" => $exception->getMessage()], $exception->getCode());
        }
    }
}
