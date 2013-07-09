<?php

namespace Formz\Tests\Integration;

use Formz\Builder;
use Formz\Field;
use Formz\ExtensionInterface;

class ExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function test_call_a_method()
    {
        $builder = new Builder([
            new FooExtension(),
        ]);

        $form = $builder->form([
            $builder->field('username')->foo(),
            $builder->field('username')->bar([]),
        ]);
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function test_call_a_non_existing_method()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username')->foo(),
        ]);
    }
}

class FooExtension implements ExtensionInterface
{
    public function foo()
    {
    }

    public function bar(Field $field, array $arr)
    {
    }
}
