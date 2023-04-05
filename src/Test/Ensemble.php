<?php

namespace App\PlusCourtChemin\Test;

use Exception;

class Ensemble {

    private array $tableauEnsemble;

    public function __construct() {
        $this->tableauEnsemble = [];
    }

    public function contient($valeur) {
        return in_array($valeur, $this->tableauEnsemble);
    }

    public function ajouter($valeur) {
        if(!$this->contient($valeur)) {
            $this->tableauEnsemble[] = $valeur;
        }
    }

    public function getTaille() {
        return count($this->tableauEnsemble);
    }

    public function estVide() {
        return $this->getTaille() == 0;
    }

    public function pop() {
        if($this->estVide()) {
            throw new Exception("L'ensemble est vide!");
        }
        return array_pop($this->tableauEnsemble);
    }
}