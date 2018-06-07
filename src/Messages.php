<?php namespace Maer\Validator;

class Messages
{
    /**
     * @var array
     */
    protected $messages;

    /**
     * @param array $messages
     */
    public function __construct(array $messages = [])
    {
        $this->messages = $messages;
    }

    /**
     * Add one or more messages
     *
     * @param string|array $rule
     * @param string       $message
     */
    public function addMessage($rule, $message = null)
    {
        if (!is_array($rule)) {
            $rule = [$rule => $message];
        }

        $this->messages = array_replace_recursive(
            $this->messages,
            $rule
        );
    }

    /**
     * Get a generated rule message
     *
     * @param  string $rule
     * @param  string $field
     * @param  array  $args
     * @return string
     */
    public function getRuleMessage($rule, $field, array $args = [])
    {
        $message = $this->messages[$rule] ?? 'The field %s failed';

        return $this->generate($message, $field, $args);
    }

    /**
     * Generate a message
     *
     * @param  string $message
     * @param  array  $args
     * @return string
     */
    public function generate($message, $field, array $args = [])
    {
        array_unshift($args, $message, $field);
        return call_user_func_array('sprintf', $args);
    }
}
