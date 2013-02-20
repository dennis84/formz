<?php

namespace Rucola;

use Rucola\Util\DataMapper;
use Rucola\Util\RecursiveFieldIterator;

/**
 * Field.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Field implements \IteratorAggregate, \ArrayAccess
{
    use Extensions\Constraints;
    use Extensions\Symfonify;

    protected $name;
    protected $root = false;
    protected $constraints = [];
    protected $children = [];
    protected $errors = [];
    protected $events = [];
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

    /**
     * Gets the field errors.
     *
     * @return array
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
     * Sets the applied data.
     *
     * @param mixed $data The data
     */
    public function setData($data)
    {
        $data = $this->trigger('change_data', $data);
        $this->data = $data;
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

        foreach ($this->events[$name] as $event) {
            if (!is_array($data)) {
                $data = [$data];
            }

            $data = call_user_func_array($event, $data);
        }

        return $data;
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

        foreach ($this->children as $child) {
            if (is_array($data) && array_key_exists($child->getFieldName(), $data)) {
                $child->bind($data[$child->getFieldName()]);
            }
        }

        $this->setValue($data);
    }

    /**
     * Fills the data to the field and all children.
     *
     * @param mixed $data The data to fill as value
     */
    public function fill($data)
    {
        $this->unapplyTree($data);
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
        if ($this->hasErrors()) {
            return $formWithErrors($this);
        }

        return $formData($data);
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
        $this->maybePrepareMultipleFields($data);

        if (!$this->hasChildren()) {
            $this->setValue($data);
            return $data;
        }

        if (!is_array($data)) {
            $data = [$data];
        }

        if (false === $this->customUnapply) {
            $data = ['data' => $data];
        }

        $unapply = $this->unapply;
        $appliedData = call_user_func_array($unapply, $data);
        $value = [];

        foreach ($this->getChildren() as $child) {
            if (isset($appliedData[$child->getFieldName()])) {
                $value[$child->getFieldName()] = $child->unapplyTree($appliedData[$child->getFieldName()]);
            }
        }

        $this->setValue($value);

        return $value;
    }

    /**
     * Applies all constraints of current field.
     */
    public function validate()
    {
        foreach ($this->constraints as $constraint) {
            $constraint->check($this);
        }
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

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \RecursiveIteratorIterator(
            new RecursiveFieldIterator($this),
            \RecursiveIteratorIterator::SELF_FIRST
        );
    }

    /**
     * Executes the apply function of all fields with their values.
     *
     * @return mixed
     */
    protected function applyTree()
    {
        foreach ($this->reverseFields() as $field) {
            $field->validate();

            if ($field->isOptionalAndEmpty()) {
                $field->setData(null);
                continue;
            }

            if (!$field->hasChildren()) {
                $field->setData($field->getValue());
                continue;
            }

            $data = [];
            foreach ($field->getChildren() as $child) {
                $data[$child->getFieldName()] = $child->getData();
            }

            $apply = $field->getApply();
            $data = call_user_func_array($apply, $data);
 
            $field->setData($data);
        }

        return $this->getData();
    }

    /**
     * Gets an blank array but with the children array keys.
     *
     * @return array
     */
    public function getBlankData()
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
     * Generates a sorted array of field objects starting from the last with the
     * highest depth.
     *
     * @Returns array
     */
    protected function reverseFields()
    {
        $flatFields = [];
        $iterator = $this->getIterator();
        foreach ($iterator as $field) {
            $flatFields[$iterator->getDepth()][] = $field;
        }

        krsort($flatFields);
        $reverse = [];

        foreach ($flatFields as $children) {
            foreach ($children as $field) {
                $reverse[] = $field;
            }
        }

        $reverse[] = $this;
        return $reverse;
    }

    /**
     * Prepares the current field if multiple is true.
     *
     * @param mixed $data The bind or fill data
     *
     * @throws InvalidArgumentException If multiple is true and data in not an array
     */
    protected function maybePrepareMultipleFields($data)
    {
        if ($this->isMultiple()) {
            if (!is_array($data)) {
                throw new \InvalidArgumentException('The bound data on an multiple field must be an array');
            }

            $choices = [];
            foreach ($data as $index => $value) {
                $choice = $this->copy();
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
            $this->setApply(function () {
                return DataMapper::fieldToArray($this);
            });

            $this->setUnapply(function ($data) {
                return $data;
            });

            $this->customUnapply = false;
        }
    }
}
