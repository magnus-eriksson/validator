<?php
namespace Maer\Validator\Errors;

class ValidationErrors
{
    /**
     * @var array
     */
    protected array $errors = [];


    /**
     * Set an error message for a field
     *
     * @param string|int $field
     * @param string $message
     *
     * @return ValidationErrors
     */
    public function add(string|int $field, string $message): ValidationErrors
    {
        $this->errors[$field] = $message;

        return $this;
    }


    /**
     * Check if a specific field has an error
     *
     * @param string|int $field
     *
     * @return bool
     */
    public function has(string|int $field)
    {
        return key_exists($field, $this->errors);
    }


    /**
     * Get all errors
     *
     * @return array
     */
    public function all(): array
    {
        return $this->errors;
    }


    /**
     * Check if there are any errors
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return empty($this->errors) === false;
    }
}
