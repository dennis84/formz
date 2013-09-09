<?php

namespace Formz\Tests\Integration;

use Formz\Builder;

class SerializedForm extends \PHPUnit_Framework_TestCase
{
    public function testSerialize()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
        ]);
    }
}
