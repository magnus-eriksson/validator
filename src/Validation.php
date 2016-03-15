<?php namespace Maer\Validator;

class Validation
{
    protected $passes    = null;
    protected $data      = [];
    protected $rules     = [];
    protected $sets      = [];
    protected $errors    = [];
    protected $messages  = [];


    /**
     * @param array $data   Data to validate
     * @param array $rules  Rules to match
     */
    public function __construct(array &$data, array &$rules, array &$messages)
    {
        $this->data     = &$data;
        $this->rules    = &$rules;
        $this->messages = &$messages;
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

        foreach($this->rules as $field => $rules) {
            
            $response = $this->runRules($rules, $field);

            if (!empty($response)) {
                $this->errors[$field] = $response;
            }

        }

        return $this->passes = empty($this->errors);
    }


    /**
     * Get validation errors
     * @return array
     */
    public function getErrors()
    {
        if (is_null($this->passes)) {
            $this->passes();
        }

        return $this->errors;
    }


    /**
     * Add a ruleset
     * @param Ruleset $set
     */
    public function addRuleset(Ruleset $set)
    {
        $set->setData($this->data);
        $this->sets[] = $set;
        return $this;
    }


    /**
     * Get an error message
     * @param  string   $field
     * @return string
     */
    protected function message($field)
    {
        return array_key_exists($field, $this->messages)
            ? $this->messages[$field]
            : '%s rule not met';
    }


    /**
     * Run the set of rules for a field
     * @param  array    $rules
     * @param  string   $field  Name of the field
     * @return string|null
     */
    protected function runRules($rules, $field)
    {
        $input = array_key_exists($field, $this->data)
            ? $this->data[$field] 
            : null;

        $required = in_array('required', $rules) !== false;

        if (!$required && is_null($input)) {
            return;
        }

        foreach($rules as $rule) {

            list($ruleName, $args) = $this->parseRule($rule);
            $method = 'rule' . ucfirst($ruleName);
            array_unshift($args, $input);

            $set = null;
            foreach($this->sets as $ruleSet) {
                if (method_exists($ruleSet, $method)) {
                    $set = $ruleSet;
                    break;
                }
            }
            
            if (!$set) {
                throw new UnknownRuleException("Unknown rule '$method'");
            }

            $response = call_user_func_array([$set, $method], $args);
            if ($response === false) {
                array_shift($args);
                array_unshift($args, $this->message($ruleName), $field);
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
            $args = explode(',', $parts[1]);
        }

        return [$ruleName, $args];
    }


}

