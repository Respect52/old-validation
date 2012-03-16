<?php

//namespace Respect\Validation\Rules;


class Respect52_Validation_Rules_AllOfTest extends \PHPUnit_Framework_TestCase
{

    public function test_removeRules_should_remove_all_rules()
    {
        $o = new Respect52_Validation_Rules_AllOf(new Respect52_Validation_Rules_Int, new Respect52_Validation_Rules_Positive);
        $o->removeRules();
        $this->assertEquals(0, count($o->getRules()));
    }

    public function test_addRules_using_array_of_rules()
    {
        $o = new Respect52_Validation_Rules_AllOf();
        $o->addRules(
            array(
                array($x = new Respect52_Validation_Rules_Int, new Respect52_Validation_Rules_Positive)
            )
        );
        $this->assertTrue($o->hasRule($x));
        $this->assertTrue($o->hasRule('Positive'));
    }

    public function test_addRules_using_specification_array()
    {
        $o = new Respect52_Validation_Rules_AllOf();
        $o->addRules(array("Between" => array(1, 2)));
        $this->assertTrue($o->hasRule('Between'));
    }

    public function test_validation_should_work_if_all_rules_return_true()
    {
        $valid1 = new Respect52_Validation_Rules_Callback(function() {
                    return true;
                });
        $valid2 = new Respect52_Validation_Rules_Callback(function() {
                    return true;
                });
        $valid3 = new Respect52_Validation_Rules_Callback(function() {
                    return true;
                });
        $o = new Respect52_Validation_Rules_AllOf($valid1, $valid2, $valid3);
        $this->assertTrue($o->validate('any'));
        $this->assertTrue($o->check('any'));
        $this->assertTrue($o->assert('any'));
    }
    
    public function test_sanitizing_should_work_for_composite_rules()
    {
        $valid = new Respect52_Validation_Rules_Digits();
        $filter = new Respect52_Validation_Rules_Int();
        $o = new Respect52_Validation_Rules_AllOf($valid, $filter);
        $this->assertTrue($o->validate(12));
        $this->assertTrue($o->check(12));
        $this->assertTrue($o->assert(12));
        $this->assertSame(12, $o->sanitize('12 monkeys'));
    }

    public function test_filter_should_work_for_composite_rules()
    {
        $valid = new Respect52_Validation_Rules_Digits();
        $filter = new Respect52_Validation_Rules_Int();
        $o = new Respect52_Validation_Rules_AllOf($valid, $filter);
        $this->assertTrue($o->validate(12));
        $this->assertTrue($o->check(12));
        $this->assertTrue($o->assert(12));
        $this->assertSame(null, $o->filter('12 monkeys'));
    }

    /**
     * @dataProvider providerStaticDummyRules
     * @expectedException Respect\Validation\Exceptions\AllOfException
     */
    public function test_validation_assert_should_fail_if_any_rule_fails_and_return_all_exceptions_failed($v1, $v2, $v3)
    {
        $o = new Respect52_Validation_Rules_AllOf($v1, $v2, $v3);
        $this->assertFalse($o->validate('any'));
        $this->assertFalse($o->assert('any'));
    }
    
    /**
     * @dataProvider providerStaticDummyRules
     * @expectedException Respect\Validation\Exceptions\CallbackException
     */
    public function test_validation_check_should_fail_if_any_rule_fails_and_throw_the_first_exception_only($v1, $v2, $v3)
    {
        $o = new Respect52_Validation_Rules_AllOf($v1, $v2, $v3);
        $this->assertFalse($o->validate('any'));
        $this->assertFalse($o->check('any'));
    }
    
    /**
     * @dataProvider providerStaticDummyRules
     */
    public function test_validation_should_fail_if_any_rule_fails($v1, $v2, $v3)
    {
        $o = new Respect52_Validation_Rules_AllOf($v1, $v2, $v3);
        $this->assertFalse($o->validate('any'));
    }
    
    public function providerStaticDummyRules()
    {
        $theInvalidOne = new Respect52_Validation_Rules_Callback(function() {
                    return false;
                });
        $valid1 = new Respect52_Validation_Rules_Callback(function() {
                    return true;
                });
        $valid2 = new Respect52_Validation_Rules_Callback(function() {
                    return true;
                });
        return array(
            array($theInvalidOne, $valid1, $valid2),
            array($valid2, $valid1, $theInvalidOne),
            array($valid2, $theInvalidOne, $valid1),
            array($valid1, $valid2, $theInvalidOne),
            array($valid1, $theInvalidOne, $valid2)
        );
    }

}