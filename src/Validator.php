<?php namespace Maer\Validator;

class Validator
{
    protected $sets      = [];
    protected $messages  = [];
    protected $ruleSets  = [];


    /**
     * @param array $lang   Language file for the error messages
     */
    public function __construct($lang = 'en')
    {
        $this->setLanguage($lang);
    }

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

            foreach($set as $rs) {
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
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @return Tester
     */
    public function make(array $data, array $rules, array $messages = [])
    {
        if(!is_array($data)) // if data been passed as std objects convert them back to array
            $data = (array)$data;

        /*
        *
        * This allows rules to be defined all at once as an array value seperated by a "|"
        * E.g:
        * $rules = [
        *   'name'       => 'required|minLength:2|maxLength:32',
        *   'status'     => 'required|in:cool,awesome,something else',
        *   'email'      => 'required|email',
        *   'won_fights' => 'required|integer|as:Won fights'
        * ];
        *
        */
        foreach($rules as $field => $markedRules)
        {
            if(!is_string($markedRules)) // skip if rules are defined as array keys ['required', 'email']
                continue;

            $tempRules = [];

            foreach(explode('|', $markedRules) as $rule)
            {
                if(substr($rule, 0, 3) == 'as:')
                {
                    $alias           = explode('as:', $rule, 2)[1];
                    $tempRules['as'] = $alias;
                }
                else
                    $tempRules[] = $rule;
            }

            $rules[$field] = $tempRules;
        }

        $validation = new Tester(
            $data, 
            $rules, 
            array_merge($this->messages, $messages)
        );
        
        return $validation->addRuleset($this->ruleSets);

    }
}

