<?php namespace Maer\Validator;

class Tester
{
    protected $passes    = null;
    protected $data      = [];
    protected $rules     = [];
    protected $sets      = [];
    protected $messages  = [];
    protected $errors;


    /**
     * @param array $data   Data to validate
     * @param array $rules  Rules to match
     */
    public function __construct(array $data, array $rules, array $messages = [])
    {
        $this->data     = $data;
        $this->rules    = $rules;
        $this->messages = $messages;
    }


    /**
     * Check if validation passes
     * @return boolean
     */
    public function passes()
    {
        if (!is_null($this->passes)) {
            return $this->passes;
        }

        $errors = [];
        foreach ($this->rules as $field => $rules) {
            if (!is_array($rules)) {
                throw new Exceptions\InvalidFormatException('Excpected Array, got ' . gettype($rules));
            }

            $name = $field;
            if (array_key_exists('as', $rules)) {
                $name = $rules['as'];
                unset($rules['as']);
            }

            $response = $this->runRules($rules, $field, $name);

            if (!empty($response)) {
                $errors[$field] = $response;
            }
        }

        $this->errors = new Errors($errors);
        return $this->passes = empty($errors);
    }


    /**
     * Get validation errors
     * @return array
     */
    public function errors()
    {
        if (is_null($this->passes)) {
            $this->passes();
        }

        return $this->errors;
    }


    /**
     * Get a property
     * @param  string   $prop
     * @return Errors|null
     */
    public function __get($prop)
    {
        // We will only return the $this->errors instance.
        // There is no real reason for this function other than
        // how it looks.
        if ($prop == 'errors') {
            return $this->errors();
        }

        throw new \Exception("Unknown property: '{$name}'");
    }


    /**
     * Add one or multiple rulesets
     *
     * @param  array|Rules\Ruleset  $set  One Ruleset or List of Rulesets
     * @return $this
     */
    public function addRuleset($set)
    {
        if ($set instanceof Rules\Ruleset) {
            $set->setData($this->data);
            $this->sets[] = $set;
        } else if (is_array($set)) {
            foreach ($set as $rs) {
                if (!$rs instanceof Rules\Ruleset) {
                    throw new Exceptions\InvalidTypeException(
                        "Rulesets must extend 'Maer\Validator\Rules\Ruleset'"
                    );
                }

                $rs->setData($this->data);
                $this->sets[] = $rs;
            }
        } else {
            throw new Exceptions\InvalidTypeException(
                "Rulesets must extend 'Maer\Validator\Rules\Ruleset'"
            );
        }

        return $this;
    }


    /**
     * Get an error message
     * @param  string   $field
     * @param  string   $fallback   Returned if no message is found
     * @return string
     */
    protected function message($field, $fallback)
    {
        return array_key_exists($field, $this->messages)
            ? $this->messages[$field]
            : $fallback;
    }


    /**
     * Run the set of rules for a field
     * @param  array    $rules
     * @param  string   $field  Name of the field
     * @return string|null
     */
    protected function runRules($rules, $field, $name)
    {
        // Get the field value from the data array
        $value = array_key_exists($field, $this->data)
            ? $this->data[$field]
            : null;

        // Check if we have a 'required' rule
        $required = in_array('required', $rules) !== false;
        if (!$required && !array_key_exists($field, $this->data)) {
            // If the field isn't required, skip rule checking if the
            // field doesn't exist.
            return;
        }

        if ($required && !array_key_exists($field, $this->data)) {
            return sprintf($this->message('required', "The field %s is invalid"), $field);
        }

        // Check if we allow empty values (null, 0 etc...)
        $allowEmpty = in_array('allowEmpty', $rules) !== false;
        if ($allowEmpty && empty($value)) {
            return;
        }

        // The above rules are meta rules without any real implementations
        $metaRules = ['required', 'allowEmpty'];

        foreach ($rules as $rule) {
            if (in_array($rule, $metaRules)) {
                // Just ignore the meta rules
                continue;
            }

            list($ruleName, $args) = $this->parseRule($rule);

            $method = 'rule' . ucfirst($ruleName);

            // Prepend the value to the arguments list so we can
            // use the call_user_func_array with all the arguments required
            array_unshift($args, $value);

            // Loop through all registered rule sets and use the first
            // set we find that has this rule
            $set = null;
            foreach ($this->sets as $ruleSet) {
                if (method_exists($ruleSet, $method)) {
                    $set = $ruleSet;
                    break;
                }
            }

            if (!$set) {
                throw new Exceptions\UnknownRuleException("Unknown rule '$method'");
            }

            $response = call_user_func_array([$set, $method], $args);
            if ($response !== true) {
                // The rule validation failed

                $message = is_string($response)
                    ? $this->message($ruleName, $response)
                    : $this->message($ruleName, "The field %s is invalid");

                // Remove the first element (the field value) from the args list.
                array_shift($args);

                // Prepend the array with the message so we can use sprintf to
                // inject the field name and use other args.
                array_unshift($args, $message, $name);

                return call_user_func_array('sprintf', $args);
            }
        }
    }


    /**
     * Parse a rule and parameters
     * @param  string   $rule
     * @return array
     */
    protected function parseRule($rule)
    {
        $parts    = explode(':', $rule, 2);
        $ruleName = $parts[0];
        $args     = [];

        if (!empty($parts[1])) {
            if ($ruleName == 'regex') {
                $args = [$parts[1]];
            } else {
                $args = explode(',', $parts[1]);
            }
        }

        return [$ruleName, $args];
    }
}
