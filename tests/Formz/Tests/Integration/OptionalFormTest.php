<?php

namespace Formz\Tests\Integration;

use Formz\Builder;
use Formz\Transformer;
use Formz\Tests\Fixtures\Address;
use Formz\Tests\Fixtures\User;
use Formz\Tests\Fixtures\NullToBlahTransformer;

class OptionalFormTest extends \PHPUnit_Framework_TestCase
{
    public function test_bind_complete_nested_form_applied_to_object()
    {
        $builder = new Builder();
        $form = $this->createNestedForm();
        $data = [
            'username' => 'dennis84',
            'password' => 'demo123',
            'address' => [
                'city' => 'foo',
                'street' => 'bar',
            ],
        ];

        $form->bind($data);
        $formData = $form->getData();

        $this->assertInstanceOf('Formz\Tests\Fixtures\User', $formData);
        $this->assertSame('dennis84', $formData->username);
        $this->assertSame('demo123', $formData->password);

        $this->assertInstanceOf('Formz\Tests\Fixtures\Address', $formData->address);
        $this->assertSame('foo', $formData->address->city);
        $this->assertSame('bar', $formData->address->street);
        $this->assertTrue($form->isValid());
    }

    public function test_bind_uncomplete_nested_form_applied_to_object()
    {
        $builder = new Builder();
        $form = $this->createNestedForm();
        $data = [
            'username' => 'dennis84',
            'password' => 'demo123',
        ];

        $form->bind($data);
        $formData = $form->getData();

        $this->assertInstanceOf('Formz\Tests\Fixtures\User', $formData);
        $this->assertSame('dennis84', $formData->username);
        $this->assertSame('demo123', $formData->password);

        $this->assertSame(null, $formData->address);
        $this->assertTrue($form->isValid());
    }

    public function test_apply_optional_data()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('foo')->optional()
                ->transform(new NullToBlahTransformer()),
            $builder->field('bar'),
        ]);

        $form->bind([ 'foo' => null, 'bar' => 'blub' ]);
        $this->assertSame([
            'foo' => 'blah',
            'bar' => 'blub',
        ], $form->getData());
    }

    private function createNestedForm()
    {
        $builder = new Builder();

        return $builder->form([
            $builder->field('username'),
            $builder->field('password'),
            $builder->field('address', [
                $builder->field('city'),
                $builder->field('street')
            ], function ($city, $street) {
                return new Address($city, $street);
            })->optional(),
        ], function ($username, $password, Address $address = null) {
            return new User($username, $password, $address);
        });
    }
}
