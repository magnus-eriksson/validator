<?php namespace Maer\Validator;

interface ValidatorInterface
{
    /**
     * Set and load the language
     *
     * @param string $lang
     */
    public function setLanguage($lang);


    /**
     * Add one or multiple rulesets
     *
     * @param array|Rules\Ruleset   $set    One Ruleset or List of Rulesets
     */
    public function addRuleset($set);


    /**
     * Get a new Tester instance
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @return Tester
     */
    public function make(array $data, array $rules, array $messages = []);
}
