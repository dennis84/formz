<?php

namespace Rucula\Builder;

class Tuple extends AbstractBuilder
{
    protected $dataMapper;

    public function __construct($dataMapper)
    {
        $this->dataMapper = $dataMapper;
    }

    public function build(array $fields, \Closure $apply = null, \Closure $unapply = null)
    {
        $dataMapper = $this->dataMapper;
        $root       = $this->buildFieldTree($fields);

        if (!$apply) {
            $root->setApply(function () use ($root, $dataMapper) {
                return $dataMapper->fieldToArray($root);
            });
        } else {
            $root->setApply($apply);
        }

        if (!$unapply) {
            $root->setUnapply(function () {});
        } else {
            $root->setUnapply($unapply);
        }

        return $root;
    }
}
