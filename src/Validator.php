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

        $this->sets = new RuleSets([
            new Rules\Rules
        ]);
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
     * @param  array  $data
     * @return TestSuite
     */
    public function make(array $data)
    {
        return new TestSuite($data, $this->sets, $this->messages);
    }
}
