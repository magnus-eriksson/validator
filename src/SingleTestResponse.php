<?php namespace Maer\Validator;

class SingleTestResponse
{
    /**
     * @var bool
     */
    protected $success = true;

    /**
     * @var string|null
     */
    protected $error   = null;


    /**
     * @param bool        $success
     * @param string|null $error
     */
    public function __construct(bool $success = true, string $error = null)
    {
        $this->success = $error ? false : $success;
        $this->error   = $error;
    }


    /**
     * Check if the test was successful
     *
     * @return bool
     */
    public function success(): bool
    {
        return $this->success;
    }


    /**
     * Get the error message, if any
     *
     * @return string|null
     */
    public function error()
    {
        return $this->error;
    }


    /**
     * Set the success state
     *
     * @param bool $success
     *
     * @return $this
     */
    public function setSuccess(bool $success): SingleTestResponse
    {
        $this->success = $success;

        return $this;
    }


    /**
     * Set the error message
     *
     * @param string|null
     *
     * @return $this
     */
    public function setError(?string $error): SingleTestResponse
    {
        $this->error = $error;

        if ($error) {
            $this->success = false;
        }

        return $this;
    }
}
