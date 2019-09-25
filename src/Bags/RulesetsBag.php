<?php namespace Valid\Bags;

use Valid\Rules\AbstractRuleset;

class RulesetsBag extends Bag
{
    /**
     * @var bool
     */
    protected $immutable = true;


    /**
     * Add a new ruleset
     *
     * @param string          $key
     * @param AbstractRuleset $value
     */
    public function add(string $key, $ruleset): Bag
    {
        if (!$ruleset instanceof AbstractRuleset) {
            throw new \Exception("Rulesets must inherit " . AbstractRuleset::class);
        }

        parent::add($key, $ruleset);

        return $this;
    }

}
