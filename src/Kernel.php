<?php

declare(strict_types=1);

namespace App;

use App\DependencyInjection\Compiler\EnumTypeCompilerPass;
use App\DependencyInjection\Extension\EnumTypeExtension;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * @param ContainerBuilder $container
     */
    protected function build(ContainerBuilder $container): void
    {
        parent::build($container);

        foreach ($this->getExtensions() as $extension) {
            $container->registerExtension(new $extension());
        }

        foreach ($this->getCompilerPasses() as $compilerPass) {
            $container->addCompilerPass(new $compilerPass());
        }
    }

    /**
     * @return array<class-string<Extension>>
     */
    private function getExtensions(): array
    {
        return [
            EnumTypeExtension::class,
        ];
    }

    /**
     * @return array<class-string<CompilerPassInterface>>
     */
    private function getCompilerPasses(): array
    {
        return [
            EnumTypeCompilerPass::class,
        ];
    }
}
