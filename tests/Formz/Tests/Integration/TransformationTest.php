<?php

namespace Formz\Tests;

use Formz\Builder;
use Formz\Transformer;
use Formz\Tests\Fixtures\NonsenseTransformer;

class TransformationTest extends \PHPUnit_Framework_TestCase
{
    public function test_transform_and_apply_order()
    {
        $test = $this;
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('foo')->integer(),
        ], function ($foo) use ($test) {
            // The apply must come after transformation.
            $test->assertSame(420, $foo);
        });

        $form->transform(new NonsenseTransformer());
        $form->bind([ 'foo' => '42.2' ]);
    }
}
