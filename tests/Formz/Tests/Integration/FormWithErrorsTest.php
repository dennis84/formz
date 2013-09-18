<?php

namespace Formz\Tests\Integration;

use Formz\Builder;

class FormWithErrorsTest extends \PHPUnit_Framework_TestCase
{
    public function test_form_errors()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username')->nonEmptyText(),
            $builder->field('password')->nonEmptyText(),
            $builder->field('address', [
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
        $this->assertSame('username', $errors[0]->getField());
        $this->assertSame('password', $errors[1]->getField());
        $this->assertSame('city', $errors[2]->getField());
        $this->assertSame('street', $errors[3]->getField());
    }

    public function test_form_values()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username')->nonEmptyText(),
            $builder->field('password')->nonEmptyText(),
            $builder->field('address', [
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

        $this->assertSame('dennis84', $form['username']->getValue());
        $this->assertSame('', $form['password']->getValue());
        $this->assertSame('foo', $form['address']['city']->getValue());
        $this->assertSame(null, $form['address']['street']->getValue());
    }
}
