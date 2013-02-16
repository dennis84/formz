<?php

namespace Rucola\Tests;

use Rucola\Rucola;
use Rucola\Tests\Model\User;
use Rucola\Tests\Model\Address;
use Rucola\Tests\Model\Location;

class UnbindCompleteFormsTest extends \PHPUnit_Framework_TestCase
{
    public function test_flat_form_unapplied_from_array()
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

        $this->assertEquals('dennis84', $form['username']->getValue());
        $this->assertEquals('demo123', $form['password']->getValue());
    }

    public function test_nested_form_unapplied_from_array()
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

        $this->assertEquals('dennis84', $form['username']->getValue());
        $this->assertEquals('demo123', $form['password']->getValue());
        
        $this->assertEquals([
            'city'   => 'Footown',
            'street' => 'Foostreet',
        ], $form['address']->getValue());

        $this->assertEquals('Footown', $form['address']['city']->getValue());
        $this->assertEquals('Foostreet', $form['address']['street']->getValue());
    }

    public function test_flat_form_unapplied_from_object()
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

        $this->assertEquals('dennis84', $form['username']->getValue());
        $this->assertEquals('demo123', $form['password']->getValue());
    }

    public function test_nested_form_unapplied_from_object()
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
                    return [
                        'lat' => $location->lat,
                        'lng' => $location->lng,
                    ];
                })
            ], null, function (Address $address) {
                return [
                    'city' => $address->city,
                    'street' => $address->street,
                    'location' => $address->location,
                ];
            })
        ], null, function (User $user) {
            return [
                'username' => $user->username,
                'password' => $user->password,
                'address' => $user->address,
            ];
        });

        $form->fill($user);

        $this->assertEquals('dennis84', $form['username']->getValue());
        $this->assertEquals('demo123', $form['password']->getValue());

        $this->assertEquals([
            'city'   => 'Footown',
            'street' => 'Foostreet',
            'location' => [
                'lat' => '50',
                'lng' => '8',
            ],
        ], $form['address']->getValue());

        $this->assertEquals('Footown', $form['address']['city']->getValue());
        $this->assertEquals('Foostreet', $form['address']['street']->getValue());
       
        $this->assertEquals('50', $form['address']['location']['lat']->getValue());
        $this->assertEquals('8', $form['address']['location']['lng']->getValue());
    }
}
