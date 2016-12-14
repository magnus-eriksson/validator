<?php namespace Maer\Validator\Rules;

class Rules extends Ruleset
{
    /**
     * Validate string minimum length
     * @param  string   $input
     * @param  integer  $minLength
     * @return boolean
     */
    public function ruleMinLength($input, $minLength)
    {
        return strlen($input) >= $minLength;
    }


    /**
     * Validate string maximum length
     * @param  string   $input
     * @param  integer  $maxLength
     * @return boolean
     */
    public function ruleMaxLength($input, $maxLength)
    {
        return strlen($input) <= $maxLength;
    }


    /**
     * Validate integer min value
     * @param  integer  $input
     * @param  integer  $minSize
     * @return boolean
     */
    public function ruleMinSize($input, $minSize)
    {
        return $input >= $minSize;
    }


    /**
     * Validate integer max value
     * @param  integer  $input
     * @param  integer  $maxSize
     * @return boolean
     */
    public function ruleMaxSize($input, $maxSize)
    {
        return $input <= $maxSize;
    }


    /**
     * Validate string against regex
     * @param  string   $input
     * @param  string   $regex
     * @return boolean
     */
    public function ruleRegex($input, $regex)
    {
        return (bool) preg_match($regex, $input);
    }


    /**
     * Check if string only contains letters and numbers
     * @param  string   $input
     * @return boolean
     */
    public function ruleAlphaNumeric($input)
    {
        return (bool) preg_match('/^(\w+)$/', $input);
    }


    /**
     * Check if input is of type Integer
     * @param  integer $input
     * @return boolean
     */
    public function ruleInteger($input)
    {
        return is_int($input);
    }


    /**
     * Check if input is of type Float
     * @param  float $input
     * @return boolean
     */
    public function ruleFloat($input)
    {
        return is_float($input);
    }


    /**
     * Check if input is of type Boolean
     * @param  boolean $input
     * @return boolean
     */
    public function ruleBoolean($input)
    {
        return is_bool($input);
    }


    /**
     * Check if input is a valid email address
     * @param  string   $input
     * @return boolean
     */
    public function ruleEmail($input)
    {
        return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
    }


    /**
     * Check if input is a valid ip-address
     * @param  string   $input
     * @return boolean
     */
    public function ruleIp($input)
    {
        return filter_var($input, FILTER_VALIDATE_IP) !== false;
    }


    /**
     * Check if input is a valid URL
     * @param  string   $input
     * @return boolean
     */
    public function ruleUrl($input)
    {
        return filter_var($input, FILTER_VALIDATE_URL) !== false;
    }


    /**
     * Check if input is equal to a custom value
     * @param  string   $input
     * @param  string   $match
     * @return boolean
     */
    public function ruleEqual($input, $match)
    {
        return $input == $match;
    }


    /**
     * Check if input is not equal to a custom value
     * @param  string   $input
     * @param  string   $match
     * @return boolean
     */
    public function ruleNotEqual($input, $match)
    {
        return $input != $match;
    }


    /**
     * Check if input is equal to another field value
     * @param  string   $input
     * @param  string   $field
     * @return boolean
     */
    public function ruleSame($input, $field)
    {
        if (!$this->hasData($field)) {
            return false;
        }

        return $input == $this->getData($field);
    }


    /**
     * Check if input is not equal to another field value
     * @param  string   $input
     * @param  string   $field
     * @return boolean
     */
    public function ruleDifferent($input, $field)
    {
        return $input != $this->getData($field);
    }


    /**
     * Check if input exists in the list
     * @param  string   $input
     * @param  array    $list
     * @return boolean
     */
    public function ruleIn($input, $list = [])
    {
        $params = is_array($list)
            ? $list
            : array_slice(func_get_args(), 1);

        return in_array($input, $params);
    }


    /**
     * Check if input don't exists in the list
     * @param  string   $input
     * @param  array    $list
     * @return boolean
     */
    public function ruleNotIn($input, $list = [])
    {
        $params = is_array($list)
            ? $list
            : array_slice(func_get_args(), 1);

        return !in_array($input, $params);
    }

}