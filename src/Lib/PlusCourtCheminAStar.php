<?php

namespace App\PlusCourtChemin\Lib;

use App\PlusCourtChemin\Modele\DataObject\NoeudRoutier;
use App\PlusCourtChemin\Modele\Repository\NoeudRoutierRepository;

class PlusCourtCheminAStar
{
    public function __construct(
        private int $noeudRoutierDepartGid,
        private int $noeudRoutierArriveeGid
    )
    {
    }

    public function calculer(bool $affichageDebug = false): float
    {
        $this->tousLesNoeudsRoutiers = (new NoeudRoutierRepository)->getTousLesVoisins();

        $noeudRoutierRepository = new NoeudRoutierRepository();

        $tabArrive = $noeudRoutierRepository->getLongitudeLatitude($this->noeudRoutierArriveeGid);


        // Distance en km, table indexé par NoeudRoutier::gid
        $this->distances = [$this->noeudRoutierDepartGid => 0];

        $tabDepart = $noeudRoutierRepository->getLongitudeLatitude($this->noeudRoutierDepartGid);
        $this->distancesHeuristique = [$this->noeudRoutierDepartGid => ($this->calculDistanceHeuristque($tabDepart['st_x'], $tabDepart['st_y'], $tabArrive['st_x'], $tabArrive['st_y']))];


        $this->noeudsALaFrontiere[$this->noeudRoutierDepartGid] = true;
        $i = 0;

        $dejaVu = [];

        while (count($this->noeudsALaFrontiere) !== 0) {
            $noeudRoutierGidCourant = $this->noeudALaFrontiereDeDistanceMinimale();
            $i++;
            // Fini
            if ($noeudRoutierGidCourant === $this->noeudRoutierArriveeGid) {
                var_dump($i);
                return $this->distances[$noeudRoutierGidCourant];
            }

//            $dejaVu[] = $noeudRoutierGidCourant;

            // Enleve le noeud routier courant de la frontiere
            unset($this->noeudsALaFrontiere[$noeudRoutierGidCourant]);
            $dejaVu[] = $noeudRoutierGidCourant;
            /** @var NoeudRoutier $noeudRoutierCourant */
            $voisins = $this->tousLesNoeudsRoutiers[$noeudRoutierGidCourant]; // TODO Deux appelles aux PDO inutile il suffit de faire un appel au getVoisins !
            foreach ($voisins as $voisin) {
//                if (in_array($voisin["noeud_routier_gid"], $dejaVu)) continue;
                $noeudVoisinGid = $voisin["noeud_routier_gid"];
                $distanceTroncon = $voisin["longueur"];

                $distanceProposee = $this->distances[$noeudRoutierGidCourant] + $distanceTroncon;

                if (!isset($this->distances[$noeudVoisinGid]) || $distanceProposee < $this->distances[$noeudVoisinGid]) {
                    $this->distances[$noeudVoisinGid] = $distanceProposee;
                    $distanceHeuristique = $distanceProposee + ($this->calculDistanceHeuristque($voisin['longitude'], $voisin['latitude'], $tabArrive['st_x'], $tabArrive['st_y']));
                    $this->distancesHeuristique[$noeudVoisinGid] = $distanceHeuristique;
                    $this->noeudsALaFrontiere[$noeudVoisinGid] = true;
                }
            }
        }
    }

    private function noeudALaFrontiereDeDistanceMinimale()
    {
        $noeudRoutierDistanceMinimaleGid = -1;
        $distanceMinimale = PHP_INT_MAX;
        foreach ($this->noeudsALaFrontiere as $noeudRoutierGid => $valeur) {
            if ($this->distancesHeuristique[$noeudRoutierGid] < $distanceMinimale) {
                $noeudRoutierDistanceMinimaleGid = $noeudRoutierGid;
                $distanceMinimale = $this->distancesHeuristique[$noeudRoutierGid];
            }
        }
        return $noeudRoutierDistanceMinimaleGid;
    }

    private function calculDistanceHeuristque($lng1, $lat1, $lng2, $lat2): float
    {
        if (($lat1 == $lat2) && ($lng1 == $lng2)) {
            return 0;
        } else {
            $pi80 = M_PI / 180;
            $lat1 *= $pi80;
            $lng1 *= $pi80;
            $lat2 *= $pi80;
            $lng2 *= $pi80;

            $r = 6372.797; // rayon moyen de la Terre en km
            $dlat = $lat2 - $lat1;
            $dlng = $lng2 - $lng1;
            $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin(
                    $dlng / 2) * sin($dlng / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            return $r * $c;
        }
    }
}
