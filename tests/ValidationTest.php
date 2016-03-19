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