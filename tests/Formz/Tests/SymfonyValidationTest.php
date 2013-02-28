<?php

namespace Formz\Tests;

use Formz\Field;
use Formz\Builder;
use Formz\Tests\Model\User;
use Formz\Tests\Model\Address;

class SfField extends Field
{
    use \Formz\Extensions\Symfonify;
}

class SfBuilder extends Builder
{
    protected function createField($name)
    {
        return new SfField($name);
    }
}

class SymfonyValidationTest extends \PHPUnit_Framework_TestCase
{
    public function test_with_annotation_asserts()
    {
        $builder = new SfBuilder();

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
            $builder->embed('address', [
                $builder->field('city'),
                $builder->field('street')
            ], function ($city, $street) {
                return new Address($city, $street);
            })->withAnnotationAsserts(),
        ], function ($username, $password, $address) {
            return new User($username, $password, $address);
        })->withAnnotationAsserts();

        $data = [
            'username' => 'dennis',
            'password' => 'demo',
            'address' => [
                'city'   => 'Foo',
                'street' => 'Foostreet 12',
            ],
        ];

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->assertEquals('password', $formWithErrors->getErrorsFlat()[0]->getField());
            $this->assertEquals('city', $formWithErrors->getErrorsFlat()[1]->getField());
        }, function ($formData) use ($data) {
            $this->fail('The form must be valid here.');
        });
    }
}
