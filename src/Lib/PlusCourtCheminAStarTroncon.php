<?php

namespace App\PlusCourtChemin\Lib;

use App\PlusCourtChemin\Modele\Repository\NoeudRoutierRepository;
use App\PlusCourtChemin\Modele\Repository\TronconRouteRepository;

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
        $this->tousLesTroncons = (new TronconRouteRepository())->getTousLesTroncons();
        $this->priorityQueue = new PlusCourtCheminPriorityQueue();
        $this->distances = [$this->noeudRoutierDepartGid => 0];
        $noeudRoutierRepository = new NoeudRoutierRepository();


        $tabArrive = $noeudRoutierRepository->getLongitudeLatitude($this->noeudRoutierArriveeGid);
        $tabDepart = $noeudRoutierRepository->getLongitudeLatitude($this->noeudRoutierDepartGid);

        $this->priorityQueue->insert($this->noeudRoutierDepartGid, $this->calculDistanceHeuristque($tabDepart['st_x'], $tabDepart['st_y'], $tabArrive['st_x'], $tabArrive['st_y']));
        $this->noeudsALaFrontiere[$this->noeudRoutierDepartGid] = true;
        while (count($this->noeudsALaFrontiere) !== 0) {
            $noeudRoutierGidCourant = $this->priorityQueue->extract();

            if ($noeudRoutierGidCourant === $this->noeudRoutierArriveeGid)
                return $this->distances[$noeudRoutierGidCourant];

            unset($this->noeudsALaFrontiere[$noeudRoutierGidCourant]);

            $voisins = $this->tousLesNoeudsRoutiers[$noeudRoutierGidCourant]; // TODO Deux appelles aux PDO inutile il suffit de faire un appel au getVoisins !
            foreach ($voisins as $voisin) {
                $noeudVoisinGid = $voisin["noeud_routier_gid"];
                $distanceTroncon = $voisin["longueur"];
                $distanceProposee = $this->distances[$noeudRoutierGidCourant] + $distanceTroncon;

                if (!isset($this->distances[$noeudVoisinGid]) || $distanceProposee < $this->distances[$noeudVoisinGid]) {
                    $this->distances[$noeudVoisinGid] = $distanceProposee;
                    $distanceHeuristique = $distanceProposee + ($this->calculDistanceHeuristque($voisin['longitude'], $voisin['latitude'], $tabArrive['st_x'], $tabArrive['st_y']));
                    $this->priorityQueue->insert($noeudVoisinGid, $distanceHeuristique);
                    $this->noeudsALaFrontiere[$noeudVoisinGid] = true;
                }
            }
        }
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
