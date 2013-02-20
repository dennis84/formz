# Rucola Forms

Rucola is a simple form mapper for PHP. This library is based on the ScalaForms by Playframework.


## Simple Form

```php

<?php

$builder = new \Rucola\Builder();

$form = $builder->form([
    $builder->field('subject')->nonEmptyText(),
    $builder->field('message')->nonEmptyText()
], function ($subject, $message) {
    return new Contact($subject, $message);
}, function (Contact $contact) {
    return [
        'subject' => $contact->getSubject(),
        'message' => $contact->getMessage(),
    ];
});

$form->bind($_POST);

$form->fold(function (Field $formWithErrors) {
    // Render your template but now with the error form.
}, function (Contact $formData) {
    // Save the data or something else ...
});

```

