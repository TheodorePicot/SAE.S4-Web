<?php

namespace App\PlusCourtChemin\Modele\DataObject;

class Trajet extends AbstractDataObject
{
    public function __construct(
        private ?int    $idTrajet,
        private string $login,
        private string $comm_depart,
        private string $coords_depart,
        private string $comm_arrivee,
        private string $coords_arrivee,
        private float  $distance,
        private string $coordonneesDuChemin,
        private string $date,
        private ?bool $estFavoris
    )
    {
    }

    public function getIdTrajet(): int
    {
        return $this->idTrajet;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getComm_depart(): string
    {
        return $this->comm_depart;
    }

    public function getCoords_depart(): string
    {
        return $this->coords_depart;
    }

    public function getComm_arrivee(): string
    {
        return $this->comm_arrivee;
    }

    public function getCoords_arrivee(): string
    {
        return $this->coords_arrivee;
    }

    public function getDistance(): float
    {
        return $this->distance;
    }

    public function getCoordonneesDuChemin(): string
    {
        return $this->coordonneesDuChemin;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getEstFavoris(): bool {
        return !($this->estFavoris == null);
    }

    public function exporterEnFormatRequetePreparee(): array
    {
        return [
            'login_utilisateur_tag' => $this->login,
            'comm_depart_tag' => $this->comm_depart,
            'coords_depart_tag' => $this->coords_depart,
            'comm_arrivee_tag' => $this->comm_arrivee,
            'coords_arrivee_tag' => $this->coords_arrivee,
            'distance_tag' => $this->distance,
            'coordonnees_du_chemin_tag' => $this->coordonneesDuChemin,
            'date_tag' => $this->date,
            'est_favoris_tag' => $this->estFavoris
        ];
    }
}