<?php

namespace Positibe\Bundle\ClassificationBundle\Form\DataTransformer;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Form\DataTransformerInterface;

use Positibe\Bundle\ClassificationBundle\Model\TagInterface;

/**
 * Class TagsToStringTransformer
 * @package Positibe\Bundle\Bundle\Form\DataTransformer
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class TagsToStringTransformer implements DataTransformerInterface
{
    private $tagRepository;
    private $class;
    private $options;

    public function __construct(EntityRepository $tagRepository, $class, array $options = array())
    {
        $this->tagRepository = $tagRepository;
        $this->class = $class;
        $this->options = $options;
    }


    /**
     * @param mixed $string
     * @return mixed|null
     */
    public function reverseTransform($string)
    {
        if ($string === "") {
            return null;
        }

        $array = explode(',', $string);

        $tags = array();

        foreach ($array as $item) {
            $item = trim($item);
            if ($item !== '') {

                $options = array_merge($this->options, array('name' => $item));
                $tag = $this->findTag($options);

                if ($tag !== null) {
                    !isset($tags[$item]) ? $tags[$item] = $tag : null;
                } else {
                    $tags[$item] = $this->createNew($options);
                }
            }
        }

        return $tags;

    }

    /**
     * @param mixed $tags
     * @return mixed|string
     */
    public function transform($tags)
    {
        /** @var PersistentCollection $tags */
        if ($tags === null) {
            return "";
        }

        /** @var TagInterface $tag */
        $string = implode(', ', $tags->getValues());

        return $string;
    }

    /**
     * @param array $options
     * @return null|TagInterface
     */
    private function findTag(array $options = array())
    {
        return $this->tagRepository->findOneBy($options);
    }

    /**
     * @param array $options
     * @return TagInterface
     */
    private function createNew(array $options)
    {
        /** @var TagInterface $tag */
        $tag = new $this->class;
        $tag->setName($options['name']);
        return $tag;
    }


}