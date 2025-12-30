<?php

namespace Gbtux\InertiaBundle;


use Gbtux\InertiaBundle\Service\Inertia;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class GbtuxInertiaBundle extends AbstractBundle
{

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        // load an XML, PHP or YAML file
        //$loader = new XmlFileLoader($builder, new FileLocator(__DIR__.'/../config'));
        $loader = new YamlFileLoader($builder, new FileLocator(__DIR__.'/../config'));
        $loader->load('services.yaml');
        $definition = $builder->getDefinition(Inertia::class);
        $definition->setArgument('$rootView', $config['root_view']);
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->scalarNode('root_view')->defaultValue('app.html.twig')->end()
            ->end();
        ;
    }

}