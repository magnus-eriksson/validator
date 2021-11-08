<?php
namespace Maer\Validator;

use Maer\Validator\Sets\AbstractSet;

class Factory
{
    /**
     * @var array
     */
    protected array $sets = [];


    /**
     * Create a new pre-configured Validator instance
     *
     * @param array $params
     * @param array $rules
     * @param array $fieldErrors
     *
     * @return Validator
     */
    public function create(array $params, array $rules, array $fieldErrors = []): Validator
    {
        return (new Validator($params, $rules, $fieldErrors))
            ->addSet($this->sets);
    }


    /**
     * Register a global set
     *
     * @param AbstractSet|array $set
     *
     * @return Factory
     */
    public function registerSet(AbstractSet|array $set): Factory
    {
        if (is_array($set)) {
            $this->sets = array_merge($this->sets, $set);
        } else {
            $this->sets[] = $set;
        }

        return $this;
    }
}
