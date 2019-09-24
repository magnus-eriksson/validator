<?php namespace Maer\Validator;

use Maer\Validator\Collections\RulesetCollection;
use Maer\Validator\Collections\ValueCollection;

class Chain
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
     * @var array
     */
    protected $rules = [];


    /**
     * @param mixed             $value
     * @param RulesetCollection $rulesets
     */
    public function __construct($value, RulesetCollection $rulesets)
    {
        $this->values   = new ValueCollection(['value' => $value]);
        $this->rulesets = $rulesets;
    }


    /**
     * Add a rule to the rule stack
     *
     * @param  string $method
     * @param  array  $args
     *
     * @return $this
     */
    public function __call($method, array $args): Chain
    {
        $ruleString = $method . ':' . implode(',', $args);
        $this->rules[] = trim($ruleString, ':');

        return $this;
    }


    /**
     * Validate all added rules
     *
     * @return Response
     */
    public function validate(): Response
    {
        $batchTest = new BatchTest(
            ['value' => $this->rules],
            $this->values,
            $this->rulesets
        );

        return $batchTest->run();
    }
}
