<?php namespace Maer\Validator\Collections;

use Maer\Validator\Exceptions\UndefinedRuleException;
use Maer\Validator\Rules\AbstractRuleset;
use Maer\Validator\Rules\DefaultRuleset;

class RulesetCollection
{
    /**
     * @var array
     */
    protected $rulesets = [];


    /**
     * Add a ruleset to the collection
     *
     * @param AbstractRuleset $ruleset
     *
     * @return RulesetCollection
     */
    public function add(AbstractRuleset $ruleset): RulesetCollection
    {
        $className = $this->normalizeClassName(get_class($ruleset));
        $this->rulesets[$className] = $ruleset;

        return $this;
    }


    /**
     * Check if a ruleset exists
     *
     * @param  string  $className
     *
     * @return bool
     */
    public function has(string $className): bool
    {
        $className = $this->normalizeClassName($className);

        return array_key_exists($className, $this->rulesets);
    }


    /**
     * Remove a ruleset
     *
     * @param  string $className
     *
     * @return RulesetCollection
     */
    public function remove(string $className): RulesetCollection
    {
        if ($this->has($className)) {
            $className = $this->normalizeClassName($className);

            unset($this->rulesets[$className]);
        }

        return $this;
    }


    /**
     * Call a rule method from the collection
     *
     * @param  string          $ruleName
     * @param  ValueCollection $values
     * @param  array           ...$args
     *
     * @return bool
     *
     * @throws UndefinedRuleException if the rule was not found in any set
     */
    public function executeRule(string $ruleName, ValueCollection $values, string $fieldName, ...$args)
    {
        if (!$this->has(DefaultRuleset::class)) {
            // By adding this last, we can override any default test
            // with custom rulesets
            $this->add(new DefaultRuleset);
        }

        foreach ($this->rulesets as $key => $set) {
            if (is_callable([$set, $ruleName])) {
                return $set->$ruleName($values, $fieldName, ...$args);
            }
        }

        throw new UndefinedRuleException("The rule '$ruleName' was not found");
    }


    /**
     * Normalize a class name
     *
     * @param  string $name
     *
     * @return string
     */
    protected function normalizeClassName(string $name): string
    {
        return trim($name, '\\');
    }
}
