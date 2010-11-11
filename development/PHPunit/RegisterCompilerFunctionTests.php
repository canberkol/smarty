<?php
/**
 * Smarty PHPunit tests register->compilerFunction / unregister->compilerFunction methods
 * 
 * @package PHPunit
 * @author Uwe Tews 
 */

/**
 * class for register->compilerFunction / unregister->compilerFunction methods tests
 */
class RegisterCompilerFunctionTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
    } 

    public static function isRunnable()
    {
        return true;
    } 

    /**
     * test register->compilerFunction method for function
     */
    public function testRegisterCompilerFunction()
    {
        $this->smarty->registerPlugin(Smarty::PLUGIN_COMPILER,'testcompilerfunction', 'mycompilerfunction');
        $this->assertEquals('mycompilerfunction', $this->smarty->registered_plugins['compiler']['testcompilerfunction'][0]);
        $this->assertEquals('hello world 1', $this->smarty->fetch('eval:{testcompilerfunction var=1}'));
    } 
    /**
     * test register->compilerFunction method for static class
     */
    public function testRegisterCompilerFunctionClass()
    {
        $this->smarty->registerPlugin(Smarty::PLUGIN_COMPILER,'testcompilerfunction', array('mycompilerfunctionclass', 'execute'));
        $this->assertEquals('hello world 2', $this->smarty->fetch('eval:{testcompilerfunction var1=2}'));
    } 
    /**
     * test register->compilerFunction method for objects
     */
    public function testRegisterCompilerFunctionObject()
    {
        $obj = new mycompilerfunctionclass;
        $this->smarty->registerPlugin(Smarty::PLUGIN_COMPILER,'testcompilerfunction', array($obj, 'compile'));
        $this->assertEquals('hello world 3', $this->smarty->fetch('eval:{testcompilerfunction var2=3}'));
    } 
    /**
     * test unregister->compilerFunction method
     */
    public function testUnregisterCompilerFunction()
    {
        $this->smarty->registerPlugin(Smarty::PLUGIN_COMPILER,'testcompilerfunction', 'mycompilerfunction');
        $this->smarty->unregisterPlugin(Smarty::PLUGIN_COMPILER,'testcompilerfunction');
        $this->assertFalse(isset($this->smarty->registered_plugins[Smarty::PLUGIN_COMPILER]['testcompilerfunction']));
    } 
    /**
     * test unregister->compilerFunction method not registered
     */
    public function testUnregisterCompilerFunctionNotRegistered()
    {
        $this->smarty->unregisterPlugin(Smarty::PLUGIN_COMPILER,'testcompilerfunction');
        $this->assertFalse(isset($this->smarty->registered_plugins[Smarty::PLUGIN_COMPILER]['testcompilerfunction']));
    } 
    /**
     * test unregister->compilerFunction method other registered
     */
    public function testUnregisterCompilerFunctionOtherRegistered()
    {
        $this->smarty->registerPlugin(Smarty::PLUGIN_BLOCK,'testcompilerfunction', 'mycompilerfunction');
        $this->smarty->unregisterPlugin(Smarty::PLUGIN_COMPILER,'testcompilerfunction');
        $this->assertTrue(isset($this->smarty->registered_plugins[Smarty::PLUGIN_BLOCK]['testcompilerfunction']));
    } 
} 
function mycompilerfunction($params, &$smarty)
{
    return "<?php echo 'hello world {$params['var']}'?>";
} 
class mycompilerfunctionclass {
    static function execute($params, &$smarty)
    {
        return "<?php echo 'hello world {$params['var1']}'?>";
    } 
    function compile($params, &$smarty)
    {
        return "<?php echo 'hello world {$params['var2']}'?>";
    } 
} 

?>