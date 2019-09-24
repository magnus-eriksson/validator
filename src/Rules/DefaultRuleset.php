<?php namespace Maer\Validator\Rules;

use Maer\Validator\Collections\ValueCollection;
use Maer\Validator\SingleTestResponse;

class DefaultRuleset extends AbstractRuleset
{
    /**
     * Check if a value has a minimum length
     *
     * @param  ValueCollection $values
     * @param  string          $fieldName
     * @param  int             $minLength
     *
     * @return SingleTestResponse
     */
    public function minLength(ValueCollection $values, string $fieldName, int $minLength): SingleTestResponse
    {
        if (strlen($values->get($fieldName)) >= $minLength) {
            return $this->response();
        }

        return $this->response()->setError('The field %s must have at least %d characters');
    }


    /**
     * Check if a value has a maximum length
     *
     * @param  ValueCollection $values
     * @param  string          $fieldName
     * @param  int             $maxLength
     *
     * @return SingleTestResponse
     */
    public function maxLength(ValueCollection $values, string $fieldName, int $maxLength): SingleTestResponse
    {
        if (strlen($values->get($fieldName)) <= $maxLength) {
            return $this->response();
        }

        return $this->response()->setError('The field %s must have maximum of %d characters');
    }


    /**
     * Check if two fields has the same value
     *
     * @param  ValueCollection $values
     * @param  string          $fieldName
     * @param  string          $fieldName2
     *
     * @return SingleTestResponse
     */
    public function same(ValueCollection $values, string $fieldName, string $fieldName2): SingleTestResponse
    {
        if ($values->has($fieldName) && $values->get($fieldName) === $values->get($fieldName2)) {
            return $this->response();
        }

        return $this->response()->setError('The field %s must be the same as the field %s');
    }


    /**
     * Check if two fields have different values
     *
     * @param  ValueCollection $values
     * @param  string          $fieldName
     * @param  string          $fieldName2
     *
     * @return SingleTestResponse
     */
    public function different(ValueCollection $values, string $fieldName, string $fieldName2): SingleTestResponse
    {
        if ($values->get($fieldName) !== $values->get($fieldName2)) {
            return $this->response();
        }

        return $this->response()->setError('The field %s must be different from the field %s');
    }


    /**
     * Check if an array has a value
     *
     * @param  ValueCollection $values
     * @param  string          $fieldName
     * @param  string          $value
     *
     * @return SingleTestResponse
     */
    public function arrayHas(ValueCollection $values, string $fieldName, string $searchString): SingleTestResponse
    {
        $array = $values->get($fieldName);

        if (!is_array($array)) {
            return $this->response()->setError('Expected field %s to be an array but got ' . gettype($array));
        }

        return in_array($searchString, $array)
            ? $this->response()
            : $this->response()->setError("Unable to find the value '{$searchString}' in the field %s");
    }


    /**
     * Validate int min value
     *
     * @param  ValueCollection $values
     * @param  string          $fieldName
     * @param  int             $minSize
     *
     * @return SingleTestResponse
     */
    public function minSize(ValueCollection $values, string $fieldName, int $minSize): SingleTestResponse
    {
        if ($values->get($fieldName) >= $minSize) {
            return $this->response();
        }

        return $this->response()->setError('The value of field %s must be greater or equal to %d');
    }


    /**
     * Validate int max value
     *
     * @param  ValueCollection $values
     * @param  string          $fieldName
     * @param  int             $maxSize
     *
     * @return SingleTestResponse
     */
    public function maxSize(ValueCollection $values, string $fieldName, int $maxSize): SingleTestResponse
    {
        if ($values->get($fieldName) <= $maxSize) {
            return $this->response();
        }

        return $this->response()->setError('The value of field %s must be less or equal to %d');
    }


    /**
     * Validate string against regex
     *
     * @param  ValueCollection $values
     * @param  string          $fieldName
     * @param  string          $regex
     *
     * @return SingleTestResponse
     */
    public function regex(ValueCollection $values, string $fieldName, string $regex): SingleTestResponse
    {
        if (preg_match($regex, $values->get($fieldName))) {
            return $this->response();
        }

        return $this->response()->setError("The field %s did not match the pattern");
    }


