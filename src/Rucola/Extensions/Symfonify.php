<?php

namespace Rucola\Extensions;

use Symfony\Component\HttpFoundation\Request;

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
        $this->bind($request->request);
    }
}
