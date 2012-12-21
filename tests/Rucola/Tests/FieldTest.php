<?php

namespace Rucola\Tests;

use Rucola\Rucola;

class FieldTest extends \PHPUnit_Framework_TestCase
{
    public function testGetName()
    {
        $rucola = new Rucola();

        $form = $rucola->form(array(
            $rucola->field('username'),
            $rucola->field('password'),
            $rucola->embed('address', array(
                $rucola->field('street'),
            )),
            $rucola->embed('choices', array(
                $rucola->field('key'),
                $rucola->field('value'),
            ))->multiple(),
        ));

        $this->assertEquals('username', $form['username']->getName());
        $this->assertEquals('address[street]', $form['address']['street']->getName());
    }
}
