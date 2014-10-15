<?php 
class Module
{
    protected $_getFunctionsList = array();
    protected $_postFunctionsList = array();
    protected $_putFunctionsList = array();
    protected $_deleteFunctionsList = array();
    
    protected $_functionArgs;
    
    public function __construct()
    {
        
    }
    
    protected function get($functionName,  $accessLevel,  $functionBody)
    {
        $this->_getFunctionsList[] = array('name' => $functionName,
                                          'access' => $accessLevel,
                                          'function' => $functionBody);
    }
    
    protected function post( $functionName, int $accessLevel,  $functionBody)
    {
        $this->_postFunctionsList[] = array('name' => $functionName,
                                          'access' => $accessLevel,
                                          'function' => $functionBody);
    }
    
    protected function put($functionName, int $accessLevel, $functionBody)
    {
        $this->_putFunctionsList[] = array('name' => $functionName,
                                          'access' => $accessLevel,
                                          'function' => $functionBody);
    }
    
    protected function delete($functionName, int $accessLevel, $functionBody)
    {
        $this->_deleteFunctionsList[] = array('name' => $functionName,
                                          'access' => $accessLevel,
                                          'function' => $functionBody);
    }
}
?>