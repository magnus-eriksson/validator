<?php
namespace Maer\Validator\Params;

class Params
{
    /**
     * @var array
     */
    protected array $params = [];


    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->normalize($params);
    }


    /**
     * Normalize the param keys for fast retrieval
     *
     * @param array $params
     * @param string|null $parent
     *
     * @return void
     */
    protected function normalize(&$params, ?string $parent = null)
    {
        foreach ($params as $key => &$value) {
            $field = $parent ? $parent . '.' . $key : $key;
            $this->params[$field] = &$value;

            if (is_array($value)) {
                $this->normalize($value, $field);
            }
        }
    }


    /**
     * Check if a field exists
     *
     * @param string|int $field
     *
     * @return bool
     */
    public function has(string|int $field): bool
    {
        return key_exists($field, $this->params);
    }


    /**
     * Get a field value
     *
     * @param string|int $field
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string|int $field, mixed $default = null): mixed
    {
        return $this->has($field)
            ? $this->params[$field]
            : $default;
    }
}
