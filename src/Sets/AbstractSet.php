<?php
namespace Maer\Validator\Sets;

use Maer\Validator\Params\Params;

abstract class AbstractSet
{
    /**
     * @var Params
     */
    protected Params $params;


    /**
     * Undocumented function
     *
     * @param Params $params
     *
     * @return AbstractSet
     */
    final public function setParams(Params $params): AbstractSet
    {
        $this->params = $params;

        return $this;
    }


    /**
     * Check if a param exists
     *
     * @param string|int $param
     *
     * @return bool
     */
    protected function hasParam(string|int $param): bool
    {
        return $this->params->has($param);
    }


    /**
     * Get a param
     *
     * @param string|int $param
     * @param mixed $default
     *
     * @return mixed
     */
    protected function getParam(string|int $param, mixed $default = null): mixed
    {
        return $this->params->get($param, $default);
    }


    /**
     * Return the rule names and callbacks
     *
     * @return array
     */
    abstract public function rules(): array;
}
