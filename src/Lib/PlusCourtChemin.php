<?php

namespace App\PlusCourtChemin\Lib;

use App\PlusCourtChemin\Modele\DataObject\NoeudRoutier;
use App\PlusCourtChemin\Modele\Repository\NoeudRoutierRepository;

class PlusCourtChemin
{
    private array $distances;
    private array $noeudsALaFrontiere;

    public function __construct(
        private int $noeudRoutierDepartGid,
        private int $noeudRoutierArriveeGid
    )
    {
    }

    public function calculer(bool $affichageDebug = false): float
    {
        $this->tousLesNoeudsRoutiers = (new NoeudRoutierRepository)->getTousLesVoisins();
        $this->priorityQueue = new PlusCourtCheminPriorityQueue();

        $this->distances = [$this->noeudRoutierDepartGid => 0];
        $this->priorityQueue->insert($this->noeudRoutierDepartGid, 0);

        $this->noeudsALaFrontiere[$this->noeudRoutierDepartGid] = true;

        while (count($this->noeudsALaFrontiere) !== 0) {
            $noeudRoutierGidCourant = $this->priorityQueue->extract();
            if ($noeudRoutierGidCourant === $this->noeudRoutierArriveeGid) {
                var_dump($i);
                return $this->distances[$noeudRoutierGidCourant];
            }

            // Enleve le noeud routier courant de la frontiere
            unset($this->noeudsALaFrontiere[$noeudRoutierGidCourant]);

            /** @var NoeudRoutier $noeudRoutierCourant */
            $voisins = $this->tousLesNoeudsRoutiers[$noeudRoutierGidCourant]; // TODO Deux appelles aux PDO inutile il suffit de faire un appel au getVoisins !
            foreach ($voisins as $voisin) {
                $noeudVoisinGid = $voisin["noeud_routier_gid"];
                $distanceTroncon = $voisin["longueur"];
                $distanceProposee = $this->distances[$noeudRoutierGidCourant] + $distanceTroncon;

                if (!isset($this->distances[$noeudVoisinGid]) || $distanceProposee < $this->distances[$noeudVoisinGid]) {
                    $this->priorityQueue->insert($voisin["noeud_routier_gid"], $distanceProposee);
                    $this->distances[$noeudVoisinGid] = $distanceProposee;
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
            if ($this->distances[$noeudRoutierGid] < $distanceMinimale) {
                $noeudRoutierDistanceMinimaleGid = $noeudRoutierGid;
                $distanceMinimale = $this->distances[$noeudRoutierGid];
            }
        }
        return $noeudRoutierDistanceMinimaleGid;
    }
}
