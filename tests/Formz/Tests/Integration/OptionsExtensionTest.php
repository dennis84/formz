<?php

namespace Formz\Tests\Integration;

use Formz\Builder;

class OptionsExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function test_options()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('email')->options([
                'foo@example.org',
                'bar@example.org',
                'baz@example.org',
            ]),
        ]);

        $form->bind([ 'email' => 'foo@example.org' ]);

        $this->assertTrue($form->isValid());
        $this->assertSame([ 'email' => 'foo@example.org' ], $form->getData());
    }

    public function test_options_mismatch()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('email')->options([
                'foo@example.org',
                'bar@example.org',
                'baz@example.org',
            ]),
        ]);

        $form->bind([ 'email' => 'biz@example.org' ]);

        $this->assertFalse($form->isValid());
    }

    public function test_multiple_options()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('emails')->options([
                'foo@example.org',
                'bar@example.org',
                'baz@example.org',
            ])->multiple(),
        ]);

        $form->bind([
            'emails' => [
                'foo@example.org',
                'bar@example.org',
            ]
        ]);

        $this->assertTrue($form->isValid());
    }
    
    public function test_multiple_options_mismatch()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('emails')->options([
                'foo@example.org',
                'bar@example.org',
                'baz@example.org',
            ])->multiple(),
        ]);

        $form->bind([
            'emails' => [
                'foo@example.org',
                'bar@example.org',
                'biz@example.org',
            ]
        ]);

        $this->assertFalse($form->isValid());
    }
    
    public function test_options_with_other_extensions()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('integer')->options([ 1, 2, 3 ])->integer(),
            $builder->field('float')->options([ 1.1, 2.2, 3.3 ])->float(),
        ]);

        $form->bind([
            'integer' => '1',
            'float' => '1.1',
        ]);

        $this->assertSame(1, $form->getData()['integer']);
        $this->assertSame(1.1, $form->getData()['float']);
    }
}