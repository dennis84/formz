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

        $this->assertSame('dennis84', $form['username']->getValue());
        $this->assertSame(null, $form['password']->getValue());
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

        $this->assertSame('dennis84', $form['username']->getValue());
        $this->assertSame(null, $form['password']->getValue());
        
        $this->assertSame(null, $form['address']['city']->getValue());
        $this->assertSame('Foostreet', $form['address']['street']->getValue());
    }
}
