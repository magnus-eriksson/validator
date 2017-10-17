<?php namespace Maer\Validator;

class Validator implements ValidatorInterface
{
    /**
     * Error messages
     * @var array
     */
    protected $messages  = [];

    /**
     * Added rule sets
     * @var array
     */
    protected $ruleSets  = [];


    /**
     * @param array $lang   Language file for the error messages
     */
    public function __construct($lang = 'en')
    {
        $this->setLanguage($lang);
    }


    /**
     * Set and load the language
     *
     * @param string $lang
     */
    public function setLanguage($lang)
    {
        $path = __DIR__ . '/../lang/';
        $this->messages = is_file($path.$lang.'.php')
            ? include $path.$lang.'.php'
            : include $path.'en.php';

        $this->addRuleset(new Rules\Rules);
    }


    /**
     * Add one or multiple rulesets
     *
     * @param array|Rules\Ruleset   $set    One Ruleset or List of Rulesets
     */
    public function addRuleset($set)
    {
        if ($set instanceof Rules\Ruleset) {
            $this->ruleSets[] = $set;
        } else if (is_array($set)) {
            foreach ($set as $rs) {
                if (!$rs instanceof Rules\Ruleset) {
                    throw new Exceptions\InvalidTypeException(
                        "Rulesets must extend 'Maer\Validator\Rules\Ruleset'"
                    );
                }

                $this->ruleSets[] = $rs;
            }
        }
    }

    /**
     * Get a new Tester instance
     *
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @return TesterInterface
     */
    public function make(array $data, array $rules, array $messages = [])
    {
        $validation = new Tester(
            $data,
            $rules,
            array_merge($this->messages, $messages)
        );

        return $validation->addRuleset($this->ruleSets);
    }


    /**
     * Test one rule directly
     *
     * @param  string  $rule
     * @param  mixed   $value
     * @param  boolean $returnErrorMessage
     * @return boolean|message
     */
    public function test($rule, $value, $returnErrorMessage = false)
    {
        $tester = $this->make([$rule => $value], [$rule => [$rule]]);
        if ($tester->passes()) {
            return true;
        }

        $errors = $returnErrorMessage ? $tester->errors->all() : [];

        return $errors ? array_pop($errors) : false;
    }
}
