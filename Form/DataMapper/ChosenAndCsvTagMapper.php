<?php

namespace Positibe\Bundle\ClassificationBundle\Form\DataMapper;

use Symfony\Component\Form\Extension\Core\DataMapper\PropertyPathMapper;

/**
 * Class TagDataMapper
 * @package Positibe\Bundle\ClassificationBundle\Form\DataMapper
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class ChosenAndCsvTagMapper extends PropertyPathMapper
{
    public function mapFormsToData($forms, &$data)
    {
        parent::mapFormsToData($forms, $data);
        //Agrega la lista de elementos simples extraidos del csv y lo agregas al ArrayCollection
        foreach ($data['csv'] as $simple) {
            $data[] = $simple;
        }

        //Elimina los type de formulario `tags` y `csv` para devolver solo los elementos encontrados
        $data->remove('tags');
        $data->remove('csv');
    }

    public function mapDataToForms($data, $forms)
    {
        //Agrega todos los objetos de data a un elemento `tags` para que sea cargado en este campo del formulario
        $data = array('tags' => $data);
        parent::mapDataToForms($data, $forms);
    }
} 