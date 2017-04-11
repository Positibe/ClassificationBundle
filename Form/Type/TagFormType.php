<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\ClassificationBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Positibe\Bundle\ClassificationBundle\Form\DataTransformer\TagsToStringTransformer;
use Positibe\Bundle\ClassificationBundle\Form\DataMapper\ChosenAndCsvTagMapper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        $className = $options['class_name'];
        $repository = $this->entityManager->getRepository($className);

        $builder
            ->add(
                $builder->create(
                    'csv',
                    TextType::class,
                    [
                        'required' => false,
                        'label' => 'tag.form.csv_label',

                    ]
                )->addModelTransformer(
                    new TagsToStringTransformer(
                        $repository,
                        $className,
                        isset($options['repository_options']) ? $options['repository_options'] : []
                    )
                )
            )
            ->setDataMapper(new ChosenAndCsvTagMapper());

        $qb = $this->entityManager->createQueryBuilder();

        if ($count = (Integer)$qb->from($className, 'o')->select($qb->expr()->countDistinct('o.id'))->getQuery()
            ->getResult()[0][1]) {
            $builder->add(
                'tags',
                'entity',
                [
                    'class' => $className,
                    'multiple' => true,
                    'attr' => array('class' => 'chosen-select'),
                    'required' => false,
                    'label' => 'tag.form.tags_label',
                ]
            );
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(
                array(
                    'repository_options' => [],
                    'class_name' => null,
                )
            );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'positibe_tag';
    }
} 