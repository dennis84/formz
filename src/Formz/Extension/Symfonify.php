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
     * @param ValidatorInterface|null $validator The symfony validator
     */
    public function __construct(ValidatorInterface $validator = null)
    {
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(Field $field)
    {
        // Activates annotation validation
        if (null !== $this->validator) {
            $disp = $field->getDispatcher();
            $disp->addListener(Events::APPLIED, function (Event $event) {
                $data = $event->getData();
                if (!is_object($data)) {
                    return;
                }

                $violations = $this->validator->validate($data);

                foreach ($violations as $violation) {
                    $field = $event->getField();

                    if ($field->hasChild($violation->getPropertyPath())) {
                        $field = $field->getChild($violation->getPropertyPath());
                    }

                    $field->addError(new Error(
                        $violation->getPropertyPath(),
                        $violation->getMessage()
                    ));
                }
            });
        }
    }

    /**
     * Allows to bind the form with Symfony's request object.
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
}
