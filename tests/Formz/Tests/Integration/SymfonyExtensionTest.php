<?php

namespace Formz\Tests\Integration;

use Formz\Builder;
use Formz\Tests\Fixtures\User;
use Formz\Tests\Fixtures\Address;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;

class SymfonyExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function test_with_annotation_asserts()
    {
        $builder = new Builder([
            new \Formz\Extension\Symfonify($this->createValidator()),
        ]);

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
            $builder->field('firstName'),
            $builder->field('last_name'),
            $builder->field('address', [
                $builder->field('city'),
                $builder->field('street')
            ], function ($city, $street) {
                return new Address($city, $street);
            })->verifyByAnnotations(),
        ], function ($username, $password, $address) {
            return new User($username, $password, $address);
        })->verifyByAnnotations();

        $request = Request::create('/', 'POST', [
            'username' => 'dennis',
            'password' => 'demo',
            'firstName' => '',
            'last_name' => '',
            'address' => [
                'city'   => 'Foo',
                'street' => 'Foostreet 12',
            ],
        ]);

        $form->bindFromRequest($request);
        $formData = $form->getData();

        $this->assertCount(1, $form['password']->getErrors());
        $this->assertCount(1, $form['address']['city']->getErrors());
        $this->assertCount(1, $form['firstName']->getErrors());
        $this->assertCount(1, $form['last_name']->getErrors());
    }

    private function createValidator()
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        return $validator;
    }
}
