<?php

/**
 * @coversDefaultClass \Maer\Validator\Validator
 */
class ValidationTest extends PHPUnit_Framework_TestCase
{
 
    public $validator;

    public function __construct()
    {
        $this->validator = new Maer\Validator\Validator();
    }

    public function testRequired()
    {
        // Round 1
        $v = $this->validator->make([
                'field_1'   => 'passes',
                'field_2'   => 1234,
                'field_3'   => null,
                'field_4'   => '',
            ],
            [
                'field_1' => ['minLength:5'],
                'field_2' => ['integer'],
                'field_3' => ['email'],
                'field_4' => ['ip'],
                'field_5' => ['url'],
            ]
        );

        $result = $v->passes();
        $this->assertTrue($result, "required passes: " . implode(', ', $v->errors()->all()));

        // Round 2
        $v = $this->validator->make([
                'field_1'   => 'fail',
                'field_2'   => null,
                'field_3'   => 'fail',
                'field_4'   => '',
            ],
            [
                'field_1' => ['minLength:5'],
                'field_2' => ['required', 'minLength:5'],
                'field_3' => ['required', 'url'],
                'field_4' => ['required', 'integer'],
                'field_5' => ['required'],
            ]
        );

        $result = $v->passes();
        $this->assertFalse($result, "required fails: " . implode(', ', $v->errors()->all()));
    }


    public function testMinMaxLength()
    {
        $v = $this->validator->make([
                'field_1'   => 'working_field',
                'field_2'   => 'failing_field'
            ],
            [
                'field_1' => ['required', 'minLength:10'],
                'field_2' => ['required', 'maxLength:5'],
            ]
        );

        $result = $v->passes();
        $this->assertFalse($result, "min- and maxLength-test");
    }
 

    public function testErrorMessage()
    {
        $v = $this->validator->make([
                'field_1'   => 'failing_field',
                'field_2'   => 'failing_field'
            ],
            [
                'field_1' => ['required', 'integer'],
                'field_2' => ['required', 'integer'],
            ],
            [
                'integer' => 'integer_test'
            ]
        );

        $result = $v->passes();
        $this->assertFalse($result, "Rule should fail");
        $this->assertEquals('integer_test', $v->errors->get('field_1'), 'Test custom rule message');
        $this->assertEquals('integer_test', $v->errors->get('field_2'), 'Test custom rule message');
    }

}