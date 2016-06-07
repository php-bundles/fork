<?php

namespace SymfonyBundles\ForkBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class ForkExtension extends ConfigurableExtension
{

    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $configs, ContainerBuilder $container)
    {
        foreach ($configs['class'] as $name => $class) {
            $parameter = sprintf('sb_fork.class.%s', $name);

            $container->setParameter($parameter, $class);
        }

        $process = new Definition($configs['class']['process']);
        $processRef = new Reference('sb_fork.process');
        $container->setDefinition($processRef, $process);

        $fork = new Definition($configs['class']['fork'], [$processRef]);
        $forkRef = new Reference('sb_fork');
        $container->setDefinition($forkRef, $fork);
        $container->setAlias($configs['service_name'], 'sb_fork');
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'sb_fork';
    }

}
