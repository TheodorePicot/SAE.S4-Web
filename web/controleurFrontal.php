<?php
ini_set("memory_limit", "10000M");
//use App\PlusCourtChemin\Lib\Psr4AutoloaderClass;

//require_once __DIR__ . '/../src/Lib/Psr4AutoloaderClass.php';

// instantiate the loader
//$loader = new Psr4AutoloaderClass();
// register the base directories for the namespace prefix
//$loader->addNamespace('App\PlusCourtChemin', __DIR__ . '/../src');
// register the autoloader
//$loader->register();
require_once __DIR__ . '/../vendor/autoload.php';

// Syntaxe alternative
// The null coalescing operator returns its first operand if it exists and is not null
//$action = $_REQUEST['action'] ?? 'afficherListe';
//
//$controleur = $_REQUEST['controleur'] ?? "noeudRoutier";
//
//$controleurClassName = 'App\PlusCourtChemin\Controleur\Controleur' . ucfirst($controleur);
//
//if (class_exists($controleurClassName)) {
//    if (in_array($action, get_class_methods($controleurClassName))) {
//        $controleurClassName::$action();
//    } else {
//        $controleurClassName::afficherErreur("Erreur d'action");
//    }
//} else {
//    App\PlusCourtChemin\Controleur\ControleurGenerique::afficherErreur("Erreur de contrôleur");
//}
App\PlusCourtChemin\Controleur\RouteurURL::traiterRequete();
