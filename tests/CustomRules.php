<?php

class CustomRules extends Maer\Validator\Rules\Ruleset
{
    public function ruleTestChuck($input)
    {
        if ($input == 'chuck') {
            return true;
        }
        return "%s failed";
    }
}