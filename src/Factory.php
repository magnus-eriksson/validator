<?php
namespace Maer\Validator;

use Closure;
use Maer\Validator\Sets\AbstractSet;
use Maer\Validator\Sets\ClosureSet;

class Factory
{
    /**
     * @var array
     */
    protected array $sets = [];

    /**
     * @var ClosureSet
     */
    protected ClosureSet $closures;


    public function __construct()
    {
        $this->closures = new ClosureSet;
        $this->sets[] =& $this->closures;
    }


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


    /**
     * Add a global closure rule
     *
     * @param string $name
     * @param Closure $closure
     *
     * @return Factory
     */
    public function registerClosure(string $name, Closure $closure): Factory
    {
        $this->closures->addClosure($name, $closure);

        return $this;
    }
}
