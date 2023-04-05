<?php

namespace App\PlusCourtChemin\Controleur;

use App\PlusCourtChemin\Lib\MessageFlash;
use App\PlusCourtChemin\Lib\PlusCourtChemin;
use App\PlusCourtChemin\Lib\PlusCourtCheminAStar;

use App\PlusCourtChemin\Modele\DataObject\NoeudCommune;
use App\PlusCourtChemin\Modele\Repository\NoeudCommuneRepository;
use App\PlusCourtChemin\Modele\Repository\NoeudRoutierRepository;
use App\PlusCourtChemin\Service\NoeudCommuneService;
use Symfony\Component\HttpFoundation\Response;

class ControleurNoeudCommune extends ControleurGenerique
{

    public static function afficherErreur($errorMessage = "", $controleur = ""): Response
    {
        parent::afficherErreur($errorMessage, "noeudCommune");
    }

    public static function afficherListe(): Response
    {
        $noeudsCommunes = (new NoeudCommuneService())->recuperer();     //appel au modèle pour gerer la BD
        return ControleurNoeudCommune::afficherVue('vueGenerale.php', [
            "noeudsCommunes" => $noeudsCommunes,
            "pagetitle" => "Liste des Noeuds Routiers",
            "cheminVueBody" => "noeudCommune/liste.php"
        ]);
    }

    public static function afficherDetail($gid): Response
    {
//        if (!isset($_REQUEST['gid'])) {
//            MessageFlash::ajouter("danger", "Immatriculation manquante.");
//            ControleurNoeudCommune::rediriger("noeudCommune", "afficherListe");
//        }

//        $gid = $_REQUEST['gid'];
        $noeudCommune = (new NoeudCommuneRepository())->recupererParClePrimaire($gid);

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

    public static function plusCourtChemin(): Response
    {
        $parametres = [
            "pagetitle" => "Plus court chemin",
            "cheminVueBody" => "noeudCommune/plusCourtChemin.php",
        ];
//        var_dump($_REQUEST);
//        var_dump($_POST);


        if (!empty($_POST)) {
            $nomCommuneDepart = $_POST["nomCommuneDepart"];
            $nomCommuneArrivee = $_POST["nomCommuneArrivee"];
            echo "what" . $_POST["nomCommuneArrivee"];


            $noeudCommuneRepository = new NoeudCommuneRepository();
            /** @var NoeudCommune $noeudCommuneDepart */
            var_dump( $noeudCommuneRepository->recupererPar(["nom_comm" => $nomCommuneDepart]));
            $noeudCommuneDepart = $noeudCommuneRepository->recupererPar(["nom_comm" => $nomCommuneDepart])[0];
            /** @var NoeudCommune $noeudCommuneArrivee */

            var_dump( $noeudCommuneRepository->recupererPar(["nom_comm" => $nomCommuneArrivee]));
            $noeudCommuneArrivee = $noeudCommuneRepository->recupererPar(["nom_comm" => $nomCommuneArrivee])[0];

            $noeudRoutierRepository = new NoeudRoutierRepository();
            $noeudRoutierDepartGid = $noeudRoutierRepository->recupererPar([
                "id_rte500" => $noeudCommuneDepart->getId_nd_rte()
            ])[0]->getGid();
            $noeudRoutierArriveeGid = $noeudRoutierRepository->recupererPar([
                "id_rte500" => $noeudCommuneArrivee->getId_nd_rte()
            ])[0]->getGid();

            $pcc = new PlusCourtCheminAStar($noeudRoutierDepartGid, $noeudRoutierArriveeGid);
            $distance = $pcc->calculer();

            $parametres["nomCommuneDepart"] = $nomCommuneDepart;
            $parametres["nomCommuneArrivee"] = $nomCommuneArrivee;
            $parametres["distance"] = $distance;
        }

        return ControleurNoeudCommune::afficherVue('vueGenerale.php', $parametres);
    }

    public static function autoCompletion($lettre): Response
    {
        $noeudCommuneRepository = new NoeudCommuneRepository();
        $resultat = $noeudCommuneRepository->getVillesAutoCompletion($lettre);
        return new Response(json_encode($resultat));
    }
}
