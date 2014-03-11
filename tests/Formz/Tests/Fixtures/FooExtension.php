<?php

namespace Formz\Tests\Fixtures;

use Formz\Field;
use Formz\ExtensionInterface;

class FooExtension implements ExtensionInterface
{
    public function initialize(Field $field)
    {
    }

    public function foo(Field $field)
    {
        return $field;
    }

    public function bar(Field $field, array $arr)
    {
        return $field;
    }

    public function baz()
    {
    }
}
