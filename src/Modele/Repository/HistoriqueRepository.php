<?php

namespace App\PlusCourtChemin\Modele\Repository;

use App\PlusCourtChemin\Modele\DataObject\AbstractDataObject;
use App\PlusCourtChemin\Modele\DataObject\Trajet;

class HistoriqueRepository extends AbstractRepository implements HistoriqueRepositoryInterface
{
    public function __construct(ConnexionBaseDeDonneesInterface $connexionBaseDeDonnees)
    {
        parent::__construct($connexionBaseDeDonnees);
    }

    protected function getNomTable(): string
    {
        return 'historique_trajets';
    }

    protected function getNomClePrimaire(): string
    {
        return 'id_trajet';
    }

    protected function getNomsColonnes(): array
    {
        return [
            'login_utilisateur',
            'comm_depart',
            'coords_depart',
            'comm_arrivee',
            'coords_arrivee',
            'distance',
            'coordonnees_du_chemin',
            'date',
            'est_favoris'
        ];
    }

    protected function getNomsColonnesSelect(): array
    {
        return [
            'id_trajet',
            'login_utilisateur',
            'comm_depart',
            'coords_depart',
            'comm_arrivee',
            'coords_arrivee',
            'distance',
            'coordonnees_du_chemin',
            'date',
            'est_favoris'
        ];
    }

    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Trajet(
            $objetFormatTableau["id_trajet"],
            $objetFormatTableau["login_utilisateur"],
            $objetFormatTableau["comm_depart"],
            $objetFormatTableau["coords_depart"],
            $objetFormatTableau["comm_arrivee"],
            $objetFormatTableau["coords_arrivee"],
            $objetFormatTableau["distance"],
            $objetFormatTableau["coordonnees_du_chemin"],
            $objetFormatTableau["date"],
            $objetFormatTableau["est_favoris"]
        );

    }

    public function recupererPar(array $critereSelection, $limit = 10): array
    {
        $nomTable = $this->getNomTable();
        $champsSelect = implode(", ", $this->getNomsColonnesSelect());

        $partiesWhere = array_map(function ($nomcolonne) {
            return "$nomcolonne = :$nomcolonne";
        }, array_keys($critereSelection));
        $whereClause = join(',', $partiesWhere);

        $requeteSQL = <<<SQL
            SELECT $champsSelect FROM $nomTable WHERE $whereClause ORDER BY date DESC LIMIT $limit;
        SQL;
        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($requeteSQL);
        $pdoStatement->execute($critereSelection);

        $objets = [];
        foreach ($pdoStatement as $objetFormatTableau) {
            $objets[] = $this->construireDepuisTableau($objetFormatTableau);
        }

        return $objets;
    }

    public function ajouterFavoris(int $idTrajet): void
    {
        $nomTable = $this->getNomTable();

        $sql = "UPDATE historique_trajets SET est_favoris = true WHERE id_trajet = :id_trajet";
        // Préparation de la requête
        $req_prep = $this->connexionBaseDeDonnees->getPDO()->prepare($sql);

        $req_prep->execute(["id_trajet" => $idTrajet]);

        return;
    }

    public function supprimerFavoris(int $idTrajet)
    {
        $nomTable = $this->getNomTable();

        $sql = "UPDATE historique_trajets SET est_favoris = false WHERE id_trajet = :id_trajet";
        // Préparation de la requête
        $req_prep = $this->connexionBaseDeDonnees->getPDO()->prepare($sql);

        $req_prep->execute(["id_trajet" => $idTrajet]);
    }

    public function recupererFavorisPar(array $critereSelection, $limit = 10): array
    {
        $nomTable = $this->getNomTable();
        $champsSelect = implode(", ", $this->getNomsColonnesSelect());

        $partiesWhere = array_map(function ($nomcolonne) {
            return "$nomcolonne = :$nomcolonne";
        }, array_keys($critereSelection));
        $whereClause = join(',', $partiesWhere);

        $requeteSQL = <<<SQL
            SELECT $champsSelect FROM $nomTable WHERE $whereClause and est_favoris = true ORDER BY date DESC LIMIT $limit;
        SQL;
        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($requeteSQL);
        $pdoStatement->execute($critereSelection);

        $objets = [];
        foreach ($pdoStatement as $objetFormatTableau) {
            $objets[] = $this->construireDepuisTableau($objetFormatTableau);
        }

        return $objets;
    }

}