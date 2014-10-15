<?php
class Api
{
    private $_requestType;
    private $_requestArgs;
    private $_requestUrlNodes=array();
    
    private $_dbWorker;
    private $_module;
    private $_accessLevel;
    
    public function __construct()
    {
        $this->_requestType = $_SERVER['REQUEST_METHOD'];
        $this->_requestArgs = $_REQUEST;
        //$this->initDbWorker();
        $this->parseUrl($_SERVER['REQUEST_URI']);
        $moduleName =$this->_requestUrlNodes[1];
        $this->loadModuleByName($moduleName);
    }
    
    private function initDbWorker()
    {
        $this->_dbWorker = new DbWorker();
    }
    
    private function parseUrl($url)
    {
        $urlNodesList = explode('/', $url);
        foreach($urlNodesList as $val)
        {
            $this->_requestUrlNodes[] = strtolower($val);
        }        
    }
    
    private function loadModuleByName($moduleName)
    {
        $this->_module = new $moduleName;  
        
        $module = $this->_module;
        $methodName = $this->_requestUrlNodes[2];
        $requestType = $this->_requestType;
        $requestArgs = $this->_requestArgs;
        $parametrs = null;
        
        if(exist($this->_requestUrlNodes[3]))
        {
            $parametrs = $this->_requestUrlNodes[3];
        }
        
        if(method_exists($module, $methodName))
        {
            $module->$methodName($requestType,$requestArgs,$parametrs);
        }
    }
    
}
?>