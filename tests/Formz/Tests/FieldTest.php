<?php

namespace Formz\Tests;

use Formz\Builder;
use Formz\Field;

class FieldTest extends FormzTestCase
{
    public function testGetName()
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

        $this->assertSame('', $form->getName());
        $this->assertSame('username', $form['username']->getName());
        $this->assertSame('address[street]', $form['address']['street']->getName());
    }

    public function testGetChild()
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
        $this->assertSame('username', $form->getChild('username')->getFieldName());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetChildFail()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('username'),
        ]);

        $form->getChild('password');
    }

    public function testOffsetExists()
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
    public function testOffsetSet()
    {
        $field = $this->createField('foo');
        $field['foo'] = 'bar';
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testOffsetUnset()
    {
        $field = $this->createField('foo');
        unset($field['foo']);
    }
}
