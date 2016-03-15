<?php namespace Maer\Validator;

abstract class Ruleset
{
    protected $data = [];

    public function setData(array &$data)
    {
        $this->data = &$data;
        return $this;
    }

    protected function hasData($field)
    {
        return array_key_exists($field, $this->data);
    }

    protected function getData($field)
    {
        return array_key_exists($field, $this->data)
            ? $this->data[$field]
            : null;
    }

}