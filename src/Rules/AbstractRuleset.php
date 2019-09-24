<?php namespace Maer\Validator\Rules;

use Maer\Validator\Collections\ValueCollection;
use Maer\Validator\SingleTestResponse;

class AbstractRuleset
{
    /**
     * Create a single test response
     *
     * @param  bool   $success
     * @param  string $error
     *
     * @return SingleTestResponse
     */
    protected function response(bool $success = true, string $error = ''): SingleTestResponse
    {
        return new SingleTestResponse($success, $error);
    }
}
