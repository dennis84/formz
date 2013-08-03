<?php

namespace Formz\Tests\Integration;

use Formz\Builder;
use Formz\TransformerInterface;
use Formz\Tests\Fixtures\Address;
use Formz\Tests\Fixtures\User;

class OptionalFormTest extends \PHPUnit_Framework_TestCase
{
    public function test_bind_nested_form_applied_to_object()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
            $builder->optionalEmbed('address', [
                $builder->field('city'),
                $builder->field('street')
            ], function ($city, $street) {
                return new Address($city, $street);
            }),
        ], function ($username, $password, Address $address = null) {
            return new User($username, $password, $address);
        });

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
}

class NullToBlahTransformer implements TransformerInterface
{
    public function transform($data)
    {
        return null === $data ? 'blah' : $data;
    }
}
