# Formz

A simple form libary for PHP, inspired by the form component from the [PlayFramework](http://www.playframework.com).

[![Build Status](https://travis-ci.org/dennis84/formz.png?branch=master)](https://travis-ci.org/dennis84/formz)

## Example

This is a quick example to give you a taste of what formz does.

```php
<?php

$builder = new \Formz\Builder();

$form = $builder->form([
    // Creates a text field that must not be blank.
    $builder->field('name')->nonEmptyText(),
    // Creates a numeric field.
    $builder->field('age')->integer(),
], function ($name, $age) {
    // This is the apply function. Use this function to convert the submitted
    // data to your domain objects.
    return new User($name, $age);
}, function (User $user) {
    // This is the unapply function. Use it to convert your domain models into
    // an associative array. This function is only needed if you want to fill a 
    // form with existing values.
    return [
        'name' => $user->getName(),
        'age' => $user->getAge(),
    ];
});

// Binds the $_POST data to the form.
$form->bind($_POST);

if ($form->isValid()) {
    // Get the applied data. In this example, this is the "User" object.
    $user = $form->getData();
}

```

## More examples

Formz has a pretty comprehensive test coverage that demonstrates [the whole bunch of functionality](https://github.com/dennis84/formz/tree/master/tests/Formz/Tests/Integration).
