<?php

namespace Formz\Tests;

use Formz\Builder;
use Formz\Field;

class FieldTest extends FormzTestCase
{
    public function test_getName()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
            $builder->embed('address', [
                $builder->field('street'),
            ]),
            $builder->embed('choices', [
                $builder->field('key'),
                $builder->field('value'),
            ])->multiple(),
        ]);

        $this->assertEquals('', $form->getName());
        $this->assertEquals('username', $form['username']->getName());
        $this->assertEquals('address[street]', $form['address']['street']->getName());
    }

    public function test_getChild()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
            $builder->embed('address', [
                $builder->field('street'),
            ]),
        ]);

        $this->assertInstanceOf('Formz\Field', $form->getChild('username'));
        $this->assertEquals('username', $form->getChild('username')->getFieldName());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_getChild_fail()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('username'),
        ]);

        $form->getChild('password');
    }

    public function test_offsetExists()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('username'),
        ]);

        $this->assertTrue(isset($form['username']));
        $this->assertFalse(isset($form['password']));
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function test_offsetSet()
    {
        $field = $this->createField('foo');
        $field['foo'] = 'bar';
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function test_offsetUnset()
    {
        $field = $this->createField('foo');
        unset($field['foo']);
    }
}
