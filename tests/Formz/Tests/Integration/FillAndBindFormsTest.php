<?php

namespace Formz\Tests\Integration;

use Formz\Builder;
use Formz\Tests\Fixtures\User;
use Formz\Tests\Fixtures\Address;
use Formz\Tests\Fixtures\Location;

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
}
