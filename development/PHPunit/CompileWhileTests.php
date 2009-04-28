<?php
/**
* Smarty PHPunit tests compilation of {while} tag
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once SMARTY_DIR . 'Smarty.class.php';

/**
* class for {while} tag tests
*/
class CompileWhileTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->error_reporting = E_ALL;
        $this->smarty->enableSecurity();
        $this->smarty->force_compile = true;
        $this->old_error_level = error_reporting();
    } 

    public function tearDown()
    {
        error_reporting($this->old_error_level);
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test {while 'condition'} tag
    */
    public function testWhileCondition()
    {
        $tpl = $this->smarty->createTemplate('string:{$X=0}{while $x<10}{$x}{$x=$x+1}{/while}');
        $this->assertEquals("0123456789", $this->smarty->fetch($tpl));
    } 

    /**
    * test {while 'statement'} tag
    */
    public function testWhileStatement()
    {
        $tpl = $this->smarty->createTemplate('string:{$y=5}{while $y=$y-1}{$y}{/while}');
        $this->assertEquals("4321", $this->smarty->fetch($tpl));
    } 
} 

?>