    /**
     * Check if string only contains letters and numbers
     *
     * @param  ValueCollection $values
     * @param  string          $fieldName
     *
     * @return SingleTestResponse
     */
    public function alphaNumeric(ValueCollection $values, string $fieldName): SingleTestResponse
    {
        if (preg_match('/^([0-9a-z]+)$/i', $values->get($fieldName))) {
            return $this->response();
        }

        return $this->response()->setError("The field %s can only contain numbers and letters");
    }


    /**
     * Check if a value is a valid email address
     *
     * @param  ValueCollection $values
     * @param  string          $fieldName
     *
     * @return SingleTestResponse
     */
    public function email(ValueCollection $values, string $fieldName): SingleTestResponse
    {
        if (filter_var($values->get($fieldName), FILTER_VALIDATE_EMAIL) !== false) {
            return $this->response();
        }

        return $this->response()->setError("The field %s must contain a valid email address");
    }


    /**
     * Check if input is a valid ip-address
     *
     * @param  ValueCollection $values
     * @param  string          $fieldName
     *
     * @return SingleTestResponse
     */
    public function ip(ValueCollection $values, string $fieldName): SingleTestResponse
    {
        if (filter_var($values->get($fieldName), FILTER_VALIDATE_IP) !== false) {
            return $this->response();
        }

        return $this->response()->setError("The field %s must contain a valid ip address");
    }


    /**
     * Check if input is a valid URL
     *
     * @param  ValueCollection $values
     * @param  string          $fieldName
     *
     * @return SingleTestResponse
     */
    public function url(ValueCollection $values, string $fieldName): SingleTestResponse
    {
        if (filter_var($values->get($fieldName), FILTER_VALIDATE_URL) !== false) {
            return $this->response();
        }

        return $this->response()->setError("The field %s must contain a valid url");
    }


    /**
     * Check if input is equal to a custom value
     *
     * @param  ValueCollection $values
     * @param  string          $fieldName
     * @param  string   $match
     *
     * @return SingleTestResponse
     */
    public function equal(ValueCollection $values, string $fieldName, string $match): SingleTestResponse
    {
        if ($values->get($fieldName) === $match) {
            return $this->response();
        }

        return $this->response()->setError("The field %s does not match {$match}");
    }


    /**
     * Check if input is not equal to a custom value
     *
     * @param  ValueCollection $values
     * @param  string          $fieldName
     * @param  string   $match
     *
     * @return SingleTestResponse
     */
    public function notEqual(ValueCollection $values, string $fieldName, $match): SingleTestResponse
    {
        if ($values->get($fieldName) !== $match) {
            return $this->response();
        }

        return $this->response()->setError("The field %s can not match {$match}");
    }


    /**
     * Check if input exists in the list
     *
     * @param  ValueCollection $values
     * @param  string          $fieldName
     * @param  array    $list
     *
     * @return SingleTestResponse
     */
    public function inList(ValueCollection $values, string $fieldName, $list = []): SingleTestResponse
    {
        $params = is_array($list)
            ? $list
            : array_slice(func_get_args(), 2);

        if (in_array($values->get($fieldName), $params)) {
            return $this->response();
        }

        return $this->response()->setError("The value of field %s does not exist in the list");
    }


    /**
     * Check if input don't exists in the list
     *
     * @param  ValueCollection $values
     * @param  string          $fieldName
     * @param  array    $list
     *
     * @return SingleTestResponse
     */
    public function notInList(ValueCollection $values, string $fieldName, $list = []): SingleTestResponse
    {
        $params = is_array($list)
            ? $list
            : array_slice(func_get_args(), 2);


        if (!in_array($values->get($fieldName), $params)) {
            return $this->response();
        }

        return $this->response()->setError("The value of field %s can not exist in the list");
    }
}
