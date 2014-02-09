<?php

namespace Formz\Tests\Integration;

use Formz\Builder;
use Formz\Tests\Fixtures\User;
use Formz\Tests\Fixtures\Address;
use Formz\Tests\Fixtures\Location;

class UnbindCompleteFormsTest extends \PHPUnit_Framework_TestCase
{
    public function test_flat_form_unapplied_from_array()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
        ]);

        $form->fill([
            'username' => 'dennis84',
            'password' => 'demo123',
        ]);

        $this->assertSame('dennis84', $form['username']->getValue());
        $this->assertSame('demo123', $form['password']->getValue());
    }

    public function test_nested_form_unapplied_from_array()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
            $builder->field('address', [
                $builder->field('city'),
                $builder->field('street'),
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

        $this->assertSame('dennis84', $form['username']->getValue());
        $this->assertSame('demo123', $form['password']->getValue());

        $this->assertSame([
            'city'   => 'Footown',
            'street' => 'Foostreet',
        ], $form['address']->getValue());

        $this->assertSame('Footown', $form['address']['city']->getValue());
        $this->assertSame('Foostreet', $form['address']['street']->getValue());
    }

    public function test_flat_form_unapplied_from_object()
    {
        $builder = new Builder();
        $user = new User('dennis84', 'demo123');

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
        ], null, function (User $user) {
            return ['username' => $user->username, 'password' => $user->password];
        });

        $form->fill($user);

        $this->assertSame('dennis84', $form['username']->getValue());
        $this->assertSame('demo123', $form['password']->getValue());
    }

    public function test_nested_form_unapplied_from_object()
    {
        $builder = new Builder();

        $location = new Location('50', '8');
        $address  = new Address('Footown', 'Foostreet', $location);
        $user     = new User('dennis84', 'demo123', $address);

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
            $builder->field('address', [
                $builder->field('city'),
                $builder->field('street'),
                $builder->field('location', [
                    $builder->field('lat'),
                    $builder->field('lng'),
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

        $this->assertSame('dennis84', $form['username']->getValue());
        $this->assertSame('demo123', $form['password']->getValue());

        $this->assertSame([
            'city'   => 'Footown',
            'street' => 'Foostreet',
            'location' => [
                'lat' => '50',
                'lng' => '8',
            ],
        ], $form['address']->getValue());

        $this->assertSame('Footown', $form['address']['city']->getValue());
        $this->assertSame('Foostreet', $form['address']['street']->getValue());

        $this->assertSame('50', $form['address']['location']['lat']->getValue());
        $this->assertSame('8', $form['address']['location']['lng']->getValue());
    }
}
