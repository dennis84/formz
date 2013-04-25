<?php

namespace Formz\Tests;

use Formz\Field;
use Formz\Builder;
use Formz\Tests\Model\User;
use Formz\Tests\Model\Address;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ValidatorInterface;
use Symfony\Component\Validator\Validation;

class SfField extends Field
{
    use \Formz\Extensions\Symfonify;
}

class SfBuilder extends Builder
{
    protected $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function embed($name, array $fields, \Closure $apply = null, \Closure $unapply = null)
    {
        $form = parent::embed($name, $fields, $apply, $unapply);
        $form->withAnnotationAsserts($this->validator);

        return $form;
    }

    protected function createField($name)
    {
        return new SfField($name);
    }
}

class SymfonyValidationTest extends \PHPUnit_Framework_TestCase
{
    public function test_with_annotation_asserts()
    {
        $builder = new SfBuilder($this->createValidator());

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
