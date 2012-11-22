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

        $form = $rucola->form('form', array(
            $rucola->field('username'),
            $rucola->field('password'),
        ));

        $form->fill(array(
            'username' => 'dennis84',
            'password' => 'demo123',
        ));

        $this->assertEquals('dennis84', $form->getChild('username')->getValue());
        $this->assertEquals('demo123', $form->getChild('password')->getValue());
    }

    public function testNestedFormWithoutUnapply()
    {
        $rucola = new Rucola();

        $form = $rucola->form('form', array(
            $rucola->field('username'),
            $rucola->field('password'),
            $rucola->form('address', array(
                $rucola->field('city'),
                $rucola->field('street'),
            )),
        ));

        $form->fill(array(
            'username' => 'dennis84',
            'password' => 'demo123',
            'address' => array(
                'city' => 'Footown',
                'street' => 'Foostreet',
            ),
        ));

        $this->assertEquals('dennis84', $form->getChild('username')->getValue());
        $this->assertEquals('demo123', $form->getChild('password')->getValue());
        
        $this->assertEquals(array(
            'city'   => 'Footown',
            'street' => 'Foostreet',
        ), $form->getChild('address')->getValue());

        $this->assertEquals('Footown', $form->getChild('address')->getChild('city')->getValue());
        $this->assertEquals('Foostreet', $form->getChild('address')->getChild('street')->getValue());
    }

    public function testFormWithUnapply()
    {
        $rucola = new Rucola();
        $user = new User('dennis84', 'demo123');

        $form = $rucola->form('form', array(
            $rucola->field('username'),
            $rucola->field('password'),
        ), null, function (User $user) {
            return array('username' => $user->username, 'password' => $user->password);
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

        $form = $rucola->form('form', array(
            $rucola->field('username'),
            $rucola->field('password'),
            $rucola->form('address', array(
                $rucola->field('city'),
                $rucola->field('street'),
                $rucola->form('location', array(
                    $rucola->field('lat'),
                    $rucola->field('lng'),
                ), null, function (Location $location) {
                    return array('lat' => $location->lat, 'lng' => $location->lng);
                })
            ), null, function (Address $address) {
                return array('city' => $address->city, 'street' => $address->street, 'location' => $address->location);
            })
        ), null, function (User $user) {
            return array('username' => $user->username, 'password' => $user->password, 'address' => $user->address);
        });

        $form->fill($user);

        $this->assertEquals('dennis84', $form->getChild('username')->getValue());
        $this->assertEquals('demo123', $form->getChild('password')->getValue());

        $this->assertEquals(array(
            'city'   => 'Footown',
            'street' => 'Foostreet',
            'location' => array(
                'lat' => '50',
                'lng' => '8',
            ),
        ), $form->getChild('address')->getValue());

        $this->assertEquals('Footown', $form->getChild('address')->getChild('city')->getValue());
        $this->assertEquals('Foostreet', $form->getChild('address')->getChild('street')->getValue());
       
        $this->assertEquals('50', $form->getChild('address')->getChild('location')->getChild('lat')->getValue());
        $this->assertEquals('8', $form->getChild('address')->getChild('location')->getChild('lng')->getValue());
    }
}
