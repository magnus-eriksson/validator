<?php
namespace Maer\Validator\Sets;

use InvalidArgumentException;

class DefaultSet extends AbstractSet
{
    /**
     * Check if string is of minimum length
     *
     * @param string|int $input
     * @param string|int $min
     *
     * @return string|bool
     */
    public function minLength(string|int $input, string|int $min): string|bool
    {
        return strlen((string)$input) >= intval($min)
            ?: "Length must be at least $min";
    }


    /**
     * Check if string is not longer than maximum
     *
     * @param string|int $input
     * @param string|int $max
     *
     * @return string|bool
     */
    public function maxLength(string|int $input, string|int $max): string|bool
    {
        return strlen((string)$input) <= intval($max)
            ?: "Length must not exceed $max";
    }


    /**
     * Check that the value is of minimal size
     *
     * @param string|int $input
     * @param string|int $min
     *
     * @return string|bool
     */
    public function minSize(string|int $input, string|int $min): string|bool
    {
        return intval($input) >= intval($min)
            ?: "Size must be at least $min";
    }


    /**
     * Check that the value does not exceed max size
     *
     * @param string|int $input
     * @param string|int $max
     *
     * @return string|bool
     */
    public function maxSize(string|int $input, string|int $max): string|bool
    {
        return intval($input) <= intval($max)
            ?: "Size must not exceed $max";
    }


    /**
     * Check if the value is in the list
     *
     * @param string|int $input
     * @param mixed ...$list
     *
     * @return string|bool
     */
    public function inList(string|int $input, ...$list): string|bool
    {
        return in_array($input, $list)
            ?: "The value is invalid";
    }


    /**
     * Check if the value is not in the list
     *
     * @param string|int $input
     * @param mixed ...$list
     *
     * @return string|bool
     */
    public function notInList(string|int $input, ...$list): string|bool
    {
        return in_array($input, $list) === false
            ?: "The value is invalid";
    }


    /**
     * Validate an email address
     *
     * @param string $input
     *
     * @return string|bool
     */
    public function isEmail(string $input): string|bool
    {
        return filter_var($input, FILTER_VALIDATE_EMAIL)
            ? true
            : "Must contain a valid email address";
    }


    /**
     * Validate a URL
     *
     * @param string $input
     *
     * @return string|bool
     */
    public function isUrl(string $input): string|bool
    {
        return filter_var($input, FILTER_VALIDATE_URL)
            ? true
            : "Must contain a valid URL";
    }


    /**
     * Validate an IP address
     *
     * @param string $input
     *
     * @return string|bool
     */
    public function isIp(string $input): string|bool
    {
        return filter_var($input, FILTER_VALIDATE_IP)
            ? true
            : "Must contain a valid IP address";
    }


    /**
     * Check if two fields have same value
     *
     * @param mixed $input
     * @param string|int $field2
     *
     * @return string|bool
     */
    public function sameAsField(mixed $input, string|int $field2): string|bool
    {
        return ($this->hasParam($field2) && $this->getParam($field2) === $input)
            ?: "The fields does not match";
    }


    /**
     * Check if two fields have different values
     *
     * @param mixed $input
     * @param string|int $field2
     *
     * @return string|bool
     */
    public function differentFromField(mixed $input, string|int $field2): string|bool
    {
        return ($this->hasParam($field2) === false  || $this->getParam($field2) !== $input)
            ?: "The fields must not match";
    }


    /**
     * Check a value against a regular expression
     *
     * @param string $input
     * @param string $pattern
     *
     * @return void
     */
    public function regexMatch(string $input, string $pattern)
    {
        $match = preg_match($pattern, $input);

        if ($match === false) {
            throw new InvalidArgumentException("Regex error");
        }

        return $match === 1
            ?: "Invalid value";
    }


    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'minLength' => [$this, 'minLength'],
            'maxLength' => [$this, 'maxLength'],
            'minSize'   => [$this, 'minSize'],
            'maxSize'   => [$this, 'maxSize'],
            'in'        => [$this, 'inList'],
            'notIn'     => [$this, 'notInList'],
            'email'     => [$this, 'isEmail'],
            'url'       => [$this, 'isUrl'],
            'ip'        => [$this, 'isIp'],
            'same'      => [$this, 'sameAsField'],
            'different' => [$this, 'differentFromField'],
            'regexMatch' => [$this, 'regexMatch'],
        ];
    }
}
