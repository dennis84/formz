<?php

namespace Rucola;

use Rucola\Type\FormType;

class Field implements \ArrayAccess
{
    use Twigable;

    protected $name;
    protected $value;
    protected $type;
    protected $parent;
    protected $children = array();
    protected $errors = array();
    protected $apply;
    protected $unapply;
    protected $data;
    protected $optional = false;
    protected $multiple = false;
    protected $root;
    protected $prototype;

    public function __construct($name, $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function addError(Error $error)
    {
        $this->errors[] = $error;
    }

    public function addErrors(array $errors)
    {
        foreach ($errors as $error) {
            $this->addError($error);
        }
    }

    public function getErrorsFlat()
    {
        $errors = $this->errors;

        foreach ($this->getChildren() as $child) {
            $errors += array_merge($child->getErrorsFlat(), $errors);
        }

        return $errors;
    }

    public function hasErrors()
    {
        return count($this->getErrorsFlat()) > 0;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setRoot($root)
    {
        $this->root = $root;
    }

    public function isRoot()
    {
        return $this->root;
    }

    public function addChild($field)
    {
        $this->children[$field->getName()] = $field;
    }

    public function getChild($name)
    {
        if (!$this->hasChild($name)) {
            throw new \InvalidArgumentException(sprintf('There was no child with name "%s" registered.', $name));
        }

        return $this->children[$name];
    }

    public function hasChild($name)
    {
        return array_key_exists($name, $this->children);
    }

    public function setChildren(array $children)
    {
        $this->children = array();
        foreach ($children as $child) {
            $this->addChild($child);
        }
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function hasChildren()
    {
        return count($this->children) > 0 ? true : false;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getNameForField()
    {
        if ($this->parent->isRoot()) {
            return $this->name;
        }

        return $parent->getNameForField() . '.' . $this->name;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setOptional($optional)
    {
        $this->optional = $optional;
    }

    public function isOptional()
    {
        return (boolean) $this->optional;
    }

    public function isOptionalAndEmpty()
    {
        return (boolean) (empty($this->value) && $this->isOptional());
    }

    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;
    }

    public function isMultiple()
    {
        return (boolean) $this->multiple;
    }

    public function setPrototype($prototype)
    {
        $this->prototype = $prototype;
    }

    public function setApply(\Closure $apply = null)
    {
        $this->apply = $apply;
    }

    public function getApply()
    {
        return $this->apply;
    }

    public function setUnapply(\Closure $unapply = null)
    {
        $this->unapply = $unapply;
    }

    public function getUnapply()
    {
        return $this->unapply;
    }

    public function bind($data = null)
    {
        if ($this->isMultiple()) {
            foreach ($data as $index => $value) {
                $choice = $this->prototype->copy();
                $choice->setName($index);
                $this->addChild($choice);
            }
        }

        if (is_array($data)) {
            $data += $this->getBlankData();
        }

        foreach ($this->children as $child) {
            if (isset($data[$child->getName()])) {
                $child->bind($data[$child->getName()]);
            } elseif ($child->isOptional()) {
                $data[$child->getName()] = null;
            }
        }

        $this->setValue($data);
        $this->validate();
    }

    public function fill($data)
    {
        $value = $this->unapplyTree($data);
        $this->fillValue($value);
    }

    public function validate()
    {
        if (true === $this->type->validate($this->getValue())) {
            $this->type->onValid($this);
            return;
        }

        $this->type->onInvalid($this);
    }

    public function fold(\Closure $formWithErrors, \Closure $formData)
    {
        $data = $this->applyTree();
        if (count($this->getErrorsFlat()) > 0) {
            return $formWithErrors($this);
        }

        return $formData($data);
    }

    private function unapplyTree($data)
    {
        $unapply = $this->unapply;
        $value = call_user_func_array($unapply, $data);

        foreach ($this->getChildren() as $child) {
            if ($child->hasChildren()) {
                $value[$child->getName()] = $child->unapplyTree($value[$child->getName()]);
            }
        }

        return $value;
    }

    private function applyTree()
    {
        $apply = $this->apply;

        try {
            $data = call_user_func_array($apply, $this->value);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException(
                'The form value could not applied to the closure function. '.
                'Propably the bound data does not match your form configuration'.
                'If the form field is optional then wrap the field with the "$rucola->optional" method.'
            );
        }

        $this->data = $data;

        foreach ($this->getChildren() as $child) {
            if ($child->isOptionalAndEmpty()) {
                continue;
            }

            if (!$child->hasChildren()) {
                if (is_array($data)) {
                    $data[$child->getName()] = $child->getValue();
                }
            } elseif ($child->hasChildren()) {
                if (is_array($data)) {
                    $data[$child->getName()] = $child->applyTree();
                } elseif (is_object($data)) {
                    $refl = new \ReflectionObject($data);
                    $prop = $refl->getProperty($child->getName());
                    $prop->setAccessible(true);
                    $prop->setValue($data, $child->applyTree());
                }
            }
        }

        return $data;
    }

    private function fillValue($value)
    {
        if (!is_array($value)) {
            $this->value = $value;
        }

        foreach ($this->children as $child) {
            if (isset($value[$child->getName()])) {
                $child->fillValue($value[$child->getName()]);
            }
        }

        $this->value = $value;
    }

    public function getBlankData()
    {
        $blank = array();
        foreach ($this->children as $name => $child) {
            $blank[$name] = null;
        }

        return $blank;
    }

    public function copy()
    {
        $copy = clone $this;

        $copy->setChildren(array_map(function ($child) {
            return $child->copy();
        }, $copy->getChildren()));

        return $copy;
    }

    public function offsetGet($offset)
    {
        return $this->getChild($offset);
    }

    public function offsetSet($offset, $value)
    {
    }

    public function offsetExists($offset)
    {
    }

    public function offsetUnset($offset)
    {
    }
}
