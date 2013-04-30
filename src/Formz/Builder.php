<?php

namespace Formz;

/**
 * This is a helper to build field objects.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Builder
{
    /**
     * Use this function to build the root form.
     *
     * @param Closure $apply   The apply function
     * @param Closure $unapply The unapply function
     *
     * @return Field
     */
    public function form(array $fields, \Closure $apply = null, \Closure $unapply = null)
    {
        return $this->embed('', $fields, $apply, $unapply);
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
        $form = $this->createField($name);

        foreach ($fields as $field) {
            $field->setParent($form);
            $form->addChild($field);
        }

        if (null !== $apply) {
            $apply = \Closure::bind($apply, $form);
            $form->setApply($apply);
        }

        if (null !== $unapply) {
            $unapply = \Closure::bind($unapply, $form);
            $form->setUnapply($unapply);
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
        return $this->createField($name);
    }

    /**
     * Creates a new field object.
     *
     * @param string $name The field name
     *
     * @return Field
     */
    protected function createField($name)
    {
        return new Field($name);
    }
}
