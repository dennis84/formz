<?php

namespace Rucola\Tests;

use Rucola\Rucola;

class FieldTest extends \PHPUnit_Framework_TestCase
{
    public function test_getName()
    {
        $rucola = new Rucola();

        $form = $rucola->form([
            $rucola->field('username'),
            $rucola->field('password'),
            $rucola->embed('address', [
                $rucola->field('street'),
            ]),
            $rucola->embed('choices', [
                $rucola->field('key'),
                $rucola->field('value'),
            ])->multiple(),
        ]);

        $this->assertEquals('username', $form['username']->getName());
        $this->assertEquals('address[street]', $form['address']['street']->getName());
    }
}
