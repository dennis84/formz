<?php

namespace Formz\Extensions;

use Formz\ExtensionInterface;
use Formz\Error;
use Formz\Field;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ValidatorInterface;

/**
 * Symfonify.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Symfonify implements ExtensionInterface
{
    protected $validator;

    /**
     * Constructor.
     *
     * @param ValidatorInterface $validator The symfony validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Allows to pass the symfony request object to the bind method.
     *
     * @param Field   $field   The form field
     * @param Request $request The symfony request object
     */
    public function bindFromRequest(Field $field, Request $request)
    {
        $field->bind($request->request->all());
    }

    /**
     * Enables symfony's annotation asserts.
     *
     * @param Field $field The form field
     */
    public function withAnnotationAsserts(Field $field)
    {
        $field->on('change_data', function ($data) use ($field) {
            $violations = $this->validator->validate($data);
            foreach ($violations as $violation) {
                $field->addError(new Error(
                    $violation->getPropertyPath(),
                    $violation->getMessage()
                ));
            }

            return $data;
        });
    }
}
