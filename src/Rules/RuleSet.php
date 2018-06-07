<?php namespace Maer\Validator\Rules;

abstract class RuleSet
{
    /**
     * The input data
     * @var array
     */
    protected $data = [];

    /**
     * @param  array &$data
     * @return $this
     */
    public function setData(array &$data)
    {
        $this->data = &$data;
        return $this;
    }

    /**
     * Check if a field exists in thte data array
     *
     * @param  string  $field
     * @return boolean
     */
    protected function hasData($field)
    {
        return array_key_exists($field, $this->data);
    }

    /**
     * Get a field
     *
     * @param  string  $field
     * @return boolean
     */
    protected function getData($field)
    {
        return $this->hasData($field)
            ? $this->data[$field]
            : null;
    }
}
