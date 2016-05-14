<?php

namespace Positibe\Bundle\ClassificationBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Positibe\Bundle\ClassificationBundle\Form\DataTransformer\TagsToStringTransformer;
use Positibe\Bundle\ClassificationBundle\Form\DataMapper\ChosenAndCsvTagMapper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class TagFormType
 * @package Positibe\Bundle\ClassificationBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class TagFormType extends AbstractType
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $repository = $this->entityManager->getRepository($options['class_name']);
        $className = $options['class_name'];


        $transformer = new TagsToStringTransformer(
          $repository,
          $className,
          isset($options['repository_options']) ? $options['repository_options'] : array()
        );

        $builder
          ->add(
            $builder->create(
              'csv',
              'text',
              array(
                'required' => false,
                'label' => 'Separadas por comas',
                'translation_domain' => 'BlogBundle',
                'attr' => array(
                  'class' => 'input-xlarge'
                )

              )
            )->addModelTransformer($transformer)
          )
          ->setDataMapper(new ChosenAndCsvTagMapper());


        $qb = $this->entityManager->createQueryBuilder();

        $count = (Integer)$qb->from(
          $options['class_name'],
          'o'
        )->select($qb->expr()->countDistinct('o.id'))->getQuery()->getResult()[0][1];

        if ($count > 0) {
            $builder->add(
              'tags',
              'entity',
              array(
                'class' => $className,
                'multiple' => true,
                'attr' => array('class' => 'chosen-select form-control'),
                'required' => false,
                'label' => 'Más usadas',
                'empty_value' => '-- Seleccione una etiqueta --'
              )
            );
        }

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
          ->setDefaults(
            array(
              'repository_options' => array(),
              'class_name' => null
            )
          )
          ->setAllowedTypes(
            array(
              'repository_options' => 'Array'
            )
          );
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'positibe_tag';
    }
} 