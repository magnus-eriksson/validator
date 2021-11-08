<?php
namespace Maer\Validator\Rules;

class ValidationRules
{
    /**
     * @var array
     */
    protected array $rules = [];

    /**
     * @var array
     */
    protected array $required = [];


    /**
     * @param array $rules
     */
    public function __construct(array &$rules)
    {
        $this->parseRules($rules);
    }


    /**
     * Check if the field is required
     *
     * @param string|int $field
     *
     * @return bool
     */
    public function isRequired(string|int $field): bool
    {
        return isset($this->required[$field]);
    }


    /**
     * Get list of validation rules
     *
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }


    /**
     * Parse the list of validation rules
     *
     * @param array $rules
     *
     * @return void
     */
    protected function parseRules(array &$rules): void
    {
        foreach ($rules as $field => $fieldRules) {
            foreach ($fieldRules as $ruleString) {
                if (!is_string($ruleString) || empty($ruleString)) {
                    continue;
                }

                [$rule, $args] = $this->getRuleNameAndArgs($ruleString);

                if ($rule === 'required') {
                    $this->required[$field] = true;
                    continue;
                }

                $this->rules[$field][$rule] = $args;
            }
        }
    }


    /**
     * Parse the rule strings
     *
     * @param string $ruleString
     *
     * @return array
     */
    protected function getRuleNameAndArgs(string $ruleString): array
    {
        $parts = explode(':', $ruleString, 2);

        return [
            $parts[0],
            str_getcsv($parts[1] ?? '')
        ];
    }
}
