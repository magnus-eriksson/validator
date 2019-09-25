<?php namespace Valid\Test;

use Valid\Bags\RulesetsBag;
use Valid\Bags\ValuesBag;

class Test
{
    /**
     * @var RulesetsBag
     */
    protected $rulesets;

    /**
     * @var ValuesBag
     */
    protected $values;


    public function __construct(ValuesBag $values, RulesetsBag $rulesets)
    {
        $this->rulesets = $rulesets;
        $this->values   = $values;
    }

    public function __invoke(string $field, string $ruleString)
    {

    }
}
