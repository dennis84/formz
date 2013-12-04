<?php

namespace Formz\Tests\Extension;

use Formz\Builder;

class HelpersTest extends \PHPUnit_Framework_TestCase
{
    public function testGetName()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
            $builder->field('address', [
                $builder->field('street'),
            ]),
            $builder->field('choices', [
                $builder->field('key'),
                $builder->field('value'),
            ])->multiple(),
        ]);

        $this->assertSame('', $form->name());
        $this->assertSame('username', $form['username']->name());
        $this->assertSame('address[street]', $form['address']['street']->name());
    }
}
