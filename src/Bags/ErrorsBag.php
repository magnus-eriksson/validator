<?php namespace Valid\Bags;

class ErrorsBag extends Bag
{
    /**
     * Get all error messages as an indexed array
     *
     * @return array
     */
    public function messages(): array
    {
        return array_values($this->items);
    }


    /**
     * Get all fields that has errors
     *
     * @return array
     */
    public function fields(): array
    {
        return array_key_exists($this->items);
    }
}
