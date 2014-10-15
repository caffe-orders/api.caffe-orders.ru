<?php 
class Module
{
    protected $_getFunctionsList = array();
    protected $_postFunctionsList = array();
    protected $_putFunctionsList = array();
    protected $_deleteFunctionsList = array();
    
    protected $_dbWorker;
    
    public function __construct()
    {
        
    }
    
    protected function get($functionName,  $accessLevel,  $functionBody)        //Получение данных
    {
        $this->_getFunctionsList[] = array('name' => $functionName,
                                           'access' => $accessLevel,
                                           'function' => $functionBody);
    }
    
    protected function post($functionName, $accessLevel,  $functionBody)        //Изменение
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
}
?>