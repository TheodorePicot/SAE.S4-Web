<?php

namespace App\PlusCourtChemin\Modele\Repository;

use PDO;

interface ConnexionBaseDeDonneesInterface
{
    public function getPdo(): PDO;
}