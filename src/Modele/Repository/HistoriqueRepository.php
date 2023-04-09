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
        return 'idTrajet';
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
        ];
    }

    protected function getNomsColonnesSelect(): array
    {
        return [
            'historique_trajets.idTrajet',
            'login_utilisateur',
            'comm_depart',
            'coords_depart',
            'comm_arrivee',
            'coords_arrivee',
            'distance',
            'coordonnees_du_chemin',
            'date',
        ];
    }

    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
         return new Trajet(
            $objetFormatTableau["idTrajet"],
            $objetFormatTableau["loginUtilisateur"],
            $objetFormatTableau["comm_depart"],
            $objetFormatTableau["coords_depart"],
            $objetFormatTableau["comm_arrivee"],
            $objetFormatTableau["coords_arrivee"],
            $objetFormatTableau["distance"],
            $objetFormatTableau["coordonneesDuChemin"],
            $objetFormatTableau["date"],
        );

    }

    public function recupererPar(array $critereSelection, $limit = 200): array
    {
        $nomTable = $this->getNomTable();
        $champsSelect = implode(", ", $this->getNomsColonnesSelect());

        $partiesWhere = array_map(function ($nomcolonne) {
            return "$nomcolonne = :$nomcolonne";
        }, array_keys($critereSelection));
        $whereClause = join(',', $partiesWhere);

        $requeteSQL = <<<SQL
            SELECT $champsSelect FROM $nomTable WHERE $whereClause LIMIT $limit;
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