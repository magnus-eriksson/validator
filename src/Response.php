<?php namespace Maer\Validator;

class Response
{
    /**
     * @var bool
     */
    protected $isValid = true;

    /**
     * @var array
     */
    protected $errors = [];


    /**
     * @param array $errors
     */
    public function __construct(array $errors = [])
    {
        $this->errors  = $errors;
        $this->isValid = count($errors) == 0;
    }


    /**
     * Check if the validation was successful
     *
     * @return bool
     */
    public function valid(string $fieldName = null): bool
    {
        if ($fieldName) {
            return !$this->hasError($fieldName);
        }

        return $this->isValid;
    }


    /**
     * Check if there are any errors
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }


    /**
     * Check if a specific field failed
     *
     * @return bool
     */
    public function hasFieldError(string $fieldName): bool
    {
        return array_key_exists($fieldName, $this->errors);
    }


    /**
     * Get the error message for a specific field
     *
     * @param  string $fieldName
     *
     * @return string|null
     */
    public function fieldError(string $fieldName): ?string
    {
        return $this->errors[$fieldName] ?? null;
    }


    /**
     * Get all errors - [fieldName => errorMessage, ...]
     *
     * @return array
     */
    public function errors(): array
    {
        return $this->errors;
    }


    /**
     * Get all error messages in an indexed array
     *
     * @return array
     */
    public function errorMessages(): array
    {
        return array_values($this->errors);
    }


    /**
     * Get a list of all fields that has errors
     *
     * @return array
     */
    public function errorFields(): array
    {
        return array_keys($this->errors);
    }
}
