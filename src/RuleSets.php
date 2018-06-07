<?php namespace Maer\Validator;

use Maer\Validator\Exceptions\UnknownRuleException;

class RuleSets
{
    /**
     * @var array
     */
    protected $sets;

    /**
     * @param array $messages
     */
    public function __construct(array $sets = [])
    {
        $this->sets = $sets;
    }

    /**
     * Add a rule set
     *
     * @param Rules\RuleSet
     */
    public function addRuleSet(Rules\RuleSet $set)
    {
        $this->sets[] = $set;
    }

    /**
     * Run a rule
     *
     * @param  array  $rule
     * @param  array  &$data
     * @param  string $field
     * @return string|boolean
     *
     * @throws \Maer\Validator\Exceptions\UnknownRuleException if rule not found
     */
    public function runRule(array $rule, array &$data, $field)
    {
        #if ('required' == $rule['rule']) {
        #    return array_key_exists($field, $data);
        #}

        foreach ($this->sets as $set) {
            $value = $data[$field] ?? null;

            if (method_exists($set, $rule['method'])) {
                $set->setData($data);
                array_unshift($rule['args'], $value);
                return call_user_func_array([$set, $rule['method']], $rule['args']);
            }
        }

        throw new UnknownRuleException("Rule '{$rule['rule']}' not found");
    }
}
