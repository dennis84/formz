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
     * Constructor.
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
     * Registers a set of extensions.
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
     * Adds an extension.
     *
     * @param ExtensionInterface $extension The extension object
     */
    public function extend(ExtensionInterface $extension)
    {
        $this->extensions[] = $extension;
    }

    /**
     * Use this function to build the root form.
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
     * Creates a field object.
     *
     * @param string   $name     The field name
     * @param Field[]  $children The field children
     * @param callable $apply    This apply function
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
