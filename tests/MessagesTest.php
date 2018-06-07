<?php
use Maer\Validator\Validator;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Maer\Validator\Validator
 */
class MessagesTest extends TestCase
{
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator;
    }

    /**
     * Test the default route messages
     */
    public function testDefaultMessage()
    {
        $v = $this->validator->make(['foo' => 'bar']);
        $v->param('foo')->equal('foo');

        $errors = $v->errors()->all();

        $this->assertEquals('The field foo must match foo', $errors['foo'] ?? 'none');
    }

    /**
     * Test overriding specific rule message
     */
    public function testOverrideRuleMessage()
    {
        $this->validator->addMessages([
            'equal' => '%s is not equal to %s'
        ]);

        $v = $this->validator->make(['foo' => 'bar']);
        $v->param('foo')->equal('cookie');

        $errors = $v->errors()->all();

        $this->assertEquals('foo is not equal to cookie', $errors['foo'] ?? 'none');
    }

    /**
     * Test custom field message
     */
    public function testCustomFieldMessage()
    {
        // Test with one
        $v = $this->validator->make(['foo' => 'bar']);
        $v->fieldMessages([
            'foo' => 'No foo for you',
        ]);

        $v->param('foo')->equal('cookie');
        $errors = $v->errors()->all();

        $this->assertEquals('No foo for you', $errors['foo'] ?? 'none');

        // Test to see that others don't get the error message
        $v = $this->validator->make([
            'foo' => 'bar',
            'bar' => 'foo'
        ]);
        $v->fieldMessages([
            'foo' => 'No foo for you',
        ]);

        $v->param('foo')->equal('bar');
        $v->param('bar')->minLength(10);
        $errors = $v->errors()->all();

        $this->assertEquals('The field bar must be at least 10 characters', $errors['bar'] ?? 'none');
    }
}
