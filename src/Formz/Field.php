<?php

namespace Formz;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Field.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Field implements \ArrayAccess
{
    protected $name;
    protected $dispatcher;
    protected $extensions = [];
    protected $transformers = [];
    protected $constraints = [];
    protected $children = [];
    protected $errors = [];
    protected $parent;
    protected $value;
    protected $data;
    protected $apply;
    protected $unapply;
    protected $optional = false;
    protected $multiple = false;

    /**
     * Constructor.
     *
     * @param string                   $name       The field name
     * @param EventDispatcherInterface $dispatcher The event dispatcher
     * @param ExtensionInterface[]     $extensions The field extensions
     */
    public function __construct($name, EventDispatcherInterface $dispatcher, array $extensions = [])
    {
        $this->name = $name;
        $this->dispatcher = $dispatcher;
        foreach ($extensions as $extension) {
            $this->extend($extension);
        }
    }

    /**
     * Adds an extension.
     *
     * @param ExtensionInterface $extension The form extension
     */
    public function extend(ExtensionInterface $extension)
    {
        $this->extensions[] = $extension;
        return $this;
    }

    /**
     * Adds a transformer.
     *
     * @param TransformerInterface The transformer
     */
    public function transform(TransformerInterface $transformer)
    {
        $this->transformers[] = $transformer;
        return $this;
    }

    /**
     * Invokes an extension method if is not defined in field class or throws
     * an exception.
     *
     * @param string  $method    The called method name
     * @param mixed[] $arguments The method arguments
     *
     * @return Field
     *
     * @throws BadMethodCallException If method is not callable
     */
    public function __call($method, $arguments)
    {
        foreach ($this->extensions as $extension) {
            if (true === method_exists($extension, $method)) {
                array_unshift($arguments, $this);
                call_user_func_array(array($extension, $method), $arguments);
                return $this;
            }
        }

        throw new \BadMethodCallException(sprintf('Method "%s" does not exists.', $method)); 
    }

    /**
     * Sets the field name.
     *
     * @param string $name The field name
     */
    public function setFieldName($name)
    {
        $this->name = $name;
    }

    /**
     * Gets the field name.
     *
     * @return string
     */
    public function getFieldName()
    {
        return $this->name;
    }

    /**
     * Gets the name for form view.
     *
     * @return string
     */
    public function getName()
    {
        $parent = $this->getParent();

        if (null !== $parent) {
            if ('' === $parent->getName()) {
                return $this->getFieldName();
            }

            return $parent->getName() . '[' . $this->getFieldName() . ']';
        }

        return $this->getFieldName();
    }

    /**
     * Resets the existing field children and sets the new ones.
     *
     * @param Field[] $children An array of field obejcts
     */
    public function setChildren(array $children)
    {
        $this->children = [];
        foreach ($children as $child) {
            $this->addChild($child);
        }
    }

    /**
     * Returns true if the field has children, otherwise false.
     *
     * @return boolean
     */
    public function hasChildren()
    {
        return count($this->children) > 0;
    }

    /**
     * Gets the field children.
     *
     * @return Field[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Adds a child field.
     *
     * @param Field $field The field obejct
     */
    public function addChild(Field $child)
    {
        $this->children[$child->getFieldName()] = $child;
    }

    /**
     * Returns true if a child by name exists, otherwise false.
     *
     * @param boolean $name The field name
     *
     * @return boolean
     */
    public function hasChild($name)
    {
        return array_key_exists($name, $this->children);
    }

    /**
     * Gets a child by name.
     *
     * @param string $name The field name.
     *
     * @return Field
     *
     * @throws InvalidArgumentException If the child by name does not exists
     */
    public function getChild($name)
    {
        if (!$this->hasChild($name)) {
            throw new \InvalidArgumentException(sprintf('There was no child with name "%s" registered.', $name));
        }

        return $this->children[$name];
    }

