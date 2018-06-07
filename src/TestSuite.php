<?php namespace Maer\Validator;

class TestSuite
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var \Maer\Validator\Messages
     */
    protected $messages;

    /**
     * Custom field messages
     * @var array
     */
    protected $fieldMessages = [];

    /**
     * @var ErrorCollection
     */
    protected $errors;

    /**
     * @var Rules\RuleSet
     */
    protected $sets;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * Validation state
     * @var null
     */
    protected $passes;

    /**
     * @param array         &$data
     * @param Messages      $messages
     * @param Rules\RuleSet $sets
     */
    public function __construct(array &$data, RuleSets $sets, Messages $messages)
    {
        $this->data     = $data;
        $this->messages = $messages;
        $this->sets     = $sets;
        $this->errors   = new ErrorCollection;
    }

    /**
     * Get a new param for adding tests
     *
     * @param  string $field
     * @return Param
     */
    public function param($field)
    {
        return $this->params[$field] = new Param($field);
    }

    /**
     * Check if the validation passes or not
     *
     * @return boolean
     */
    public function passes()
    {
        if (!is_null($this->passes)) {
            // We've already run this test.
            // Return the last result.
            return $this->passes;
        }

        $passes = true;

        foreach ($this->params as $field => $param) {
            $response = $this->testParam($field, $param);

            if ($response !== true) {
                $this->errors->addError($field, $response);
                $passes = false;
            }
        }

        return $this->passes = $passes;
    }

    /**
     * Get the error collection
     *
     * @return ErrorCollection
     */
    public function errors()
    {
        // Make sure the validation has been run
        $this->passes();

        return $this->errors;
    }

    /**
     * Set an error message for all errors on a specific field
     *
     * @param  array  $messages
     * @return $this
     */
    public function fieldMessages(array $messages)
    {
        $this->fieldMessages = array_replace_recursive(
            $this->fieldMessages,
            $messages
        );

        return $this;
    }

    /**
     * Test a param
     *
     * @param  string $field
     * @param  Param  $param
     * @return string|boolean
     */
    protected function testParam($field, Param $param)
    {
        if (!array_key_exists($field, $this->data)) {
            if ($param->isRequired()) {
                return $this->messages->getRuleMessage('required', $field, []);
            }

            // The field doesn't exist but isn't required, so let's skip the
            // other tests since they will always fail otherwise
            return true;
        }

        foreach ($param->getRules() as $rule) {
            $response = $this->sets->runRule($rule, $this->data, $field);

            if ($response !== true) {
                // Non true == error.

                // If we have a custom error message on field level,
                // let's override the message using that
                $response = $this->fieldMessages[$field] ?? $response;

                // If we got a string back, use that as an error message
                return is_string($response)
                    ? $this->messages->generate($response, $field, $rule['args'])
                    : $this->messages->getRuleMessage($rule['rule'], $field, $rule['args']);
            }
        }

        return true;
    }
}
