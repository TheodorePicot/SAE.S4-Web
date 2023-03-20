<?php

namespace App\PlusCourtChemin\Lib;

use SplPriorityQueue;

class PlusCourtCheminPriorityQueue extends SplPriorityQueue
{
    public function compare($priority1, $priority2): int
    {
        if ($priority1 === $priority2) return 0;
        return $priority1 < $priority2 ? 1 : -1;
    }
}