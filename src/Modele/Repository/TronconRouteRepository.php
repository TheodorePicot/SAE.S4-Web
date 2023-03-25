<?php

namespace App\PlusCourtChemin\Modele\Repository;

use App\PlusCourtChemin\Modele\DataObject\AbstractDataObject;
use App\PlusCourtChemin\Modele\DataObject\TronconRoute;

class TronconRouteRepository extends AbstractRepository
{

    public function construireDepuisTableau(array $noeudRoutierTableau): TronconRoute
    {
        return new TronconRoute(
            $noeudRoutierTableau["gid"],
            $noeudRoutierTableau["id_rte500"],
            $noeudRoutierTableau["sens"],
            $noeudRoutierTableau["num_route"] ?? "",
            (float) $noeudRoutierTableau["longueur"],
            null
        );
    }

    protected function getNomTable(): string
    {
        return 'troncon_route';
    }

    protected function getNomClePrimaire(): string
    {
        return 'gid';
    }

    protected function getNomsColonnes(): array
    {
        return ["gid", "id_rte500", "sens", "num_route", "longueur"];
    }

    // On bloque l'ajout, la màj et la suppression pour ne pas modifier la table
    // Normalement, j'ai restreint l'accès à SELECT au niveau de la BD
    public function supprimer(string $valeurClePrimaire): bool
    {
        return false;
    }

    public function mettreAJour(AbstractDataObject $object): void
    {
        return;
    }

    public function ajouter(AbstractDataObject $object): bool
    {
        return false;
    }

    public function getTousLesTroncons(): array
    {
        // TODO Vue materialisée pour stocker les voisins
        // TODO index sur l'id du noeud du quel on veut trouver les voisins
        // TODO
        $requeteSQL = <<<SQL
            select troncon, noeud_routier_depart, longitude_depart, latitude_depart, 
                   noeud_routier_arrivee,  longitude_arrivee, latitude_arrivee, longueur
            from troncons_depart_arrivee;
        SQL;
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query($requeteSQL);

        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTroncon(int $noeudRoutierGid): array
    {
        // TODO Vue materialisée pour stocker les voisins
        // TODO index sur l'id du noeud du quel on veut trouver les voisins
        // TODO
        $requeteSQL = <<<SQL
            select troncon, noeud_routier_depart, longitude_depart, latitude_depart, 
                   noeud_routier_arrivee,  longitude_arrivee, latitude_arrivee, longueur
            from troncons_depart_arrivee
            where noeud_routier_depart = :gidTag
        SQL;

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($requeteSQL);
        $array = [
            "gidTag" => $noeudRoutierGid
        ];
        $pdoStatement->execute($array);

        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

}
