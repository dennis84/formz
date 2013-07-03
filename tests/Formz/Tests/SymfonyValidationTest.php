<?php

namespace Formz\Tests;

use Formz\Builder;
use Formz\Tests\Model\User;
use Formz\Tests\Model\Address;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;

class SfBuilder extends Builder
{
    public function embed($name, array $fields, \Closure $apply = null, \Closure $unapply = null)
    {
        $form = parent::embed($name, $fields, $apply, $unapply);
        $form->withAnnotationAsserts();
        return $form;
    }
}

class SymfonyValidationTest extends \PHPUnit_Framework_TestCase
{
    public function test_with_annotation_asserts()
    {
        $builder = new SfBuilder([
            new \Formz\Extensions\Symfonify($this->createValidator()),
        ]);

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
            $builder->embed('address', [
                $builder->field('city'),
                $builder->field('street')
            ], function ($city, $street) {
                return new Address($city, $street);
            }),
        ], function ($username, $password, $address) {
            return new User($username, $password, $address);
        });

        $request = Request::create('/', 'POST', [
            'username' => 'dennis',
            'password' => 'demo',
            'address' => [
                'city'   => 'Foo',
                'street' => 'Foostreet 12',
            ],
        ]);

        $form->bindFromRequest($request);
        $formData = $form->getData();

        $this->assertEquals('password', $form->getErrorsFlat()[0]->getField());
        $this->assertEquals('city', $form->getErrorsFlat()[1]->getField());
    }

    private function createValidator()
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $this->symfonyValidator = $validator;
        return $validator;
    }
}
