<?php

declare(strict_types=1);

namespace App\Entity\Map;

enum SnapshotType: string
{
    /**
     * Type of sum cell snapshot.
     */
    case Sum = 'sum';

    /**
     * Type of diff cell snapshot.
     */
    case Diff = 'diff';
}
