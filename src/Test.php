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

    public function test(string $field, string $ruleString)
    {
        [$method, $args] = $this->parseRuleString($ruleString);

        $result = $this->rulesets->runRule($this->values, $field, $method, $args);

        dd($result);
    }


    /**
     * Parse a rule string into rule method and arguments
     *
     * @param  string $ruleString
     *
     * @return array
     */
    protected function parseRuleString(string $ruleString)
    {
        $parts = explode(':', $ruleString, 2);

        if (!empty($parts[1])) {
            $parts[1] = explode(',', $parts[1]);
        }

        return [
            $parts[0],      // Rule name
            $parts[1] ?? [] // Rule arguments
        ];
    }
}
