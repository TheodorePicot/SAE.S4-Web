<?php

namespace App\PlusCourtChemin\Modele\Repository;

use App\PlusCourtChemin\Modele\DataObject\AbstractDataObject;
use App\PlusCourtChemin\Modele\DataObject\NoeudRoutier;
use PDO;

class NoeudRoutierRepository extends AbstractRepository
{

    public function construireDepuisTableau(array $noeudRoutierTableau): NoeudRoutier
    {
        return new NoeudRoutier(
            $noeudRoutierTableau["gid"],
            $noeudRoutierTableau["id_rte500"],
            null
        );
    }

    protected function getNomTable(): string
    {
        return 'noeud_routier';
    }

    protected function getNomClePrimaire(): string
    {
        return 'gid';
    }

    protected function getNomsColonnes(): array
    {
        return ["gid", "id_rte500"];
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

    /**
     * Renvoie le tableau des voisins d'un noeud routier
     *
     * Chaque voisin est un tableau avec les 3 champs
     * `noeud_routier_gid`, `troncon_gid`, `longueur`
     *
     * @param int $noeudRoutierGid
     * @return String[][]
     **/
    public function getVoisins(int $noeudRoutierGid): array
    {
        // TODO Vue materialisée pour stocker les voisins
        // TODO index sur l'id du noeud du quel on veut trouver les voisins
        // TODO
        $requeteSQL = <<<SQL
            select noeud_routier_gid, troncon_gid, st_x(coordonnees_voisin) as longitude, st_y(coordonnees_voisin) as latitude, longueur from voisins where noeud_routier_base =:gidTag;
        SQL;
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($requeteSQL);
        $array = [
            "gidTag" => $noeudRoutierGid
        ];
        $pdoStatement->execute($array);
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTousLesVoisins(): array
    {
        // TODO Vue materialisée pour stocker les voisins
        // TODO index sur l'id du noeud du quel on veut trouver les voisins
        // TODO
        $requeteSQL = <<<SQL
            select noeud_routier_base, noeud_routier_gid, troncon_gid,  st_x(coordonnees_voisin) as longitude, st_y(coordonnees_voisin) as latitude, longueur from voisins;
        SQL;
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query($requeteSQL);

        return $pdoStatement->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);
    }

    public function getLongitudeLatitude(int $noeudRoutierGid): array
    {
        $requeteSQL = <<<SQL
            (select st_x(geom), st_y(geom)
                from view_gid_geom_routier
                where gid = :gidTag
            );
        SQL;
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($requeteSQL);
        $pdoStatement->execute(array(
            "gidTag" => $noeudRoutierGid
        ));
        return $pdoStatement->fetch(PDO::FETCH_ASSOC);
    }
}
