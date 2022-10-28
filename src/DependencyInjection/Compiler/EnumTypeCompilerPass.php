<?php

declare(strict_types=1);

namespace App\DependencyInjection\Compiler;

use App\DependencyInjection\Extension\EnumTypeExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class EnumTypeCompilerPass implements CompilerPassInterface
{
    private const TAG_CONNECTION_FACTORY_TYPES = 'doctrine.dbal.connection_factory.types';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        $typesDefinition = [];
        if ($container->hasParameter(self::TAG_CONNECTION_FACTORY_TYPES)) {
            /** @var array $typesDefinition */
            $typesDefinition = $container->getParameter(self::TAG_CONNECTION_FACTORY_TYPES);
        }

        $taggedEnums = $container->findTaggedServiceIds(EnumTypeExtension::ENUM_TYPE);

        foreach ($taggedEnums as $enumType => $definition) {
            $typesDefinition[$enumType::NAME] = ['class' => $enumType];
        }
        $container->setParameter(self::TAG_CONNECTION_FACTORY_TYPES, $typesDefinition);
    }
}
