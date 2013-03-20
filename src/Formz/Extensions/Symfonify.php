<?php

namespace Formz\Extensions;

use Formz\Error;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ValidatorInterface;

/**
 * Symfonify.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
trait Symfonify
{
    /**
     * Allows to pass the symfony request object to the bind method.
     *
     * @param Request $request The symfony request object
     */
    public function bindFromRequest(Request $request)
    {
        $this->bind($request->request->all());
    }

    /**
     * Enables symfony's annotation asserts.
     *
     * @return Field
     */
    public function withAnnotationAsserts(ValidatorInterface $vali)
    {
        $this->on('change_data', function ($data) use ($vali) {
            $violations = $vali->validate($data);
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
}
