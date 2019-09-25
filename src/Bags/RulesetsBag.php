<?php namespace Valid\Bags;

use Valid\Rules\AbstractRuleset;

class RulesetsBag extends Bag
{
    /**
     * @var bool
     */
    protected $immutable = true;


    /**
     * Add a ruleset to the collection
     *
     * @param  AbstractRuleset $ruleset
     *
     * @return $this
     */
    public function register(string $key, AbstractRuleset $ruleset): Bag
    {
        $key = get_class($ruleset);
        parent::add($key, $ruleset);

        return $this;
    }


    public function add(string $key, $value): Bag
    {
        throw new \Exception("Use RulesetsBag::register to add new rulesets");
    }
}
