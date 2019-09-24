<?php namespace Maer\Validator\Collections;

class ValueCollection
{
    /**
     * @var array
     */
    protected $values = [];


    /**
     * @param iterable $values
     */
    public function __construct(iterable $values)
    {
        if (!is_array($values)) {
            $values = (array)$values;
        }

        $this->values = $values;
    }


    /**
     * Get a value from the collection
     *
     * @param  string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        return array_key_exists($key, $this->values)
            ? $this->values[$key]
            : null;
    }



    /**
     * Check if an item exists
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function has(string $key)
    {
        return array_key_exists($key, $this->values);
    }
}
