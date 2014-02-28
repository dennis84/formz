<?php

namespace Formz\Tests\Integration;

use Formz\Builder;

class FillAndBindFormsTest extends \PHPUnit_Framework_TestCase
{
    public function test_fill_and_bind_complete_flat_form_applied_to_array()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
        ]);

        $form->fill([
            'username' => 'dennis84',
            'password' => 'demo123',
        ]);

        $form->bind([
            'username' => 'dennis',
            'password' => 'demo',
        ]);

        $formData = $form->getData();

        $this->assertSame([
            'username' => 'dennis',
            'password' => 'demo',
        ], $formData);
    }

    public function test_fill_and_bind_incomplete_flat_form_applied_to_array()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
        ]);

        $form->fill([
            'username' => 'dennis84',
            'password' => 'demo123',
        ]);

        $form->bind([
            'username' => 'dennis',
        ]);

        $formData = $form->getData();
        $this->assertSame([
            'username' => 'dennis',
            'password' => null,
        ], $formData);
    }

    public function test_transform_single_field()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('birthday', [], function ($value) {
                return new \DateTime($value);
            }, function (\DateTime $date) {
                return $date->format('Y-m-d');
            }),
        ]);

        $form->bind([
            'birthday' => '1984-01-01',
        ]);

        $data = $form->getData();
        $this->assertInstanceOf('DateTime', $data['birthday']);

        $form->fill([ 'birthday' => new \DateTime('1984-01-01') ]);
        $this->assertSame('1984-01-01', $form['birthday']->getValue());
    }
}
