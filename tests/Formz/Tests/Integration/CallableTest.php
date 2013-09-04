<?php

namespace Formz\Tests\Integration;

use Formz\Builder;
use Formz\Tests\Fixtures\User;

class CallableTest extends \PHPUnit_Framework_TestCase
{
    public function testClosure()
    {
        $builder = new Builder();
        $applied = false;
        $unapplied = false;

        $user = new User('dennis84', 'demo123');

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
        ], function ($username, $password) use (&$applied) {
            $applied = true;
        }, function  (User $user) use (&$unapplied) {
            $unapplied = true;
        });

        $form->fill($user);

        $form->bind([
            'username' => 'foo',
            'password' => 'bar'
        ]);

        $this->assertTrue($applied && $unapplied);
    }

    public function testCallUserFunc()
    {
        $builder = new Builder();

        $user = new User('dennis84', 'demo123');

        $formHandler = $this->getMock(
            'Formz\Tests\Fixtures\UserFormHandler',
            ['apply', 'unapply']);

        $formHandler->expects($this->once())
            ->method('apply')
            ->with($this->equalTo('foo'), $this->equalTo('bar'));

        $formHandler->expects($this->once())
            ->method('unapply')
            ->with($this->equalTo($user));

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
        ], [$formHandler, 'apply'], [$formHandler, 'unapply']);

        $form->fill($user);

        $form->bind([
            'username' => 'foo',
            'password' => 'bar'
        ]);
    }
}
