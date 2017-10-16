<?php

namespace SymfonyBundles\ForkBundle\Tests\DependencyInjection;

use SymfonyBundles\ForkBundle\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use SymfonyBundles\ForkBundle\DependencyInjection\ForkExtension;

class ForkExtensionTest extends TestCase
{
    public function testExtension()
    {
        $this->assertInstanceOf(Extension::class, new ForkExtension());
    }

    public function testParameters()
    {
        $extension = new ForkExtension();
        $container = new ContainerBuilder();

        $this->assertFalse($container->has('fork'));
        $this->assertFalse($container->hasParameter('sb_fork.class.fork'));
        $this->assertFalse($container->hasParameter('sb_fork.class.process'));

        $extension->load([], $container);

        $this->assertTrue($container->has('fork'));
        $this->assertTrue($container->hasParameter('sb_fork.class.fork'));
        $this->assertTrue($container->hasParameter('sb_fork.class.process'));
    }

    public function testAlias()
    {
        $extension = new ForkExtension();

        $this->assertStringEndsWith('fork', $extension->getAlias());
    }
}
