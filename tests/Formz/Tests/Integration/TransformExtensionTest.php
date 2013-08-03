<?php

namespace Formz\Tests\Integration;

use Formz\Builder;

class TransformExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function test_transform()
    {
        $builder = new Builder();
        $form = $builder->form([])
            ->transform(new \Formz\Transformer\Integer())
            ->transform(new \Formz\Transformer\Float());

        $this->assertAttributeCount(2, 'transformers', $form);
    }
} 
