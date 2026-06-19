<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class DridialaaSyliusSplioExtension extends Extension implements PrependExtensionInterface
{
    private const MIGRATIONS_NAMESPACE = 'Dridialaa\SyliusSplioPlugin\Migrations';

    public function getAlias(): string
    {
        return 'dridialaa_sylius_splio';
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        if ($container->hasExtension('doctrine_migrations')) {
            $container->prependExtensionConfig('doctrine_migrations', [
                'migrations_paths' => [
                    self::MIGRATIONS_NAMESPACE => '@DridialaaSyliusSplioPlugin/src/Migrations',
                ],
            ]);
        }

        if ($container->hasExtension('sylius_labs_doctrine_migrations_extra')) {
            $container->prependExtensionConfig('sylius_labs_doctrine_migrations_extra', [
                'migrations' => [
                    self::MIGRATIONS_NAMESPACE => ['Sylius\Bundle\CoreBundle\Migrations'],
                ],
            ]);
        }
    }
}
