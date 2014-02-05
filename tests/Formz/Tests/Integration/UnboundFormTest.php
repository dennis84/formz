<?php

namespace Formz\Tests\Integration;

use Formz\Builder;
use Formz\Tests\Fixtures\User;

class UnboundFormTest extends \PHPUnit_Framework_TestCase
{
    public function test_get_data_from_unbound_form()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
        ], function ($username, $password) {
            return new User($username, $password);
        }, function (User $user) {
            return [
                'username' => $user->username,
                'password' => $user->password,
            ];
        });

        $user = new User('dennis', 'demo');
        $form->fill($user);

        // Should the field throw an exception here?
        $this->assertNull($form->getData());
    }
}
