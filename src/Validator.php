<?php namespace Valid;

use Valid\Bags\ErrorsBag;
use Valid\Bags\RulesetsBag;
use Valid\Bags\ValuesBag;
use Valid\Rules\AbstractRuleset;
use Valid\Rules\DefaultRuleset;

class Validator
{
    /**
     * @var ValuesBat
     */
    protected $values;


    /**
     * @var RulesetsBag
     */
    protected $rulesets;

    /**
     * @var ErrorsBag
     */
    protected $errors;

    /**
     * @var array
     */
    protected $rules;


    /**
     * @param iterable $values
     * @param array    $rules
     */
    public function __construct(iterable $values, array $rules)
    {
        $this->values   = new ValuesBag($values);
        $this->errors   = new ErrorsBag;
        $this->rulesets = new RulesetsBag;
        $this->rules    = $rules;

        $this->addRuleset(new DefaultRuleset);
    }


    /**
     * Add a ruleset
     *
     * @param AbstractRuleset $ruleset
     */
    public function addRuleset(AbstractRuleset $ruleset): Validator
    {
        $this->rulesets->register(get_class($ruleset), $ruleset);

        return $this;
    }

    /**
     * Check if the validation passed
     *
     * @return bool
     */
    public function success(): bool
    {
        return true;
    }


    /**
     * Get the errors bag
     *
     * @return ErrorsBag
     */
    public function errors(): ErrorsBag
    {
        return $this->errors;
    }
}
