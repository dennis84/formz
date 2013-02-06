<?php

namespace Rucola\Tests;

use Rucola\Rucola;
use Rucola\Tests\Model\User;
use Rucola\Tests\Model\Address;
use Rucola\Tests\Model\Location;

class UnbindCompleteFormsTest extends \PHPUnit_Framework_TestCase
{
    public function testFormWithoutUnapply()
    {
        $rucola = new Rucola();

        $form = $rucola->form([
            $rucola->field('username'),
            $rucola->field('password'),
        ]);

        $form->fill([
            'username' => 'dennis84',
            'password' => 'demo123',
        ]);

        $this->assertEquals('dennis84', $form->getChild('username')->getValue());
        $this->assertEquals('demo123', $form->getChild('password')->getValue());
    }

    public function testNestedFormWithoutUnapply()
    {
        $rucola = new Rucola();

        $form = $rucola->form([
            $rucola->field('username'),
            $rucola->field('password'),
            $rucola->embed('address', [
                $rucola->field('city'),
                $rucola->field('street'),
            ]),
        ]);

        $form->fill([
            'username' => 'dennis84',
            'password' => 'demo123',
            'address' => [
                'city' => 'Footown',
                'street' => 'Foostreet',
            ],
        ]);

        $this->assertEquals('dennis84', $form->getChild('username')->getValue());
        $this->assertEquals('demo123', $form->getChild('password')->getValue());
        
        $this->assertEquals([
            'city'   => 'Footown',
            'street' => 'Foostreet',
        ], $form->getChild('address')->getValue());

        $this->assertEquals('Footown', $form->getChild('address')->getChild('city')->getValue());
        $this->assertEquals('Foostreet', $form->getChild('address')->getChild('street')->getValue());
    }

    public function testFormWithUnapply()
    {
        $rucola = new Rucola();
        $user = new User('dennis84', 'demo123');

        $form = $rucola->form([
            $rucola->field('username'),
            $rucola->field('password'),
        ], null, function (User $user) {
            return ['username' => $user->username, 'password' => $user->password];
        });

        $form->fill($user);

        $this->assertEquals('dennis84', $form->getChild('username')->getValue());
        $this->assertEquals('demo123', $form->getChild('password')->getValue());
    }

    public function testNestedFormWithUnapply()
    {
        $rucola = new Rucola();

        $location = new Location('50', '8');
        $address  = new Address('Footown', 'Foostreet', $location);
        $user     = new User('dennis84', 'demo123', $address);

        $form = $rucola->form([
            $rucola->field('username'),
            $rucola->field('password'),
            $rucola->embed('address', [
                $rucola->field('city'),
                $rucola->field('street'),
                $rucola->embed('location', [
                    $rucola->field('lat'),
                    $rucola->field('lng'),
                ], null, function (Location $location) {
                    return ['lat' => $location->lat, 'lng' => $location->lng];
                })
            ], null, function (Address $address) {
                return ['city' => $address->city, 'street' => $address->street, 'location' => $address->location];
            })
        ], null, function (User $user) {
            return ['username' => $user->username, 'password' => $user->password, 'address' => $user->address];
        });

        $form->fill($user);

        $this->assertEquals('dennis84', $form->getChild('username')->getValue());
        $this->assertEquals('demo123', $form->getChild('password')->getValue());

        $this->assertEquals([
            'city'   => 'Footown',
            'street' => 'Foostreet',
            'location' => [
                'lat' => '50',
                'lng' => '8',
            ],
        ], $form->getChild('address')->getValue());

        $this->assertEquals('Footown', $form->getChild('address')->getChild('city')->getValue());
        $this->assertEquals('Foostreet', $form->getChild('address')->getChild('street')->getValue());
       
        $this->assertEquals('50', $form->getChild('address')->getChild('location')->getChild('lat')->getValue());
        $this->assertEquals('8', $form->getChild('address')->getChild('location')->getChild('lng')->getValue());
    }
}
