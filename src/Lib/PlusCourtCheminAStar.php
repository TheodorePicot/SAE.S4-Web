<?php

use App\PlusCourtChemin\Modele\Repository\NoeudRoutierRepository;

class PlusCourtChemin
{
    private array $distances;

    public function __construct(
        private int $noeudRoutierDepartGid,
        private int $noeudRoutierArriveeGid
    )
    {
    }

    public function calculer(bool $affichageDebug = false): float
    {
        $noeudRoutierRepository = new NoeudRoutierRepository();
        $tabArrive = $noeudRoutierRepository->getLongitudeLatitude($this->noeudRoutierArriveeGid);
        //Initialisation d’un tas minimum “noeudsALaFrontière” qui contient les noeuds frontières, qui doivent être évalués
        $noeudsALaFrontiere = new SplMinHeap();

        //Initialisation d’une liste “dejaVu” qui contient les noeuds qui ont déjà étaient évalués
        $dejaVu = [];

            $this->distances = [$this->noeudRoutierDepartGid => 0];

        //Ajout du noeud initiale (départ) dans le tas noeudsALaFrontiere
        $noeudsALaFrontiere->insert($this->noeudRoutierDepartGid);

        while(count($noeudsALaFrontiere) !== 0){

            //Initialisation du noeudCourant et le supprime dans “noeudsALaFrontière”
            $noeudRoutierGidCourant = $noeudsALaFrontiere->extract();

            $dejaVu[] = $noeudRoutierGidCourant;

            if ($noeudRoutierGidCourant === $this->noeudRoutierArriveeGid) {
                return $this->distances[$noeudRoutierGidCourant];
            }

            $noeudRoutierCourant = $noeudRoutierRepository->recupererParClePrimaire($noeudRoutierGidCourant);
            $voisins = $noeudRoutierCourant->getVoisins();
            foreach ($voisins as $voisin){
                if (in_array($voisin, $dejaVu)){
                    continue;
                }
                else{
                    $tab = $noeudRoutierRepository->getLongitudeLatitude($voisin["noeud_routier_gid"]);
                    $distanceHeuristique = $this->calculDistanceHeuristque($tab[0],$tab[1], $tabArrive[0], $tabArrive[1]);
                    $distanceTroncon = $voisin["longueur"];
                    $distanceDepuisDebut = $this->distances[$noeudRoutierGidCourant] + $distanceTroncon;

                    if (!isset($noeudsALaFrontiere[$noeudVoisinGid]) || $distanceProposee < $noeudsALaFrontiere[$noeudVoisinGid]){
                        //add
                        //set totalCost
                    }
                }
            }

        }

    }

    private function calculDistanceHeuristque($lng1, $lat1, $lng2, $lat2): float
    {
            $earth_radius = 6378137;   // Terre = sphère de 6378km de rayon
            $rlo1 = deg2rad($lng1);
            $rla1 = deg2rad($lat1);
            $rlo2 = deg2rad($lng2);
            $rla2 = deg2rad($lat2);
            $dlo = ($rlo2 - $rlo1) / 2;
            $dla = ($rla2 - $rla1) / 2;
            $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo));
            $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
            return ($earth_radius * $d);
    }
}
