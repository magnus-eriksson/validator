<?php
namespace Maer\Validator;

use Closure;
use InvalidArgumentException;
use Maer\Validator\Errors\ValidationErrors;
use Maer\Validator\Params\Params;
use Maer\Validator\Rules\ValidationRules;
use Maer\Validator\Sets\AbstractSet;
use Maer\Validator\Sets\ClosureSet;
use Maer\Validator\Sets\DefaultSet;

class Validator
{
    /**
     * @var Params
     */
    protected Params $params;

    /**
     * @var ValidationErrors
     */
    protected ValidationErrors $errors;

    /**
     * @var array
     */
    protected array $fieldErrors = [];

    /**
     * @var ValidationRules
     */
    protected ValidationRules $rules;

    /**
     * @var ClosureSet
     */
    protected ClosureSet $closures;

    /**
     * @var array
     */
    protected array $sets = [];


    /**
     * @param array $params
     * @param array $rules
     * @param array $fieldErrors Custom field errors
     */
    public function __construct(array $params, array $rules, array $fieldErrors = [])
    {
        $this->params = new Params($params);
        $this->rules  = new ValidationRules($rules);
        $this->errors = new ValidationErrors;
        $this->fieldErrors = $fieldErrors;
        $this->closures = new ClosureSet;
        $this->addSet(new DefaultSet);
    }


    /**
     * Check if the data is valid
     *
     * @return bool
     */
    public function isValid(): bool
    {
        $success = true;

        foreach ($this->rules->getRules() as $field => $rules) {
            foreach ($rules as $rule => $args) {
                $fieldError = $this->fieldErrors[$field] ?? null;

                if (key_exists($rule, $this->sets) === false) {
                    // Rule doesn't exist, throw an exception
                    throw new InvalidArgumentException("Unknown rule '$rule'");
                }

                if ($this->params->has($field) === false) {
                    // The field doesn't exist.
                    if ($this->rules->isRequired($field)) {
                        // The rule is required but doesn't exist. Set error and
                        // continue to the next field
                        $this->errors->add($field, $fieldError ?? "$field is required");
                        $success = false;
                        break;
                    }

                    // The rule doesn't exist but isn't required, so let's just
                    // continue to the next field
                    continue;
                }

                // Add the field value as the first argument
                array_unshift($args, $this->params->get($field));

                $callback = call_user_func_array($this->sets[$rule], $args);

                if ($callback === true) {
                    // The validation rule passed, continue to next
                    continue;
                }

                // Set success to false since the validation failed
                $success = false;

                if ($fieldError || is_string($callback)) {
                    // We found a custom field error, let's use that
                    $this->errors->add($field, $fieldError ?? $callback);
                    break;
                }

                // No other error messages was found or returned, create a generic one
                $this->errors->add($field, "Validation for $rule failed");
                break;
            }
        }

        return $success;
    }


    /**
     * Get all errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors->all();
    }


    /**
     * Add one or multiple rule sets
     *
     * @param AbstractSet $set
     *
     * @return Validator|array One set or array of sets
     */
    public function addSet(AbstractSet|array $set): Validator
    {
        if (is_array($set)) {
            foreach ($set as $single) {
                $this->addSet($single);
            }

            return $this;
        }

        $set->setParams($this->params);
        $this->sets = array_replace_recursive($this->sets, $set->rules());

        return $this;
    }


    /**
     * Add a closure rule
     *
     * @param string $name
     * @param Closure $closure
     *
     * @return void
     */
    public function addClosure(string $name, Closure $closure): void
    {
        $this->closures->addClosure($name, $closure);
        $this->addSet($this->closures);
    }
}
