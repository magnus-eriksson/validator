<?php namespace Valid\Bags;

abstract class Bag
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * If set to true, the bag can't be modified after instantiation
     *
     * @var bool
     */
    protected $immutable = false;


    /**
     * @param iterable $items
     */
    public function __construct(iterable $items = [])
    {
        if (!is_array($items)) {
            $items = (array)$items;
        }

        $this->items = $items;
    }


    /**
     * Get all items
     *
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }


    /**
     * Get an item from the bag
     *
     * @param  string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->items[$key] ?? null;
    }


    /**
     * Add an item to the collection
     *
     * @param  string $key
     * @param  mixed  $value
     *
     * @return $this
     */
    public function add(string $key, $value): Bag
    {
        if ($this->isMutable()) {
            $this->items[$key] = $value;
        }

        return $this;
    }

    /**
     * Remove an item from the bag
     *
     * @param  string $key
     *
     * @return Bag
     */
    public function remove(string $key): Bag
    {
        if ($this->isMutable() && $this->has($key)) {
            unset($this->items[$key]);
        }

        return $this;
    }


    /**
     * Check if an item exists
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->items);
    }


    /**
     * Get the number of items in the bag
     *
     * @return ing
     */
    public function count(): int
    {
        return count($this->items);
    }


    /**
     * Check if the bag is empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return !empty($this->items);
    }


    /**
     * Check if this bag is immutable
     *
     * @return bool
     */
    public function isImmutable(): bool
    {
        return $this->immutable;
    }


    /**
     * Check if this bag is mutable
     *
     * @return bool
     */
    public function isMutable(): bool
    {
        return !$this->isImmutable();
    }
}
