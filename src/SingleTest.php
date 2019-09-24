<?php namespace Maer\Validator;

use Maer\Validator\Collections\ValueCollection;
use Maer\Validator\Collections\RulesetCollection;

class SingleTest
{
    /**
     * @var ValueCollection
     */
    protected $values;

    /**
     * @var RulesetCollection
     */
    protected $rulesets;

    /**
     * @var string
     */
    protected $fieldName;


    /**
     * @param string            $fieldName
     * @param ValueCollection   $values
     * @param RulesetCollection $rulesets
     */
    public function __construct(string $fieldName, ValueCollection $values, RulesetCollection $rulesets)
    {
        $this->fieldName = $fieldName;
        $this->values    = $values;
        $this->rulesets  = $rulesets;
    }


    /**
     * @param  string $method
     * @param  array  $args
     *
     * @return bool
     *
     * @throws UndefinedRuleException if the rule doesn't exist
     */
    public function __call($method, array $args): bool
    {
        $response = $this->rulesets->executeRule($method, $this->values, $this->fieldName, ...$args);

        return $response->success();
    }
}
