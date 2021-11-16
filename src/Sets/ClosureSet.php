<?php
namespace Maer\Validator\Sets;

use Closure;

class ClosureSet extends AbstractSet
{
    /**
     * @var array
     */
    protected array $rules = [];


    /**
     * Add a new closure rule
     *
     * @param string $name
     * @param Closure $closure
     *
     * @return void
     */
    public function addClosure(string $name, Closure $closure): void
    {
        $this->rules[$name] = Closure::bind($closure, $this, $this);
    }


    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return $this->rules;
    }
}
