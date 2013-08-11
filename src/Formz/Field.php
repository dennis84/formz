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
     * Removes a child by name.
     *
     * @param string $name The field name.
     *
     * @throws InvalidArgumentException If the child does not exists
     */
    public function removeChild($name)
    {
        $child = $this->getChild($name);
        unset($this->children[$name]);
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
            throw new \InvalidArgumentException(sprintf('There is no child with name "%s" registered.', $name));
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
     * Replaces all constraints with an new array of constaints.
     *
     * @param Constraint[]
     */
    public function setConstraints(array $constraints)
    {
        $this->constraints = [];
        foreach ($constraints as $constraint) {
            $this->addConstraint($constraint);
        }
    }

    /**
     * Gets the constraints.
     *
     * @return Constraint[]
     */
    public function getConstraints()
    {
        return $this->constraints;
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
     * @param Closure|null $apply The closure object
     */
    public function setApply(\Closure $apply = null)
    {
        $this->apply = $apply;
    }

    /**
     * Gets the apply function.
     *
     * @return Closure|null
     */
    public function getApply()
    {
        return $this->apply;
    }

    /**
     * Sets the unapply function.
     *
     * @param Closure|null $unapply The closure object
     */
    public function setUnapply(\Closure $unapply = null)
    {
        $this->unapply = $unapply;
    }

    /**
     * Gets the unapply function.
     *
     * @return Closure|null
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
     * @param mixed $input The client data
     */
    public function bind($input)
    {
        if ($this->dispatcher->hasListeners(Events::BIND)) {
            $event = new Event($this, $this->data, $this->value, $input);
            $this->dispatcher->dispatch(Events::BIND, $event);
            $input = $event->getInput();
        }

        if ($this->hasChildren()) {
            $value = $data = [];
        } else {
            $value = $data = $input;
        }

        foreach ($this->children as $child) {
            if (isset($input[$child->getFieldName()])) {
                $child->bind($input[$child->getFieldName()]);
            } else {
                $child->bind(null);
            }

            $value[$child->getFieldName()] = $child->getValue();
            $data[$child->getFieldName()] = $child->getData();
        }

        $this->setValue($value);
        
        if ($this->dispatcher->hasListeners(Events::BEFORE_TRANSFORM)) {
            $event = new Event($this, $data, $value, $input);
            $this->dispatcher->dispatch(Events::BEFORE_TRANSFORM, $event);
            $data = $event->getData();
        }

        foreach ($this->transformers as $transformer) {
            $data = $transformer->transform($data);
        }

        if ($this->getApply() && $data) {
            $data = call_user_func_array($this->getApply(), $data);
        }

        if ($this->dispatcher->hasListeners(Events::APPLIED)) {
            $event = new Event($this, $data, $value, $input);
            $this->dispatcher->dispatch(Events::APPLIED, $event);
            $data = $event->getData();
        }

        $this->validate($data);
        $this->data = $data;
    }

    /**
     * Fills the data to the field and all children.
     *
     * @param mixed $data The data to fill as value
     */
    public function fill($data)
    {
        if ($this->dispatcher->hasListeners(Events::FILL)) {
            $event = new Event($this, $data);
            $this->dispatcher->dispatch(Events::FILL, $event);
            $data = $event->getData();
        }

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
                $value[$child->getFieldName()] = $child->fill($data[$child->getFieldName()]);
            }
        }

        $this->setValue($value);
        return $value;
    }

    /**
     * Triggers the validation. Normally the validation will be triggered
     * against the final bound data, but some constrains must be checked against
     * the input or value.
     *
     * @param mixed $data The data to validate
     */
    public function validate($data)
    {
        foreach ($this->constraints as $constraint) {
            if (false === $constraint->validate($data)) {
                $this->addError(new Error(
                    $this->getFieldName(),
                    $constraint->getMessage()
                ));
            }
        }
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
        return $this->removeChild($offset);
    }
}
