<?php namespace Maer\Validator;

class Errors
{
    /**
     * @var array
     */
    protected $errors = [];


    /**
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }


    /**
     * Get the error message for a field
     * @param  string   $field
     * @return string|false
     */
    public function get($field)
    {
        return $this->has($field)
            ? $this->errors[$field]
            : false;
    }


    /**
     * Get all error messages
     * @return array
     */
    public function all()
    {
        return $this->errors;
    }


    /**
     * Get a list of all fields that has an error message
     * @return array
     */
    public function fields()
    {
        return array_keys($this->errors);
    }


    /**
     * Check if a field has an error message
     * @param  string   $field
     * @return boolean
     */
    public function has($field)
    {
        return array_key_exists($field, $this->errors);
    }
}
