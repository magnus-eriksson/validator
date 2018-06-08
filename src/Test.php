<?php namespace Maer\Validator;

class Test
{
    /**
     * Rule sets
     * @var array
     */
    protected $sets;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param RuleSets $set
     * @param mixed    $value
     */
    public function __construct(RuleSets $sets, $value)
    {
        $this->sets  = $sets;
        $this->value = $value;
    }

    /**
     * Call a rule
     *
     * @param  string $method
     * @param  array  $args
     * @return boolean
     */
    public function __call($method, array $args)
    {
        $data = ['value' => $this->value];

        $rule = [
            'rule'   => $method,
            'method' => 'rule' . ucfirst($method),
            'args'   => $args,
        ];

        return $this->sets->runRule($rule, $data, 'value') === true;
    }
}
