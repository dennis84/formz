<?php

namespace Formz\Extension;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ValidatorInterface;
use Formz\ExtensionInterface;
use Formz\Error;
use Formz\Event;
use Formz\Events;
use Formz\Field;

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
     *
     * @return Field
     */
    public function bindFromRequest(Field $field, Request $request)
    {
        $field->bind($request->request->all());
        return $field;
    }

    /**
     * Enables symfony's annotation asserts.
     *
     * @param Field $field The form field
     *
     * @return Field
     */
    public function withAnnotationAsserts(Field $field)
    {
        $field->getDispatcher()->addListener(Events::APPLIED, function (Event $event) {
            $violations = $this->validator->validate($event->getData());
            foreach ($violations as $violation) {
                $event->getField()->addError(new Error(
                    $violation->getPropertyPath(),
                    $violation->getMessage()
                ));
            }
        });

        return $field;
    }
}
