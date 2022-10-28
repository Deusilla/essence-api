<?php

declare(strict_types=1);

namespace App\Doctrine\Type\Enum;

use App\Entity\Map\SnapshotType;

final class SnapshotTypeType extends AbstractEnumType
{
    /**
     * @var string
     */
    public const NAME = 'enum_snapshot_type';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @return class-string
     */
    public static function getEnumsClass(): string
    {
        return SnapshotType::class;
    }
}