    /**
     * Sets the parent field.
     *
     * @param Field $field The parent field object
     */
    public function setParent(Field $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Gets the parent field.
     *
     * @return Field
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Adds a error to the field.
     *
     * @param Error $error The error object
     */
    public function addError(Error $error)
    {
        $this->errors[] = $error;
    }

    /**
     * Gets all errors from field and children as an flat array.
     *
     * @return Error[]
     */
    public function getErrorsFlat()
    {
        $errors = $this->errors;

        foreach ($this->getChildren() as $child) {
            $errors = array_merge($errors, $child->getErrorsFlat());
        }

        return $errors;
    }

    /**
     * Gets the field errors.
     *
     * @return Error[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Returns true if the field has errors, otherwise false.
     *
     * @return boolean
     */
    public function hasErrors()
    {
        return count($this->getErrorsFlat()) > 0;
    }

    /**
     * Adds a constraint to the field.
     *
     * @param Constraint $constraint The constaint object
     */
    public function addConstraint(Constraint $constraint)
    {
        $this->constraints[] = $constraint;
    }

    /**
     * Sets the value.
     *
     * @param mixed $value The value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Gets the value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Gets the applied data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets the apply function.
     *
     * @param Closure $apply The closure object
     */
    public function setApply(\Closure $apply)
    {
        $this->apply = $apply;
    }

    /**
     * Gets the apply function.
     *
     * @return Closure
     */
    public function getApply()
    {
        return $this->apply;
    }

    /**
     * Sets the unapply function.
     *
     * @param Closure $unapply The closure object
     */
    public function setUnapply(\Closure $unapply)
    {
        $this->unapply = $unapply;
    }

    /**
     * Gets the unapply function.
     *
     * @return Closure
     */
    public function getUnapply()
    {
        return $this->unapply;
    }

    /**
     * Gets the event dispatcher.
     *
     * @return EventDispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * Binds the client data to the field and all children.
     *
     * @param mixed $data The data to bind to the field
     */
    public function bind($data)
    {
        $this->maybePrepareMultipleFields($data);

        if (is_array($data)) {
            foreach ($data as $name => $value) {
                if (!$this->hasChild($name)) {
                    unset($data[$name]);
                }
            }

            $data += $this->getBlankData();
        }

        $this->setValue($data);

        foreach ($this->constraints as $constraint) {
            if (false === $constraint->check($this->getValue())) {
                $this->addError(new Error(
                    $this->getFieldName(),
                    $constraint->getMessage()
                ));
            }
        }

        $optionalAndEmpty = $this->isOptional() && empty($data);

        if (!$optionalAndEmpty) {
            foreach ($this->children as $child) {
                if (isset($data[$child->getFieldName()])) {
                    $child->bind($data[$child->getFieldName()]);
                } else {
                    $child->bind(null);
                }

                $data[$child->getFieldName()] = $child->getData();
            }
        }

        foreach ($this->transformers as $transformer) {
            $data = $transformer->transform($data);
        }

        if ($this->getApply() && !$optionalAndEmpty) {
            $data = call_user_func_array($this->getApply(), $data);
        }

        if ($this->dispatcher->hasListeners(Events::APPLIED)) {
            $event = new Event($this, $data);
            $this->dispatcher->dispatch(Events::APPLIED, $event);
        }

        $this->data = $data;
    }

    /**
     * Fills the data to the field and all children.
     *
     * @param mixed $data The data to fill as value
     */
    public function fill($data)
    {
        $this->maybePrepareMultipleFields($data);

        if (!$this->hasChildren()) {
            $this->setValue($data);
            return $data;
        }

        if (!is_array($data)) {
            $data = [$data];
        }

        if (null !== $this->unapply) {
            $data = call_user_func_array($this->unapply, $data);
        }

        $value = [];

        foreach ($this->getChildren() as $child) {
            if (isset($data[$child->getFieldName()])) {
                $value[$child->getFieldName()] =
                    $child->fill($data[$child->getFieldName()]);
            }
        }

        $this->setValue($value);
        return $value;
    }

    /**
     * Returns true if the field and all children have no errors, otherwise
     * false.
     *
     * @return boolean
     */
    public function isValid()
    {
        return !$this->hasErrors();
    }

    /**
     * Sets the form as optional.
     *
     * @return Field
     */
    public function optional()
    {
        $this->optional = true;
        return $this;
    }

    /**
     * Returns true if the field is optional, otherwise false.
     *
     * @return boolean
     */
    public function isOptional()
    {
        return $this->optional;
    }

    /**
     * Sets the field as multiple.
     *
     * @return Field
     */
    public function multiple($multiple = true)
    {
        $this->multiple = $multiple;
        return $this;
    }

    /**
     * Returns true if the field is multiple, otherwise false.
     *
     * @return boolean
     */
    public function isMultiple()
    {
        return $this->multiple;
    }

    /**
     * Also clone the childen.
     */
    public function __clone()
    {
        $this->setChildren(array_map(function ($child) {
            return clone $child;
        }, $this->getChildren()));
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->getChild($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->hasChild($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException('Not implemented');
    }

    /**
     * Gets an blank array but with the children array keys.
     *
     * @return mixed[]
     */
    private function getBlankData()
    {
        $blank = [];
        foreach ($this->children as $name => $child) {
            $value = null;
            if ($child->isMultiple()) {
                $value = [];
            }

            $blank[$name] = $value;
        }

        return $blank;
    }

    /**
     * Prepares the current field if multiple is true.
     *
     * @param mixed $data The bind or fill data
     *
     * @throws InvalidArgumentException If multiple is true and data is not an
     *                                  array
     */
    private function maybePrepareMultipleFields($data)
    {
        if (!$this->isMultiple()) {
            return;
        }

        if (!is_array($data)) {
            throw new \InvalidArgumentException('The bound data on an multiple field must be an array');
        }

        $choices = [];
        foreach ($data as $index => $value) {
            $choice = clone $this;
            $choice->setFieldName((string) $index);
            $choice->multiple(false);
            $choice->setParent($this);
            foreach ($choice->getChildren() as $child) {
                $child->setParent($choice);
            }

            if ($apply = $choice->getApply()) {
                $apply = \Closure::bind($apply, $choice);
                $choice->setApply($apply);
            }

            if ($unapply = $choice->getUnapply()) {
                $unapply = \Closure::bind($unapply, $choice);
                $choice->setUnapply($unapply);
            }

            $choices[] = $choice;
        }

        $this->setChildren($choices);

        $this->apply = null;
        $this->unapply = null;
    }
}
