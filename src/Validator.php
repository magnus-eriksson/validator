<?php namespace Maer\Validator;

class Validator
{
    protected $sets      = [];
    protected $messages  = [];


    /**
     * @param array $data   Data to validate
     * @param array $rules  Rules to match
     */
    public function __construct()
    {
        $this->messages = include __DIR__ . '/messages.php';
    }


    /**
     * Get a new validator instance
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @return Validation
     */
    public function make(array $data, array $rules, array $messages = [])
    {
        $validation = new Validation(
            $data, 
            $rules, 
            array_merge($this->messages, $messages)
        );
        
        return $validation->addRuleset(new Rules);

    }
}

