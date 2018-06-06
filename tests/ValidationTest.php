<?php
require_once __DIR__ . '/CustomRules.php';

/**
 * @coversDefaultClass \Maer\Validator\Validator
 */
class ValidationTest extends PHPUnit_Framework_TestCase
{

    public $validator;
    protected $data = [
        'fieldInt'  => 12345,
        'fieldStr'  => "hello",
        'fieldNull' => null,
        'fieldZero' => 0,
    ];

    public function __construct()
    {
        $this->validator = new Maer\Validator\Validator();
        $this->validator->addRuleset(new CustomRules);
    }

    public function testRequired()
    {
        // Test 1
        $result = $this->validator->make($this->data, [
            'fieldNull' => ['required'],
            'fieldInt'  => ['required', 'minSize:10000']
        ]);

        $this->assertTrue($result->passes(), 'required 1');

        // Test 2
        $result = $this->validator->make($this->data, [
            'fieldNonExisting' => ['required']
        ]);

        $this->assertFalse($result->passes(), "required 2");

        // Test 3
        $result = $this->validator->make($this->data, [
            'fieldStr' => ['required', 'minLength:50']
        ]);

        $this->assertFalse($result->passes(), "required 3");
    }

    public function testAllowEmpty()
    {
        // Test 1
        $result = $this->validator->make($this->data, [
            'fieldNull' => ['required', 'allowEmpty', 'minLength:20'],
            'fieldZero' => ['required', 'allowEmpty', 'minSize:10'],
        ]);

        $this->assertTrue($result->passes(), 'allow empty 1');

        // Test 2
        $result = $this->validator->make($this->data, [
            'fieldNull' => ['minLength:20'],
        ]);

        $this->assertFalse($result->passes(), 'allow empty 2');

        // Test 3
        $result = $this->validator->make($this->data, [
            'fieldZero' => ['minSize:20'],
        ]);

        $this->assertFalse($result->passes(), 'allow empty 3');
    }

    public function testErrorMessage()
    {
        $result = $this->validator->make($this->data,
            [
                'fieldStr' => ['required', 'integer'],
            ],
            [
                'integer' => 'integer_test'
            ]
        );

        $this->assertFalse($result->passes(), "Rule should fail");
        $this->assertEquals('integer_test', $result->errors->get('fieldStr'), 'Test custom rule message 1');

        $result = $this->validator->make($this->data,
            [
                'fieldNonExisting' => ['required', 'integer'],
            ],
            [
                'required' => 'required_test'
            ]
        );

        $this->assertFalse($result->passes(), "Rule should fail");
        $this->assertEquals('required_test', $result->errors->get('fieldNonExisting'), 'Test custom rule message 2');
    }

    public function testTestFail()
    {
        $response = $this->validator->test('integer', 'abc');
        $this->assertFalse($response, 'test(integer) should return false');
    }

    public function testTestSuccess()
    {
        $response = $this->validator->test('integer', 123);
        $this->assertTrue($response, 'test(integer) should return true');
    }

    public function testTestErrorMessage()
    {
        $response = $this->validator->test('integer', 'abc', true);
        $this->assertInternalType('string', $response, 'test(integer) should return error message');
    }

    public function testCustomRules()
    {
        $rules = [
            'test' => ['testChuck'],
        ];

        $rulesMulti = [
            'test' => ['testChuck', 'minLength: 20'],
        ];

        $v = $this->validator->make(['test' => 'chuck'], $rules);
        $this->assertEquals(true, $v->passes());

        $v = $this->validator->make(['test' => 'chuckie'], $rules);
        $this->assertEquals(false, $v->passes());
        $errors = $v->errors()->all();
        $error = isset($errors['test']) ? $errors['test'] : 'No error found';
        $this->assertEquals('test failed', $error);

        $v = $this->validator->make(['test' => 'chuck'], $rulesMulti, ['minLength' => 'invalid length']);
        $this->assertEquals(false, $v->passes());
        $errors = $v->errors()->all();
        $error = isset($errors['test']) ? $errors['test'] : 'No error found';
        $this->assertEquals('invalid length', $error);
    }
}
