<?php

namespace App\PlusCourtChemin\Modele\Repository;

use App\PlusCourtChemin\Modele\DataObject\AbstractDataObject;
use App\PlusCourtChemin\Modele\DataObject\NoeudRoutier;
use PDO;
use Phpfastcache\Helper\Psr16Adapter;

class NoeudRoutierRepository extends AbstractRepository implements NoeudRoutierRepositoryInterface
{

    public function __construct(ConnexionBaseDeDonneesInterface $connexionBaseDeDonnees)
    {
        parent::__construct($connexionBaseDeDonnees);
    }

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
        $requeteSQL = <<<SQL
            select noeud_routier_gid,st_x(coordonnees_base) as longitude_base, st_y(coordonnees_base) as latitude_base, troncon_gid, st_x(coordonnees_voisin) as longitude_voisin, st_y(coordonnees_voisin) as latitude_voisin, longueur from voisins where noeud_routier_base =:gidTag;
        SQL;
        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($requeteSQL);
        $array = [
            "gidTag" => $noeudRoutierGid
        ];
        $pdoStatement->execute($array);
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTousLesVoisins(): array
    {
        $defaultDriver = 'Files';
        $Psr16Adapter = new Psr16Adapter($defaultDriver);
        if (!$Psr16Adapter->has('queried')) {
            $Psr16Adapter->set('queried', 'query', 300);// 5 minutes
            $requeteSQL = <<<SQL
            select noeud_routier_base, noeud_routier_gid,st_x(coordonnees_base) as longitude_base, st_y(coordonnees_base) as latitude_base, troncon_gid, st_x(coordonnees_voisin) as longitude_voisin, st_y(coordonnees_voisin) as latitude_voisin, longueur from voisinsv3;
            SQL;
            $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->query($requeteSQL);
            $fetchResult = $pdoStatement->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);
            $Psr16Adapter->set('tousLesNoeudsRoutiers', $fetchResult, 300);
            return $fetchResult;
        } else {
            // Getter action
            return $Psr16Adapter->get('tousLesNoeudsRoutiers');
        }
    }

    public function getLongitudeLatitude(int $noeudRoutierGid): array
    {
        $requeteSQL = <<<SQL
            (select st_x(geom), st_y(geom)
                from view_gid_geom_routier
                where gid = :gidTag
            );
        SQL;
        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($requeteSQL);
        $pdoStatement->execute(array(
            "gidTag" => $noeudRoutierGid
        ));
        return $pdoStatement->fetch(PDO::FETCH_ASSOC);
    }
}
