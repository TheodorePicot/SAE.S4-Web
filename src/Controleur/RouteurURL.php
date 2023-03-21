<?php
namespace App\PlusCourtChemin\Controleur;

use Symfony\Component\HttpFoundation\Request;

class RouteurURL
{
    public static function traiterRequete() {


        $requete = Request::createFromGlobals();
    }
}