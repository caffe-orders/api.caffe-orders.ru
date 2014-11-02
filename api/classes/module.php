<?php 
class Module
{
    protected $_getFunctionsList = array();
    protected $_postFunctionsList = array();
    protected $_putFunctionsList = array();
    protected $_deleteFunctionsList = array();
    
    protected $_accessLevel;
    
    public function __construct()
    {
        
    }
    
    protected function get($functionName, $accessLevel, $functionBody)        //Получение данных
    {
        $this->_getFunctionsList[] = array('name' => $functionName,
                                           'access' => $accessLevel,
                                           'function' => $functionBody);
    }
    
    protected function post($functionName, $accessLevel, $functionBody)        //Изменение
    {
        $this->_postFunctionsList[] = array('name' => $functionName,
                                            'access' => $accessLevel,
                                            'function' => $functionBody);
    }
    
    protected function put($functionName, $accessLevel, $functionBody)          //Создание
    {
        $this->_putFunctionsList[] = array('name' => $functionName,
                                           'access' => $accessLevel,
                                           'function' => $functionBody);
    }
    
    protected function delete($functionName, $accessLevel, $functionBody)       //Удаление
    {
        $this->_deleteFunctionsList[] = array('name' => $functionName,
                                              'access' => $accessLevel,
                                              'function' => $functionBody);
    } 
    
    static public function CheckFunctionArgs($parametersArray, $args)
    {
        foreach($parametersArray as $key)
        {
            if(!isset($args[$key]))            
            {
                return false;
            }
        }        
        return true;
    }
    
    static public function RunOtherModuleFunction($moduleName, $functionName, $functionType, $functionArgs, $accessLevel)
    {
        $module = new $moduleName;
        
        return $module->RunModuleFunction($functionType, $functionName, $functionArgs, $accessLevel);
    }
}
?>