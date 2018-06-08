<?php namespace Maer\Validator;

class Validator
{
    /**
     * @var \Maer\Validator\Messages
     */
    protected $messages;

    /**
     * @var array
     */
    protected $sets;

    /**
     * @param array $lang Messages for the default rules
     */
    public function __construct()
    {
        $this->messages = new Messages(
            include __DIR__ . '/messages/en.php'
        );

        $this->sets = new RuleSets([new Rules\Rules]);
    }

    /**
     * Add messages
     *
     * @param array $messages
     */
    public function addMessages(array $messages)
    {
        $this->messages->addMessage($messages);
    }

    /**
     * Add a rule set
     *
     * @param Rules\RuleSet $set
     */
    public function addRuleSet(Rules\RuleSet $set)
    {
        $this->sets->addRuleSet($set);
    }

    /**
     * Get a new TestSuite to start validation
     *
     * @param  array $data
     * @param  array $rules Pass this for alternative syntax
     * @return TestSuite
     */
    public function make(array $data, array $rules = [])
    {
        if ($rules) {
            return $this->alternativeSyntax($data, $rules);
        }

        return new TestSuite($data, $this->sets, $this->messages);
    }

    /**
     * Test a value against a rule straight away
     *
     * @param  mixed  $value
     * @param  string $ruleInfo
     * @return boolean
     */
    public function test($value)
    {
        return new Test($this->sets, $value);

        /**
        $suite = $this->make(['test' => $value]);
        $param = $suite->param('test');

        call_user_func_array([$param, $rule], $args);

        return $suite->passes();
        */
    }

    /**
     * Use array syntax for the rules
     *
     * @param  array $data
     * @param  array $rules
     * @return TestSuite
     */
    protected function alternativeSyntax($data, $rules)
    {
        $suite = $this->make($data);

        foreach ($rules as $field => $item) {
            $param = $suite->param($field);

            foreach ($item as $itemRule) {
                $parts = explode(':', $itemRule);
                $rule  = $parts[0];
                $args  = explode(',', $parts[1] ?? '');

                call_user_func_array([$param, $rule], $args);
            }
        }

        return $suite;
    }
}
