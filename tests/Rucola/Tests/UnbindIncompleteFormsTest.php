<?php

namespace Rucola\Tests;

use Rucola\Rucola;
use Rucola\Tests\Model\User;
use Rucola\Tests\Model\Address;
use Rucola\Tests\Model\Location;

class UnbindIncompleteFormsTest extends \PHPUnit_Framework_TestCase
{
    public function test_flat_form_unapplied_from_array()
    {
        $rucola = new Rucola();

        $form = $rucola->form([
            $rucola->field('username'),
            $rucola->field('password'),
        ]);

        $form->fill(['username' => 'dennis84']);

        $this->assertEquals('dennis84', $form['username']->getValue());
        $this->assertEquals('', $form['password']->getValue());
    }

    public function test_nested_form_unapplied_from_array()
    {
        $rucola = new Rucola();

        $form = $rucola->form([
            $rucola->field('username'),
            $rucola->field('password'),
            $rucola->embed('address', [
                $rucola->field('city'),
                $rucola->field('street'),
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
