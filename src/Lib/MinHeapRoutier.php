<?php

use App\PlusCourtChemin\Modele\DataObject\NoeudRoutier;

class MinHeapRoutier extends SplMinHeap {
    protected function compare(mixed $value1, mixed $value2)
    {
        return $value1->getDistance() - $value2->getDistance();
    }
}