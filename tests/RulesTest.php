<?php

/**
 * @coversDefaultClass \Maer\Validator\Validator
 */
class RulesTest extends PHPUnit_Framework_TestCase
{
 
    public $v;

    public function __construct()
    {
        $this->v = new Maer\Validator\Rules\Rules;
    }

    public function testRequired()
    {
        $this->assertTrue(
            $this->v->ruleRequired('Hello')
        );

        $this->assertFalse(
            $this->v->ruleRequired('')
        );
    }
 
    public function testMinLength()
    {
        $this->assertTrue(
            $this->v->ruleMinLength('Hello', 5)
        );

        $this->assertFalse(
            $this->v->ruleMinLength('Hello', 8)
        );
    }

    public function testMaxLength()
    {
        $this->assertTrue(
            $this->v->ruleMaxLength('Hello', 5)
        );

        $this->assertFalse(
            $this->v->ruleMaxLength('Hello', 2)
        );
    }

    public function testMinSize()
    {
        $this->assertTrue(
            $this->v->ruleMinSize(10, 4)
        );

        $this->assertFalse(
            $this->v->ruleMinSize(10, 11)
        );
    }

    public function testMaxSize()
    {
        $this->assertTrue(
            $this->v->ruleMaxSize(3, 4)
        );

        $this->assertFalse(
            $this->v->ruleMaxSize(3, 2)
        );
    }

    public function testRegex()
    {
        $this->assertTrue(
            $this->v->ruleRegex("bla", "/^([a-z]+)$/")
        );

        $this->assertFalse(
            $this->v->ruleRegex("bl 1", "/^([a-z0-9]+)$/")
        );    
    }

    public function testAlphaNumeric()
    {
        $this->assertTrue(
            $this->v->ruleAlphaNumeric("bla1")
        );

        $this->assertFalse(
            $this->v->ruleAlphaNumeric("bl=1")
        );    
    }

    public function testInteger()
    {
        $this->assertTrue(
            $this->v->ruleInteger(4)
        );

        $this->assertFalse(
            $this->v->ruleInteger("a")
        );    
    }

    public function testFloat()
    {
        $this->assertTrue(
            $this->v->ruleFloat(4.2)
        );

        $this->assertFalse(
            $this->v->ruleFloat(5)
        );    
    }

    public function testBoolean()
    {
        $this->assertTrue(
            $this->v->ruleBoolean(true)
        );

        $this->assertFalse(
            $this->v->ruleBoolean("true")
        );    
    }

    public function testEmail()
    {
        $this->assertTrue(
            $this->v->ruleEmail("foo@bar.org")
        );

        $this->assertFalse(
            $this->v->ruleEmail("foo@bar")
        );    
    }

    public function testIp()
    {
        $this->assertTrue(
            $this->v->ruleIp("1.1.1.1")
        );

        $this->assertFalse(
            $this->v->ruleIp("1.1.1")
        );    
    }

    public function testUrl()
    {
        $this->assertTrue(
            $this->v->ruleUrl("http://foo.org")
        );

        $this->assertFalse(
            $this->v->ruleUrl("foo.bar")
        );    
    }

    public function testEqual()
    {
        $this->assertTrue(
            $this->v->ruleEqual("hello", "hello")
        );

        $this->assertFalse(
            $this->v->ruleEqual("hello", "hell")
        );    
    }

    public function testNotEqual()
    {
        $this->assertTrue(
            $this->v->ruleNotEqual("hello", "hell")
        );

        $this->assertFalse(
            $this->v->ruleNotEqual("hello", "hello")
        );    
    }

    public function testIn()
    {
        $this->assertTrue(
            $this->v->ruleIn("test2", ["test1", "test2", "test3"])
        );

        $this->assertFalse(
            $this->v->ruleIn("test4", ["test1", "test2", "test3"])
        );    
    }

    public function testNotIn()
    {
        $this->assertTrue(
            $this->v->ruleNotIn("test4", ["test1", "test2", "test3"])
        );

        $this->assertFalse(
            $this->v->ruleNotIn("test2", ["test1", "test2", "test3"])
        );    
    }

}