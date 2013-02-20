<?php

namespace Rucola\Tests;

use Rucola\Builder;
use Rucola\Tests\Model\User;
use Rucola\Tests\Model\Address;

class SymfonyValidationTest extends \PHPUnit_Framework_TestCase
{
    public function test_with_annotation_assets()
    {
        $builder = new Builder();

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
