<?php

namespace App\PlusCourtChemin\Lib;

use App\PlusCourtChemin\Modele\DataObject\NoeudRoutier;
use App\PlusCourtChemin\Modele\Repository\NoeudRoutierRepository;
use SplMinHeap;

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
        $this->minHeap = new MinHeap();

        $noeudRoutierRepository = new NoeudRoutierRepository();

        // Distance en km, table indexé par NoeudRoutier::gid
        $this->distances = [$this->noeudRoutierDepartGid => 0];
        $this->minHeap->insert([0, $this->noeudRoutierDepartGid]);

        var_dump($this->noeudRoutierArriveeGid);

        $heap = new SplMinHeap();
        $heap->insert([0.31,333]);
        $heap->insert([0.2,33]);
        $heap->insert([0.6,3]);
        $heap->insert([0.8,3]);
        $heap->insert([0.1,3]);
        $heap->insert([0.81,3]);
        $heap->insert([0.31,3]);

        var_dump($heap);

        $this->noeudsALaFrontiere[$this->noeudRoutierDepartGid] = true;
        $i = 0;

        while (count($this->noeudsALaFrontiere) !== 0) {
            var_dump($this->minHeap);
//            var_dump($this->minHeap->top());
            $thisyyy = $this->minHeap->extract();
            var_dump($thisyyy);
            $noeudRoutierGidCourant = $thisyyy[1];
            if ($i === 100) {
                break;
            }
            $i++;
            // Fini
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
                    $this->minHeap->insert([$distanceProposee, $voisin["noeud_routier_gid"]]);
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
