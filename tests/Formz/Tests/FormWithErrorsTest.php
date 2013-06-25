<?php

namespace Formz\Tests;

use Formz\Builder;

class FormWithErrorsTest extends \PHPUnit_Framework_TestCase
{
    public function test_form_errors()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username')->nonEmptyText(),
            $builder->field('password')->nonEmptyText(),
            $builder->embed('address', [
                $builder->field('city')->nonEmptyText(),
                $builder->field('street')->nonEmptyText(),
            ]),
        ]);

        $form->bind([
            'username' => '',
            'password' => '',
            'address' => [
                'city' => '',
                'street' => '',
            ],
        ]);

        $errors = $form->getErrorsFlat();
        $this->assertEquals('username', $errors[0]->getField());
        $this->assertEquals('password', $errors[1]->getField());
        $this->assertEquals('city', $errors[2]->getField());
        $this->assertEquals('street', $errors[3]->getField());
    }

    public function test_form_values()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username')->nonEmptyText(),
            $builder->field('password')->nonEmptyText(),
            $builder->embed('address', [
                $builder->field('city')->nonEmptyText(),
                $builder->field('street')->nonEmptyText(),
            ]),
        ]);

        $form->bind([
            'username' => 'dennis84',
            'password' => '',
            'address' => [
                'city' => 'foo',
            ],
        ]);

        $this->assertEquals('dennis84', $form['username']->getValue());
        $this->assertSame('', $form['password']->getValue());
        $this->assertEquals('foo', $form['address']['city']->getValue());
        $this->assertSame(null, $form['address']['street']->getValue());
    }
}
