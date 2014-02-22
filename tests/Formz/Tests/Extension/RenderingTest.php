<?php

namespace Formz\Tests\Extension;

use Formz\Builder;

class RenderingTest extends \PHPUnit_Framework_TestCase
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

        $this->assertSame('', $form->getName());
        $this->assertSame('username', $form['username']->getName());
        $this->assertSame('address[street]', $form['address']['street']->getName());
        $this->assertSame('choices[]', $form['choices']->getName());

        $form->bind(['choices' => [
            ['key' => 'foo', 'value' => 'bar'],
            ['key' => 'bla', 'value' => 'blubb'],
        ]]);

        $this->assertSame('choices[0][key]', $form['choices']['0']['key']->getName());
        $this->assertSame('choices[0][value]', $form['choices']['0']['value']->getName());
        $this->assertSame('choices[1][key]', $form['choices']['1']['key']->getName());
        $this->assertSame('choices[1][value]', $form['choices']['1']['value']->getName());
    }
}
