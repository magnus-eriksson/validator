<?php namespace Maer\Validator;

interface TesterInterface
{
    /**
     * Check if validation passes
     * @return boolean
     */
    public function passes();


    /**
     * Get validation errors
     * @return array
     */
    public function errors();


    /**
     * Get a property
     * @param  string   $prop
     * @return Errors|null
     */
    public function __get($prop);


    /**
     * Add one or multiple rulesets
     *
     * @param  array|Rules\Ruleset  $set  One Ruleset or List of Rulesets
     * @return $this
     */
    public function addRuleset($set);
}
