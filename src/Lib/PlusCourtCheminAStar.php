<?php

namespace App\PlusCourtChemin\Lib;

use App\PlusCourtChemin\Service\NoeudRoutierServiceInterface;

class PlusCourtCheminAStar
{
    private float $distanceFinale;
    private array $coordonneesDuChemin;
    private array $coordsArrivee;
    private array $coordsDepart;


    public function __construct(
        private int                                   $noeudRoutierDepartGid,
        private int                                   $noeudRoutierArriveeGid,
        private readonly NoeudRoutierServiceInterface $noeudRoutierService
    )
    {
        $this->calculer();
    }

    public function calculer(bool $affichageDebug = false): void
    {
        //vérifie si le cache contient les noeuds routiers, sinon récupération des noeuds routiers et stock dans le cache 5 minutes

        $this->tousLesVoisins = $this->noeudRoutierService->getTousLesVoisinsV2();

        //initialisation de la liste de tous les voisins, de la priorityQueue qui contient les noeuds à la frontière, le tableau distance indexé par le gid qui donne la distance de chaque noeud par rapport au
        // noeud de départ, et le predecesseurs de chaque noeud

        $this->priorityQueue = new PlusCourtCheminPriorityQueue();
        $this->distances = [$this->noeudRoutierDepartGid => 0];
        $this->predecesseurs = [];
//        $noeudRoutierRepository = new NoeudRoutierRepository();

        //recupération de la latitude et longitude des points de départ et arrivés
//        var_dump($this->tousLesVoisins);

        $this->coordsArrivee = [
            "lng" => (float) $this->tousLesVoisins[$this->noeudRoutierArriveeGid][0]["longitude_base"],
            "lat" => (float) $this->tousLesVoisins[$this->noeudRoutierArriveeGid][0]["latitude_base"]
        ];
        $this->coordsDepart = [
            "lng" => (float) $this->tousLesVoisins[$this->noeudRoutierDepartGid][0]["longitude_base"],
            "lat" => (float) $this->tousLesVoisins[$this->noeudRoutierDepartGid][0]["latitude_base"]
        ];


        // on insère dans la pq le noeud de départ avec sa distance heuristique
        $this->priorityQueue->insert($this->noeudRoutierDepartGid, $this->calculDistanceHeuristque($this->coordsDepart["lng"], $this->coordsDepart["lat"], $this->coordsArrivee["lng"], $this->coordsArrivee["lat"]));

        //Il initialise le tableau des nœuds à la frontière avec le nœud de départ.
        $this->noeudsALaFrontiere[$this->noeudRoutierDepartGid] = true;


        //tant qu'il reste des personnes à la frontière
        while (count($this->noeudsALaFrontiere) !== 0) {

            //le noeud Routier courant est le premier noeud dans le tas, celui avec la plus petite distance heuristique
            $noeudRoutierGidCourant = $this->priorityQueue->extract();

            //quand on trouve l'arrivé, fin de boucle
            if ($noeudRoutierGidCourant === $this->noeudRoutierArriveeGid) {
                $this->distanceFinale = $this->distances[$noeudRoutierGidCourant];
                $this->coordonneesDuChemin = array_map(function ($i) {
                    $nr = $this->tousLesVoisins[$i];
                    return [
                        'lat' => (float) $nr[0]["latitude_base"],
                        'lng' => (float) $nr[0]["longitude_base"]

                    ];
                }, $this->retracerChemin());
                break;
            }

            //on retire le noeud actuel des noeuds à explorer
            unset($this->noeudsALaFrontiere[$noeudRoutierGidCourant]);

            $voisins = $this->tousLesVoisins[$noeudRoutierGidCourant]; // TODO Deux appelles aux PDO inutile il suffit de faire un appel au getVoisins !

            //pour chaque voisin du noeud courant, calcul de la distance à partir du noeud courant et mise à jour des informations des distances et du prédecesseur pour ce voisin
            foreach ($voisins as $voisin) {
                $noeudVoisinGid = $voisin["noeud_routier_gid"];
                $distanceTroncon = $voisin["longueur"];
                $distanceProposee = $this->distances[$noeudRoutierGidCourant] + $distanceTroncon;

                //soit on n'a jamais vu le voisin, soit la distance est meilleur
                if (!isset($this->distances[$noeudVoisinGid]) || $distanceProposee < $this->distances[$noeudVoisinGid]) {
                    $this->distances[$noeudVoisinGid] = $distanceProposee;
                    $distanceHeuristique = $distanceProposee + ($this->calculDistanceHeuristque($voisin['longitude_voisin'], $voisin['latitude_voisin'], $this->coordsArrivee['lng'], $this->coordsArrivee['lat']));
                    $this->priorityQueue->insert($noeudVoisinGid, $distanceHeuristique);
                    $this->predecesseurs[$noeudVoisinGid] = $noeudRoutierGidCourant;
                    $this->noeudsALaFrontiere[$noeudVoisinGid] = true;
                }
            }
        }
    }

    private function retracerChemin(): array
    {
        $chemin = [];
        $noeudRoutierGidCourant = $this->noeudRoutierArriveeGid;

        while ($noeudRoutierGidCourant != $this->noeudRoutierDepartGid) {
            $chemin[] = $noeudRoutierGidCourant;
            $noeudRoutierGidCourant = $this->predecesseurs[$noeudRoutierGidCourant];
        }

        $chemin[] = $this->noeudRoutierDepartGid;
        return array_reverse($chemin);
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

    public function getDistanceFinale()
    {
        return $this->distanceFinale;
    }

    /**
     * @return array
     */
    public function getCoordonneesDuChemin(): array
    {
        return $this->coordonneesDuChemin;
    }

    /**
     * @return array
     */
    public function getCoordsArrivee(): array
    {
        return $this->coordsArrivee;
    }

    /**
     * @return array
     */
    public function getCoordsDepart(): array
    {
        return $this->coordsDepart;
    }
}
