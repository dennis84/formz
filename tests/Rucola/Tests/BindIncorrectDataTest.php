<?php

namespace Rucola\Tests;

use Rucola\Rucola;
use Rucola\Tests\Model\User;
use Rucola\Tests\Model\Address;
use Rucola\Tests\Model\Location;

class BindIncorrectDataTest extends \PHPUnit_Framework_TestCase
{
    public function test_flat_form_applied_to_array()
    {
        $rucola = new Rucola();

        $form = $rucola->form([
            $rucola->field('username'),
            $rucola->field('password'),
        ]);

        $data = [
            'foo' => [
                'username' => 'dennis84',
                'password' => 'demo123',
            ],
        ];

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) use ($data) {
            $this->assertEquals('', $formData['username']);
            $this->assertEquals('', $formData['password']);
        });
    }

    public function test_flat_form_applied_to_object()
    {
        $rucola = new Rucola();

        $form = $rucola->form([
            $rucola->field('username'),
            $rucola->field('password'),
        ], function ($username, $password) {
            return new User($username, $password);
        });

        $data = [
            'foo' => [
                'username' => 'dennis84',
                'password' => 'demo123',
            ],
        ];

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) use ($data) {
            $this->assertSame(null, $formData->username);
            $this->assertSame(null, $formData->password);
        });
    }
}
