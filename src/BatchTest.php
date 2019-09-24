<?php namespace Maer\Validator;

use Maer\Validator\Collections\ValueCollection;
use Maer\Validator\Collections\RulesetCollection;
use Maer\Validator\Exceptions\InvalidRuleResponseException;

class BatchTest
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
     * @var Response
     */
    protected $response;


    /**
     * @param array             $rules;
     * @param ValueCollection   $values
     * @param RulesetCollection $rulesets
     */
    public function __construct(array $rules, ValueCollection $values, RulesetCollection $rulesets)
    {
        $this->rules     = $rules;
        $this->values    = $values;
        $this->rulesets  = $rulesets;
    }


    /**
     * Run all the rules
     *
     * @return Response
     *
     * @throws UndefinedRuleException if a rule isn't found
     */
    public function run()
    {
        if ($this->response) {
            return $this->response->isValid();
        }

        $errors  = [];

        $virtualRules = [
            'required',
        ];

        foreach ($this->rules as $field => $rules) {
            $niceFieldName = $field;

            if (!empty($rules['as'])) {
                $niceFieldName = $rules['as'];
                unset($rules['as']);
            }

            if (!$this->values->has($field)) {
                if (in_array('required', $rules)) {
                    // Since it's required but doesn't exist, let's add an error
                    // message for it
                    $errors[$field] = "The field {$field} is required";
                }

                // If the value doesn't exist, there's no reason to test it
                // against any rules
                continue;
            }

            foreach ($rules as $ruleString) {
                [$method, $args] = $this->parseRuleString($ruleString);

                if (in_array($method, $virtualRules)) {
                    // Virtual rules doesn't have any real rule methods so
                    // let's skip them
                    continue;
                }

                $response = $this->rulesets->executeRule($method, $this->values, $field, ...$args);

                if (!$response instanceof SingleTestResponse) {
                    throw new InvalidRuleResponseException('Expected: ' . SingleTestResponse::class . '. Got: ' . (is_object($response) ? get_class($response) : gettype($response)));
                }

                if (!$response->success()) {
                    $message = $response->error();
                    $message = $message ?: "The field %s failed on the rule {$method}";
                    $errors[$field] = sprintf($response->error(), $niceFieldName, ...$args);
                }
            }
        }

        return $this->response = new Response($errors);
    }


    /**
     * Parse the rule string
     *
     * @param  string $ruleString
     *
     * @return array
     */
    protected function parseRuleString($ruleString)
    {
        $parts    = explode(':', $ruleString, 2);
        $parts[1] = explode(',', $parts[1] ?? null);

        return $parts;
    }
}
