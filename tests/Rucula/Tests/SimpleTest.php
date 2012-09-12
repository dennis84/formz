<?php

namespace Rucula\Tests;

use Rucula\Rucula;

class RuculaTest extends \PHPUnit_Framework_TestCase
{
    public function testSomeForm()
    {
        $rucula = new Rucula();

        $form = $rucula['builder.tuple']->build(array(
            'username'   => $rucula['type.text'],
            'address'    => $rucula['builder.tuple']->build(array(
                'city'     => $rucula['type.text'],
                'street'   => $rucula['type.text'],
                'location' => $rucula->optional($rucula['builder.tuple']->build(array(
                    'lat'    => $rucula['type.non_empty_text'],
                    'lng'    => $rucula['type.non_empty_text'],
                ), function ($lat, $lng) {
                    return new Location($lat, $lng);
                }, function ($loc) {
                    return array('lat' => $loc->lat, 'lng' => $loc->lng);
                })),
            ), function ($city, $street, $location) {
                return new Address($city, $street, $location);
            }, function ($address) {
                return array('city' => $address->city, 'street' => $address->street, 'location' => $address->location);
            }),
        ), function ($username, $address) {
            return new User($username, $address);
        }, function ($user) {
            return array('username' => $user->username, 'address' => $user->address);
        });

        $user = new User('dennis84', new Address('cologne', 'foostr', new Location('12.8', 8.2)));
        $form->fill($user);

        //$form->bind(array(
            //'username'   => 'dennis84',
            //'address'    => array(
                //'city'     => 'Cologne',
                //'street'   => 'Foostr. 12',
                //'location' => array(
                    //'lat'    => '12.000892',
                    //'lng'    => '8',
                //),
            //),
        //));

        //$form->fold(function ($formWithErrors) {
            //print_r($formWithErrors);
        //}, function ($formData) {
            //print_r($formData);
        //});
    }
}

class User
{
    public $username;
    public $address;

    public function __construct($username, $address)
    {
        $this->username = $username;
        $this->address  = $address;
    }
}

class Address
{
    public $city;
    public $street;
    public $location;

    public function __construct($city, $street, $location)
    {
        $this->city     = $city;
        $this->street   = $street;
        $this->location = $location;
    }
}

class Location
{
    public $lat;
    public $lng;

    public function __construct($lat, $lng)
    {
        $this->lat  = $lat;
        $this->lng = $lng;
    }
}
