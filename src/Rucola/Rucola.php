<?php

namespace Rucola;

use Rucola\Util\DataMapper;

/**
 * Rucola. This is a helper to build field objects.
 */
class Rucola
{
    /**
     * Use this function to build the root form.
     *
     * @param Closure $apply   This apply function
     * @param Closure $unapply The unapply function
     *
     * @return Field
     */
    public function form(array $fields, \Closure $apply = null, \Closure $unapply = null)
    {
        $form = $this->embed('', $fields, $apply, $unapply);
        $form->setRoot(true);

        return $form;
    }

    /**
     * Use this function to build embedded forms.
     *
     * @param string  $name    The field name
     * @param Closure $apply   This apply function
     * @param Closure $unapply The unapply function
     *
     * @return Field
     */
    public function embed($name, array $fields, \Closure $apply = null, \Closure $unapply = null)
    {
        $form = new Field($name);

        foreach ($fields as $field) {
            $field->setParent($form);
            $form->addChild($field);
        }

        if (null === $apply) {
            $apply = function () {
                return DataMapper::fieldToArray($this);
            };

            $apply = \Closure::bind($apply, $form);
            $form->setApply($apply);
        } else {
            $form->setApply($apply);
        }

        if (null === $unapply) {
            $form->setUnapply(function ($data) {
                return $data;
            });
        } else {
            $form->setUnapply($unapply);
            $form->setCustomUnapply();
        }

        return $form;
    }



    /**
     * Use this function to define a optional form. This form must not get any
     * data from the client.
     *
     * @param string  $name    The field name
     * @param Closure $apply   This apply function
     * @param Closure $unapply The unapply function
     *
     * return field
     */
    public function optionalEmbed($name, array $fields, \Closure $apply = null, \Closure $unapply = null)
    {
        $form = $this->embed($name, $fields, $apply, $unapply);
        $form->optional();

        return $form;
    }

    /**
     * Use this method to create a single form field.
     *
     * @param string $name The field name
     *
     * @return Field
     */
    public function field($name)
    {
        $field = new Field($name);
        return $field;
    }
}
