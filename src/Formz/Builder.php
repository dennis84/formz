<?php

namespace Formz;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * This is a helper to build field objects.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Builder
{
    protected $extensions = [];

    /**
     * Instantiate a new form builder.
     *
     * @param ExtensionInterface[] $extension The form extensions
     */
    public function __construct(array $extensions = [])
    {
        $this->registerExtensions([
            new \Formz\Extension\Rendering(),
            new \Formz\Extension\Constraints(),
            new \Formz\Extension\Optional(),
            new \Formz\Extension\Multiple(),
            new \Formz\Extension\Verifying(),
        ]);

        $this->registerExtensions($extensions);
    }

    /**
     * Registers an array of extensions.
     *
     * @param ExtensionInterface[] $extensions The form extensions
     */
    public function registerExtensions(array $extensions)
    {
        foreach ($extensions as $extension) {
            $this->extend($extension);
        }
    }

    /**
     * Registers an extension. All extensions will be passed to the fields and 
     * can be called via the magic __call mathod.
     *
     * @param ExtensionInterface $extension The extension object
     */
    public function extend(ExtensionInterface $extension)
    {
        $this->extensions[] = $extension;
    }

    /**
     * Creates a new field object with the name ''. Each field and the form 
     * itself is based on the Field class, so the root form is just a field with
     * a blank name. (If you want to namespace a form, then use the "field()" 
     * method.)
     *
     * $form = $builder->form([
     *   // add fields here
     * ]);
     *
     * @param callable $apply   The apply function
     * @param callable $unapply The unapply function
     *
     * @return Field
     */
    public function form(array $fields, callable $apply = null, callable $unapply = null)
    {
        return $this->field('', $fields, $apply, $unapply);
    }

    /**
     * Creates a named field. Use this function to create form fields, embedded
     * forms or namespaced forms.
     *
     * Creates a single field:
     * $username = $builder->field('username');
     *
     * Create an embedded form:
     * $address = $builder->field('address', [
     *   $builder->field('street'),
     *   $builder->field('city'),
     * ]);
     *
     * Creates a namespaced form (The rendered fields gets a name prefix e.g.
     * <input type="text" name="user[username]">):
     * $form = $builder->field('user', [
     *   // add fields here
     * ]);
     *
     * @param string   $name     The field name
     * @param Field[]  $children The field children
     * @param callable $apply    The apply function
     * @param callable $unapply  The unapply function
     *
     * @return Field
     */
    public function field($name, array $children = [], callable $apply = null, callable $unapply = null)
    {
        $field = $this->createField($name);

        foreach ($children as $child) {
            $child->setParent($field);
            $field->addChild($child);
        }

        $field->transform(new \Formz\Transformer\Callback($apply, $unapply), -1);
        return $field;
    }

    /**
     * Creates a new field object.
     *
     * @param string $name The field name
     *
     * @return Field
     */
    protected function createField($name)
    {
        return new Field($name, new EventDispatcher(), $this->extensions);
    }
}
