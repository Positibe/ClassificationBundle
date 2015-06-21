<?php

namespace Positibe\Bundle\ClassificationBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Positibe\Bundle\ClassificationBundle\Form\DataTransformer\TagsToStringTransformer;
use Positibe\Bundle\ClassificationBundle\Form\DataMapper\ChosenAndCsvTagMapper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class TagFormType
 * @package Positibe\Bundle\ClassificationBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class TagFormType extends AbstractType
{
    /**
     * @var EntityRepository
     */
    private $tagRepository;

    public function setTagRepository(EntityRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new TagsToStringTransformer(
            $this->tagRepository, $this->tagRepository->getClassName(),
            isset($options['repository_options']) ? $options['repository_options'] : array()
        );

        $builder
            ->add(
                $builder->create(
                    'csv',
                    'text',
                    array(
                        'required' => false,
                        'label' => 'Separate tags with commas',
                        'translation_domain' => 'BlogBundle',
                        'attr' => array(
                            'class' => 'input-xlarge'
                        )

                    )
                )->addModelTransformer($transformer)
            )
            ->setDataMapper(new ChosenAndCsvTagMapper());

        //@todo Realizar una función en el repositorio para realizar solamente un conteo de tagsy optimzar la consulta
        if (count($this->tagRepository->findAll()) > 0) {
            $builder->add(
                'tags',
                'genemu_jquerychosen_entity',
                array(
                    'class' => $this->tagRepository->getClassName(),
                    'multiple' => true,
                    'attr' => array('class' => 'chosen-select form-control'),
                    'required' => false,
                    'label' => 'Choose from the most used tags'
                )
            );
        }

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
//        $resolver ->setAllowedTypes(array(
//            'repository_options' => 'Array',
//        ));
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