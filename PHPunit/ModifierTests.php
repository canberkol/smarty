<?php
/**
* Smarty PHPunit tests of modifier
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for modifier tests
*/
class ModifierTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
        $this->smarty->enableSecurity();
        $this->smarty->force_compile = true;
        $this->old_error_level = error_reporting();
        error_reporting(E_ALL);
    } 

    public function tearDown()
    {
        error_reporting($this->old_error_level);
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test PHP function as modifier
    */
    public function testPHPFunctionModifier()
    {
        $this->smarty->security_policy->modifiers = array('strlen');
        $tpl = $this->smarty->createTemplate('string:{"hello world"|strlen}');
        $this->assertEquals("11", $this->smarty->fetch($tpl));
    } 
    public function testPHPFunctionModifier2()
    {
        $this->smarty->security_policy->modifiers = array('strlen');
        $tpl = $this->smarty->createTemplate('string:{assign var=foo value="hello world"}{$foo|strlen}');
        $this->assertEquals("11", $this->smarty->fetch($tpl));
    } 
    /**
    * test plugin as modifier
    */
    public function testPluginModifier()
    {
        $tpl = $this->smarty->createTemplate('string:{"hello world"|truncate:6}');
        $this->assertEquals("hel...", $this->smarty->fetch($tpl));
    } 
    /**
    * test modifier chaining
    */
    public function testModifierChaining()
    {
        $this->smarty->security_policy->modifiers = array('strlen');
        $tpl = $this->smarty->createTemplate('string:{"hello world"|truncate:6|strlen}');
        $this->assertEquals("6", $this->smarty->fetch($tpl));
    } 
    /**
    * test modifier at plugin result
    */
    public function testModifierAtPluginResult()
    {
        $tpl = $this->smarty->createTemplate('string:{counter|truncate:5 start=100000}');
        $this->assertEquals("10...", $this->smarty->fetch($tpl));
    } 
    /**
    * test unqouted string as modifier parameter
    */
    public function testModifierUnqoutedString()
    {
        $tpl = $this->smarty->createTemplate('string:{"hello world"|replace:hello:xxxxx}');
        $this->assertEquals("xxxxx world", $this->smarty->fetch($tpl));
    } 
    /**
    * test unknown modifier error
    */
    public function testUnknownModifier()
    {
        try {
            $this->smarty->fetch('string:{"hello world"|unknown}');
        } 
        catch (Exception $e) {
            $this->assertContains('unknown modifier "unknown"', $e->getMessage());
            return;
        } 
        $this->fail('Exception for unknown modifier has not been raised.');
    } 
} 

?>
