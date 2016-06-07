<?php

namespace SymfonyBundles\ForkBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SymfonyBundlesForkBundle extends Bundle
{

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new DependencyInjection\ForkExtension();
    }

}
