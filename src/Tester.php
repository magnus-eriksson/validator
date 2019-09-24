<?php namespace Maer\Validator;

use Maer\Validator\Collections\RulesetCollection;
use Maer\Validator\Collections\ValueCollection;

class Tester
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
     * @param iterable          $values
     * @param Rules
     */
    public function __construct(iterable $values, RulesetCollection $rulesets = null)
    {
        $this->values   = new ValueCollection($values);
        $this->rulesets = $rulesets ?: new RulesetCollection;
    }


    /**
     * Get a SingleTest instance for a specific field
     *
     * @param  string $fieldName
     *
     * @return SingleTest
     */
    public function field(string $fieldName)
    {
        return new SingleTest($fieldName, $this->values, $this->rulesets);
    }


    /**
     * Get a SingleTest instance for a specific value
     *
     * @param  mixed $value
     *
     * @return SingleTest
     */
    public function value($value)
    {
        $values = new ValueCollection(['value' => $value]);

        return new SingleTest('value', $values, $this->rulesets);
    }


    /**
     * Validate the values against a list of rules
     *
     * @return Response
     */
    public function validate(array $rules)
    {
        $batchTest = new BatchTest($rules, $this->values, $this->rulesets);

        return $batchTest->run();
    }
}
