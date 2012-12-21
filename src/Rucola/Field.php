<?php

namespace Rucola;

use Rucola\Util\DataMapper;

/**
 * Field.
 */
class Field implements \ArrayAccess
{
    use Constraints;

    protected $name;
    protected $root = false;
    protected $constraints = array();
    protected $children = array();
    protected $errors = array();
    protected $events = array();
    protected $parent;
    protected $value;
    protected $data;
    protected $apply;
    protected $unapply;
    protected $optional = false;
    protected $multiple = false;
    protected $customUnapply = false;

    /**
     * Constructor.
     *
     * @param string $name The field name
     */
    public function __construct($name)
    {
        $this->name = $name;
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
            if ($parent->isRoot()) {
                return $this->getFieldName();
            }

            return $parent->getName() . '[' . $this->getFieldName() . ']';
        }

        return $this->getFieldName();
    }

    /**
     * Sets the field to root.
     *
     * @param boolean $root The root value
     */
    public function setRoot($root)
    {
        $this->root = (boolean) $root;
    }

    /**
     * Returns true or false if the field is a root one or not.
     *
     * @return boolean
     */
    public function isRoot()
    {
        return $this->root;
    }

    /**
     * Resets the existing field children and sets the new ones.
     *
     * @param array $children An array of field obejcts
     */
    public function setChildren(array $children)
    {
        $this->children = array();
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
        return count($this->children) > 0 ? true : false;
    }

    /**
     * Gets the field children.
     *
     * @return array
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
     * @return array
     */
    public function getErrorsFlat()
    {
        $errors = $this->errors;

        foreach ($this->getChildren() as $child) {
            $errors = array_merge($errors, $child->getErrorsFlat());
        }

        return $errors;
    }

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
     * Adds a custom constraint to the field.
     *
     * @param string  $message The error message if the contraint matches an error
     * @param Closure $check   The check method
     *
     * @return Field
     */
    public function verifying($message, $check)
    {
        $this->addConstraint(new Constraint($message, $check));
        return $this;
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
     * Sets the client data.
     *
     * @param mixed $data The data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Gets the client data.
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
     * Sets custom apply to true. This is impotrtant to unbind the value with a
     * custom function correctly.
     */
    public function setCustomUnapply()
    {
        $this->customUnapply = true;
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
     * Adds a event.
     *
     * @param string  $name     The event name.
     * @param Closure $function The event handler
     */
    public function on($name, \Closure $function)
    {
        $this->events[$name][] = $function;
    }

    /**
     * Triggers all events by name and returns filtered data.
     *
     * @param string $name The event name
     * @param mixed  $data The data that is passed into the event function
     *
     * @return mixed The filtered data
     */
    public function trigger($name, $data)
    {
        if (!array_key_exists($name, $this->events)) {
            return $data;
        }

        $filtered = null;

        foreach ($this->events[$name] as $event) {
            if (!is_array($data)) {
                $data = array($data);
            }

            $filtered = call_user_func_array($event, $data);
        }

        return $filtered;
    }

    /**
     * Binds the client data to the field and all children.
     *
     * @param mixed $data The data to bind to the field
     */
    public function bind($data)
    {
        if ($this->isMultiple()) {
            if (!is_array($data)) {
                throw new \InvalidArgumentException('The bound data on an multiple field must be an array');
            }

            $choices = array();
            foreach ($data as $index => $value) {
                $choice = $this->copy();
                $choice->setFieldName((string) $index);
                $choice->multiple(false);
                if ($apply = $choice->getApply()) {
                    $apply = \Closure::bind($apply, $choice);
                    $choice->setApply($apply);
                }

                $choices[] = $choice;
            }

            $this->setChildren($choices);
            $this->setApply(function () {
                return DataMapper::fieldToArray($this);
            });
        }

        // Unset data for missing fields.
        if (is_array($data)) {
            foreach ($data as $name => $value) {
                if (!$this->hasChild($name)) {
                    unset($data[$name]);
                }
            }
        }

        if (is_array($data)) {
            $data += $this->getBlankData();
        }

        foreach ($this->children as $child) {
            if (isset($data[$child->getFieldName()])) {
                $child->bind($data[$child->getFieldName()]);
            } elseif ($child->isOptional()) {
                $data[$child->getFieldName()] = null;
            }
        }

        $this->setData($data);
        if (true === $this->validate()) {
            $data = $this->trigger('bind_value', $data);
        }

        $this->setValue($data);
    }

    /**
     * Fills teh data to the field and all children.
     *
     * @param mixed $data The data to fill as value
     */
    public function fill($data)
    {
        $value = $this->unapplyTree($data);
        $this->fillValue($value);
    }

    /**
     * Gets the result by passing two functions. The first one has the current
     * field with errors and the second has the mapped form data as argument.
     *
     * @param Closure $formWithErrors The current field with errors
     * @param Closure $formData       The mapped and valid form data
     *
     * returns mixed The response of the functions
     */
    public function fold(\Closure $formWithErrors, \Closure $formData)
    {
        $data = $this->applyTree();
        if (count($this->getErrorsFlat()) > 0) {
            return $formWithErrors($this);
        }

        return $formData($data);
    }

    /**
     * Applies the tree.
     *
     * @return mixed
     */
    protected function applyTree()
    {
        $apply = $this->apply;

        try {
            $data = call_user_func_array($apply, $this->value);
        } catch (\Exception $e) {
            if (
                false === strpos($e->getMessage(), 'Rucola\{closure}()') &&
                false === strpos($e->getMessage(), 'call_user_func_array')
            ) {
                throw $e;
            }

            throw new \InvalidArgumentException(
                'The form value could not applied to the closure function. '.
                'Propably the bound data does not match your form configuration'
            );
        }

        $this->data = $data;

        foreach ($this->getChildren() as $child) {
            if ($child->isOptionalAndEmpty()) {
                continue;
            }

            if (!$child->hasChildren()) {
                if (is_array($data)) {
                    $data[$child->getFieldName()] = $child->getValue();
                }
            } elseif ($child->hasChildren()) {
                if (is_array($data)) {
                    $data[$child->getFieldName()] = $child->applyTree();
                } elseif (is_object($data)) {
                    $refl = new \ReflectionObject($data);
                    $prop = $refl->getProperty($child->getFieldName());
                    $prop->setAccessible(true);
                    $prop->setValue($data, $child->applyTree());
                }
            }
        }

        return $data;
    }

    /**
     * Unapply tree.
     *
     * @param mixed $data The data which passed though the unapply function.
     *
     * @return mixed
     */
    public function unapplyTree($data)
    {
        if (!is_array($data)) {
            $data = array($data);
        }

        if (false === $this->customUnapply) {
            $data = array('data' => $data);
        }

        $unapply = $this->unapply;
        $value = call_user_func_array($unapply, $data);

        foreach ($this->getChildren() as $child) {
            if ($child->hasChildren()) {
                $value[$child->getFieldName()] = $child->unapplyTree($value[$child->getFieldName()]);
            }
        }

        return $value;
    }

    /**
     * Gets an blank array but with the children array keys.
     *
     * @return array
     */
    public function getBlankData()
    {
        $blank = array();
        foreach ($this->children as $name => $child) {
            $blank[$name] = null;
        }

        return $blank;
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
     * Returns true if the field is optional and the value is empty, otherwise
     * false.
     *
     * @return boolean
     */
    public function isOptionalAndEmpty()
    {
        return (empty($this->value) && true === $this->isOptional());
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
     * Returns a clone of the field with cloned children.
     *
     * @return Field
     */
    public function copy()
    {
        $copy = clone $this;

        $copy->setChildren(array_map(function ($child) {
            return $child->copy();
        }, $copy->getChildren()));

        return $copy;
    }

    /**
     * Validates the current field. And returns true if this field is 
     * valid, otherwise false.
     *
     * @return boolean
     */
    protected function validate()
    {
        foreach ($this->constraints as $constraint) {
            $constraint->check($this);
        }

        return $this->isValid();
    }

    /**
     * Returns true if form has no errors, otherwise false.
     *
     * @return boolean
     */
    public function isValid()
    {
        return count($this->getErrorsFlat()) === 0;
    }

    /**
     * Fills the value to the form.
     *
     * @param mixed $value The value
     */
    protected function fillValue($value)
    {
        if (!is_array($value)) {
            $this->value = $value;
            return;
        }

        foreach ($this->children as $child) {
            if (isset($value[$child->getFieldName()])) {
                $child->fillValue($value[$child->getFieldName()]);
            }
        }

        $this->value = $value;
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
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
    }
}
