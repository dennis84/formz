<?php

namespace Rucola\Extensions;

use Rucola\Error;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;

/**
 * Symfonify.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
trait Symfonify
{
    private $symfonyValidator;

    /**
     * Allows to pass the symfony request object to the bind method.
     *
     * @param Request $request The symfony request object
     */
    public function bindFromRequest(Request $request)
    {
        $this->bind($request->request);
    }

    /**
     * Enables symfony's annotation asserts.
     *
     * @return Field
     */
    public function withAnnotationAsserts()
    {
        $this->on('change_data', function ($data) {
            $violations = $this->getValidator()->validate($data);
            foreach ($violations as $violation) {
                $this->addError(new Error(
                    $violation->getPropertyPath(),
                    $violation->getMessage()
                ));
            }

            return $data;
        });

        return $this;
    }

    /**
     * Gets the symfony validator.
     *
     * @return Validator
     */
    private function getValidator()
    {
        if (null !== $this->symfonyValidator) {
            return $this->symfonyValidator;
        }

        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $this->symfonyValidator = $validator;
        return $validator;
    }
}
