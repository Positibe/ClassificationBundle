<?php

namespace Positibe\Bundle\ClassificationBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class PositibeClassificationBundle
 * @package Positibe\Bundle
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class PositibeClassificationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $modelDir = realpath(__DIR__ . '/Resources/config/doctrine/model');

        $mappings = array(
            $modelDir => 'Positibe\Bundle\ClassificationBundle\Model',
        );

        $ormCompilerClass = 'Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass';

        if (class_exists($ormCompilerClass)) {
            $container->addCompilerPass(
                DoctrineOrmMappingsPass::createXmlMappingDriver(
                    $mappings,
                    array('doctrine.orm.default_entity_manager')
                )
            );
        }

    }
} 