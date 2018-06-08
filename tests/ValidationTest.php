<?php
use Maer\Validator\Validator;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Maer\Validator\Validator
 */
class ValidationTest extends TestCase
{
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator;
    }

    /**
     * Test successful validation
     */
    public function testValidationSuccess()
    {
        $v = $this->validator->make([
            'foo' => 'bar',
            'bar' => 'foo',
        ]);

        $v->param('foo')->equal('bar');
        $v->param('bar')->minLength(3);

        $this->assertTrue($v->passes());
    }

    /**
     * Test failed validation
     */
    public function testValidationFail()
    {
        $v = $this->validator->make([
            'foo' => 'bar',
            'bar' => 'foo',
        ]);

        $v->param('foo')->equal('foo');
        $v->param('bar')->minLength(10);

        $this->assertFalse($v->passes());
        $this->assertCount(2, $v->errors()->all());
    }

    /**
     * Test one value against one rule
     */
    public function testValidationTest()
    {
        $response = $this->validator->test('foo', 'minLength', 2);
        $this->assertTrue($response);

        $response = $this->validator->test('foo', 'minLength', 4);
        $this->assertFalse($response);
    }

    /**
     * Test passing in an array
     */
    public function testPassArrayArgument()
    {
        // Success
        $v = $this->validator->make([
            'foo' => 'bar',
        ]);

        $v->param('foo')->in(['test', 'bar']);

        $this->assertTrue($v->passes());

        // Fail
        $v = $this->validator->make([
            'foo' => 'bar',
        ]);

        $v->param('foo')->in(['test', 'example']);

        $this->assertFalse($v->passes());
    }

    /**
     * Test alternative syntax
     */
    public function testAlternativeSyntax()
    {
        $rules = [
            'foo' => ['required', 'minLength:4', 'in:barba,test'],
            'bar' => ['required', 'email']
        ];

        // Success
        $v = $this->validator->make(['foo' => 'barba', 'bar' => 'foo@bar.com'], $rules);

        $this->assertTrue($v->passes());

        // Fail
        $v = $this->validator->make(['foo' => 'barr', 'bar' => 'foo'], $rules);

        $this->assertFalse($v->passes());

        $errors = $v->errors()->all();

        $this->assertCount(2, $errors);

    }
}
