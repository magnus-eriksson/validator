<?php

class CustomRules extends Maer\Validator\Rules\RuleSet
{
    public function ruleFoo($input)
    {
        return $input === 'foo'
            ? true
            : "%s must be foo";
    }

    public function ruleFooBar($input)
    {
        return $input === 'foobar';
    }
}