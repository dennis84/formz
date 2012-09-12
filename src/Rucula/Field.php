<?php

namespace Rucula;

class Field
{
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
        $errors = array();

        foreach ($this->getChildren() as $child) {
            $errors += $child->getErrorsFlat();
        }

        $this->addErrors($errors);
        return $this->errors;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
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

    public function __get($name)
    {
        return $this->getChild($name);
    }

    public function __isset($name)
    {
        return $this->hasChild($name);
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
        if ('root' === $this->parent->getName()) {
            return $this->name;
        }

        return $parent->getNameForField() . '-' . $this->name;
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

    public function setApply(\Closure $apply = null)
    {
        $this->apply = $apply;
    }

    public function setUnapply(\Closure $unapply = null)
    {
        $this->unapply = $unapply;
    }

    public function bind($data = null)
    {
        $this->setValue($data);

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
        $this->type->validate($this);
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

        if (is_object($data)) {
            $data = array('obj' => $data);
        }

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
        $data  = call_user_func_array($apply, $this->value);
        $this->data = $data;

        foreach ($this->getChildren() as $child) {
            if ($child->isOptionalAndEmpty()) {
                continue;
            }

            if ($child->hasChildren()) {
                if (is_array($data)) {
                    $data[$child->getName()] = $child->applyTree();
                }
                elseif (is_object($data)) {
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
}
