<?php

namespace Formz\Tests\Integration;

use Formz\Builder;

class TwigTest extends \PHPUnit_Framework_TestCase
{
    public function testRender()
    {
        $loader = new \Twig_Loader_String();
        $twig = new \Twig_Environment($loader);

        $builder = new Builder();
        $form = $builder->form([
            $builder->field('name'),
            $builder->field('address', [
                $builder->field('street'),
                $builder->field('city'),
            ]),
        ]);

        $form->fill(['name' => 'foobar']);

        $this->assertSame('address[street]', $twig->render(
            '{{ form.address.street.name }}',
            ['form' => $form]
        ));

        $this->assertSame('address[city]', $twig->render(
            '{{ form.address.city.getName }}',
            ['form' => $form]
        ));
    }
}
