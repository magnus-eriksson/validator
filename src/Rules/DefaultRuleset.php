<?php namespace Valid\Rules;

use Valid\Bags\ValuesBag;

class DefaultRuleset extends AbstractRuleset
{
    public function test(ValuesBag $values, $field, ...$args)
    {
        dd($field, $values, $args);
    }
}
