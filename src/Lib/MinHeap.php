<?php

namespace App\PlusCourtChemin\Lib;

class MinHeap extends \SplMinHeap
{
    public function compare($a, $b): int
    {
        echo "-----------------------";
        var_dump($a, $b);
        var_dump($a[0] <=> $b[0]);
        echo "-----------------------";
        if ($a[1] === $b[1]) {
            return 0;
        }
        return $a[0] <=> $b[0];
    }
}