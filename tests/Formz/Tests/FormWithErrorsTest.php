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

        $form->fold(function ($formWithErrors) {
            $errors = $formWithErrors->getErrorsFlat();
            $this->assertEquals('username', $errors[0]->getField());
            $this->assertEquals('password', $errors[1]->getField());
            $this->assertEquals('city', $errors[2]->getField());
            $this->assertEquals('street', $errors[3]->getField());
        }, function ($formData) {
            $this->fail('The form must be invalid here.');
        });
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
                'street' => '',
            ],
        ]);

        $form->fold(function ($formWithErrors) {
            $this->assertEquals('dennis84', $formWithErrors['username']->getValue());
            $this->assertEquals('', $formWithErrors['password']->getValue());
            $this->assertEquals('foo', $formWithErrors['address']['city']->getValue());
            $this->assertEquals('', $formWithErrors['address']['street']->getValue());
        }, function ($formData) {
            $this->fail('The form must be invalid here.');
        });
    }
}