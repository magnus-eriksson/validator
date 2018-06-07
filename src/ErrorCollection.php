<?php namespace Maer\Validator;

class ErrorCollection
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * Get the error message for a field
     *
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
     * Add an error message to the collection
     *
     * @param string $field
     * @param string $message
     */
    public function addError($field, $message)
    {
        $this->errors[$field] = $message;
    }

    /**
     * Get all error messages field => message
     *
     * @return array
     */
    public function all()
    {
        return $this->errors;
    }

    /**
     * Get a list of all fields that has an error message
     *
     * @return array
     */
    public function fields()
    {
        return array_keys($this->errors);
    }

    /**
     * Get a list of all messages without the field names
     *
     * @return array
     */
    public function messages()
    {
        return array_values($this->errors);
    }

    /**
     * Check if a field has an error message
     *
     * @param  string   $field
     * @return boolean
     */
    public function has($field)
    {
        return array_key_exists($field, $this->errors);
    }
}
