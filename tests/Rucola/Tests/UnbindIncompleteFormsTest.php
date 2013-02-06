<?php

namespace Rucola\Tests;

use Rucola\Rucola;
use Rucola\Tests\Model\User;
use Rucola\Tests\Model\Address;
use Rucola\Tests\Model\Location;

class UnbindIncompleteFormsTest extends \PHPUnit_Framework_TestCase
{
    public function testFormWithoutUnapply()
    {
        $rucola = new Rucola();

        $form = $rucola->form([
            $rucola->field('username'),
            $rucola->field('password'),
        ]);

        $form->fill(['username' => 'dennis84']);

        $this->assertEquals('dennis84', $form->getChild('username')->getValue());
        $this->assertEquals('', $form->getChild('password')->getValue());
    }

    public function testNestedFormWithoutUnapply()
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

        $this->assertEquals('dennis84', $form->getChild('username')->getValue());
        $this->assertEquals('', $form->getChild('password')->getValue());
        
        $this->assertEquals('', $form->getChild('address')->getChild('city')->getValue());
        $this->assertEquals('Foostreet', $form->getChild('address')->getChild('street')->getValue());
    }
}
