<?php

namespace Formz\Tests;

use Formz\Builder;

class FieldTest extends \PHPUnit_Framework_TestCase
{
    public function test_getName()
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

        $this->assertEquals('username', $form['username']->getName());
        $this->assertEquals('address[street]', $form['address']['street']->getName());
    }
}
