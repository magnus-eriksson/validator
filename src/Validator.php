<?php namespace Maer\Validator;

class Validator
{
    protected $sets      = [];
    protected $messages  = [];


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
        $validation = new Tester(
            $data, 
            $rules, 
            array_merge($this->messages, $messages)
        );
        
        return $validation->addRuleset(new Rules\Rules);

    }
}

