<?php
use Maer\Validator\Validator;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/CustomRules.php';

/**
 * @coversDefaultClass \Maer\Validator\Validator
 */
class CustomRulesTest extends TestCase
{
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator;
        $this->validator->addRuleSet(new CustomRules);
    }

    /**
     * Test custom rule
     */
    public function testCustomRule()
    {
        $v = $this->validator->make(['foo' => 'foo']);
        $v->param('foo')->foo();

        $this->assertTrue($v->passes());
    }

    /**
     * Test custom rule message
     */
    public function testCustomRuleMessage()
    {
        $v = $this->validator->make(['foo' => 'bar']);
        $v->param('foo')->foo();

        $this->assertFalse($v->passes());

        $errors = $v->errors()->all();
        $this->assertEquals('foo must be foo', $errors['foo'] ?? '');
    }

    /**
     * Test generic message
     */
    public function testGenericMessage()
    {
        $v = $this->validator->make(['foo' => 'bar']);
        $v->param('foo')->fooBar();

        $this->assertFalse($v->passes());

        $errors = $v->errors()->all();
        $this->assertEquals('The field foo failed', $errors['foo'] ?? '');
    }


    /**
     * Test one value against a custom rule
     */
    public function testValidationTest()
    {
        $response = $this->validator->test('foo', 'foo');
        $this->assertTrue($response);

        $response = $this->validator->test('bar', 'foo');
        $this->assertFalse($response);
    }
}
