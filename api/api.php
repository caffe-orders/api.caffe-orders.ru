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
        $this->runModuleFunction($this->_requestUrlNodes[2], $this->_requestType, $this->_requestArgs);
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
    }
    
    private function runModuleFunction(string $functionName = null, string $functionType, $functionArgs)
    {
        $this->_module->RunModuleFunction($functionName, $functionType, $functionArgs);
    }
    
}
?>