<?php

namespace App\PlusCourtChemin\Modele\DataObject;

use App\PlusCourtChemin\Modele\Repository\NoeudRoutierRepository;

class NoeudRoutierWrapper
{
    private int $noeudRoutierGid;
    private float $distance;

    private float $longitude;

    private float $latitude;

    public function __construct(int $noeudRoutierGid, float $distance) {
        $this->noeudRoutierGid = $noeudRoutierGid;
        $this->distance = $distance;
        $longitudeLatitude = (new NoeudRoutierRepository)->getLongitudeLatitude($noeudRoutierGid);
        $this->longitude = $longitudeLatitude[0];
        $this->latitude = $longitudeLatitude[1];
    }
}