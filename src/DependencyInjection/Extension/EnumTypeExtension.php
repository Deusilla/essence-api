<?php

declare(strict_types=1);

namespace App\DependencyInjection\Extension;

use App\Doctrine\Type\Enum\AbstractEnumType;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class EnumTypeExtension extends Extension
{
    /**
     * @var string
     */
    public const ENUM_TYPE = 'app.doctrine.enum_type';

    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $container
            ->registerForAutoconfiguration(AbstractEnumType::class)
            ->addTag(self::ENUM_TYPE);
    }
}
