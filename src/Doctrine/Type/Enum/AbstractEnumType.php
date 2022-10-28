<?php

declare(strict_types=1);

namespace App\Doctrine\Type\Enum;

use BackedEnum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use LogicException;

abstract class AbstractEnumType extends StringType
{
    /**
     * {@inheritdoc}
     */
    final public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return mixed
     */
    final public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        return null;
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return mixed
     */
    final public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        if (enum_exists($this->getEnumsClass(), true) === false) {
            throw new LogicException('This class should be an enum');
        }

        return [$this::getEnumsClass(), 'tryFrom']($value);
    }

    /**
     * @return class-string
     */
    abstract public static function getEnumsClass(): string;
}
