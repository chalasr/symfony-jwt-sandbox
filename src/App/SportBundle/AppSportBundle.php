<?php

namespace App\SportBundle;

use App\Util\DependencyInjection\Compiler\RepositoryCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppSportBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RepositoryCompilerPass());
    }
}
