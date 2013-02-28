<?php

namespace Formz\Tests;

use Formz\Builder;
use Formz\Tests\Model\User;
use Formz\Tests\Model\Address;
use Formz\Tests\Model\Location;

class UnbindIncompleteFormsTest extends \PHPUnit_Framework_TestCase
{
    public function test_flat_form_unapplied_from_array()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
        ]);

        $form->fill(['username' => 'dennis84']);

        $this->assertEquals('dennis84', $form['username']->getValue());
        $this->assertEquals('', $form['password']->getValue());
    }

    public function test_nested_form_unapplied_from_array()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
            $builder->embed('address', [
                $builder->field('city'),
                $builder->field('street'),
            ]),
        ]);

        $form->fill([
            'username' => 'dennis84',
            'address' => [
                'street' => 'Foostreet',
            ],
        ]);

        $this->assertEquals('dennis84', $form['username']->getValue());
        $this->assertEquals('', $form['password']->getValue());
        
        $this->assertEquals('', $form['address']['city']->getValue());
        $this->assertEquals('Foostreet', $form['address']['street']->getValue());
    }
}
