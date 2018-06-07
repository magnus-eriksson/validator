<?php namespace Maer\Validator;

class Param
{
    /**
     * @var string
     */
    protected $key;

    /**
     * Registered rules
     * @var array
     */
    protected $rules = [];

    /**
     * @var boolean
     */
    protected $required = false;

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Add a rule
     *
     * @param  string $rule
     * @param  array  $args
     * @return $this
     */
    public function __call($rule, array $args)
    {
        if ('required' == $rule) {
            $this->required = true;
            return $this;
        }

        $this->rules[$rule] = [
            'rule'   => $rule,
            'method' => 'rule' . ucfirst($rule),
            'args'   => $args,
        ];

        return $this;
    }

    /**
     * Check if the param is required
     *
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }


    /**
     * Get all registered rules
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }
}
