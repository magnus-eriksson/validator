<?php namespace Maer\Validator;

use Maer\Validator\Collections\RulesetCollection;
use Maer\Validator\Collections\ValueCollection;
use Maer\Validator\Rules\AbstractRuleset;

class Validator
{
    /**
     * @var RulesetCollection
     */
    protected $rulesets;

    /**
     * Predefined rule groups
     *
     * @var array
     */
    protected $ruleGroups = [];


    public function __construct()
    {
        $this->rulesets = new RulesetCollection;
    }


    /**
     * Add a ruleset
     *
     * @param  AbstractRuleset $ruleset
     *
     * @return $this
     */
    public function addRuleset(AbstractRuleset $ruleset): Validator
    {
        $this->rulesets->add($ruleset);

        return $this;
    }


    /**
     * Remove a ruleset
     *
     * @param  string $className Fully qualified class name (with namespaces)
     *
     * @return $this
     */
    public function removeRuleset(string $className): Validator
    {
        $this->rulesets->remove($className);

        return $this;
    }


    /**
     * Add a rule group
     *  - A rule group is just a list of rules for a specific test
     *
     * @param  string $groupName
     * @param  array  $rules
     *
     * @return Validator
     */
    public function addRuleGroup(string $groupName, array $rules): Validator
    {
        $this->ruleGroups[$groupName] = $rules;

        return $this;
    }


    /**
     * Remove a rule group
     *
     * @param  string $groupName
     *
     * @return Validator
     */
    public function removeRuleGroup(string $groupName): Validator
    {
        if (array_key_exists($groupName, $this->ruleGroups)) {
            unset($this->ruleGroups[$groupName]);
        }

        return $this;
    }


    /**
     * Validate data using an added rule group
     *
     * @param  iterable $values
     * @param  string   $groupName
     *
     * @return Response
     */
    public function useRuleGroup(string $groupName, iterable $values): Response
    {
        if (!array_key_exists($groupName, $this->ruleGroups)) {
            throw new \Exception("The rule group '{$groupName}' was not found");
        }

        return (new BatchTest(
            $this->ruleGroups[$groupName],
            new ValueCollection($values),
            $this->rulesets
        ))->run();
    }


    /**
     * Get a SingleTest instance for a specific value
     *
     * @param  mixed $value
     *
     * @return SingleTest
     */
    public function value($value): SingleTest
    {
        $values = new ValueCollection(['value' => $value]);

        return new SingleTest('value', $values, $this->rulesets);
    }


    /**
     * Validate the values against a list of rules
     *
     * @param  iterable $values
     * @param  array    $rules
     *
     * @return Response
     */
    public function validate(array $rules, iterable $values): Response
    {
        return (new BatchTest(
            $rules,
            new ValueCollection($values),
            $this->rulesets
        ))->run();
    }


    /**
     * Chain rules for a value
     *
     * @param  mixed $value
     *
     * @return Chain
     */
    public function chain($value): Chain
    {
        return new Chain($value, $this->rulesets);
    }
}
