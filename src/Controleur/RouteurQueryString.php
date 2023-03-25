<?php

namespace App\PlusCourtChemin\Controleur;

class RouteurQueryString {

    public static function traiterRequete(){
        // Syntaxe alternative
        // The null coalescing operator returns its first operand if it exists and is not null
        $action = $_REQUEST['action'] ?? 'afficherListe';

        $controleur = $_REQUEST['controleur'] ?? "noeudRoutier";

        $controleurClassName = 'App\PlusCourtChemin\Controleur\Controleur' . ucfirst($controleur);

        if (class_exists($controleurClassName)) {
            if (in_array($action, get_class_methods($controleurClassName))) {
                $controleurClassName::$action();
            } else {
                $controleurClassName::afficherErreur("Erreur d'action");
            }
        } else {
            ControleurGenerique::afficherErreur("Erreur de contrôleur");
        }
    }
}
