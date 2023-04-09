<?php

namespace App\PlusCourtChemin\Controleur;

use App\PlusCourtChemin\Lib\MessageFlash;
use App\PlusCourtChemin\Lib\PlusCourtCheminAStar;
use App\PlusCourtChemin\Modele\DataObject\NoeudCommune;
use App\PlusCourtChemin\Service\NoeudCommuneServiceInterface;
use App\PlusCourtChemin\Service\NoeudRoutierServiceInterface;
use PHPUnit\Util\Json;
use Symfony\Component\HttpFoundation\Response;

class ControleurNoeudCommune extends ControleurGenerique
{

    public function __construct(
        private readonly NoeudCommuneServiceInterface $noeudCommuneService,
        private readonly NoeudRoutierServiceInterface $noeudRoutierService
    )
    {
    }


    public static function afficherErreur($errorMessage = "", $controleur = ""): Response
    {
        return parent::afficherErreur($errorMessage, "noeudCommune");
    }


    public function afficherListe(): Response
    {
//        if (!empty($_POST)) {
//            $nomCommuneRecherche = $_POST["nomCommuneDepart"];
//
//            $noeudsCommunes = $this->noeudCommuneService->recupererNoeudCommune();     //appel au modèle pour gerer la BD
            /** @var NoeudCommune $noeudCommuneDepart */
//            $noeudCommuneRecherche = $this->noeudCommuneService->recupererNoeudCommunePar(["nom_comm" => $nomCommuneRecherche])[0];
            return ControleurNoeudCommune::afficherTwig('noeudCommune/list.html.twig', [
//                "noeudsCommunes" => $noeudsCommunes,

            ]);
//        }
    }

    public function afficherDetail($gid): Response
    {
//        if (!isset($_REQUEST[$gid'])) {
//            MessageFlash::ajouter("danger", "Immatriculation manquante.");
//            ControleurNoeudCommune::rediriger("noeudCommune", "afficherListe");
//        }

//        $gid = $_REQUEST['gid'];
        $noeudCommune = $this->noeudCommuneService->recupererNoeudCommuneParClePrimaire($gid);

        if ($noeudCommune === null) {
            MessageFlash::ajouter("warning", "gid inconnue.");
            return ControleurNoeudCommune::rediriger("afficherListeCommune");
        }

        return ControleurNoeudCommune::afficherVue('vueGenerale.php', [
            "noeudCommune" => $noeudCommune,
            "pagetitle" => "Détail de la noeudCommune",
            "cheminVueBody" => "noeudCommune/detail.php"
        ]);
    }

    public function plusCourtChemin(): Response
    {
        $parametres = [];
//        var_dump($_REQUEST);
//        var_dump($_POST);


        if (!empty($_POST)) {
            $nomCommuneDepart = $_POST["nomCommuneDepart"];
            $nomCommuneArrivee = $_POST["nomCommuneArrivee"];

            /** @var NoeudCommune $noeudCommuneDepart */
            $noeudCommuneDepart = $this->noeudCommuneService->recupererNoeudCommunePar(["nom_comm" => $nomCommuneDepart])[0];
            /** @var NoeudCommune $noeudCommuneArrivee */
            $noeudCommuneArrivee = $this->noeudCommuneService->recupererNoeudCommunePar(["nom_comm" => $nomCommuneArrivee])[0];

            $noeudRoutierDepartGid = $this->noeudRoutierService->recupererNoeudRoutierPar([
                "id_rte500" => $noeudCommuneDepart->getId_nd_rte()
            ])[0]->getGid();
            $noeudRoutierArriveeGid = $this->noeudRoutierService->recupererNoeudRoutierPar([
                "id_rte500" => $noeudCommuneArrivee->getId_nd_rte()
            ])[0]->getGid();

            $pcc = new PlusCourtCheminAStar($noeudRoutierDepartGid, $noeudRoutierArriveeGid, $this->noeudRoutierService);
            $distance = $pcc->getDistanceFinale();
            $parametres["coordonneesChemin"] = json_encode($pcc->getCoordonneesDuChemin());
            $parametres["coordonneesDepart"] = json_encode($pcc->getCoordsDepart());
            $parametres["coordonneesArrivee"] = json_encode($pcc->getCoordsArrivee());
            $parametres["nomCommuneDepart"] = $nomCommuneDepart;
            $parametres["nomCommuneArrivee"] = $nomCommuneArrivee;
            $parametres["distance"] = $distance;
        }

        return ControleurNoeudCommune::afficherTwig('noeudCommune/plusCourtChemin.html.twig', $parametres);
    }

    public function autoCompletion($lettre): Response
    {
        $resultat = $this->noeudCommuneService->getVillesAutoCompletion($lettre);
        return new Response(json_encode($resultat));
    }
}
