<?php
class Api
{
    private $_requestType;
    private $_requestArgs;
    private $_requestUrlNodes = array();
    
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
        $this->sendResponse($this->getResponseData($this->_requestType, $this->_requestUrlNodes[2], $this->_requestArgs, $this->_accessLevel));
    }
    
    private function initDbWorker()
    {
        $this->_dbWorker = DbWorker::GetInstance();
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
    
    private function getResponseData($functionType, $functionName = null, $functionArgs = null, $accessLevel = 0)
    {
        $outputData = $this->_module->RunModuleFunction($functionType, $functionName, $functionArgs, $accessLevel);
        return $outputData;
    }
    
    private function sendResponse($data)
    {
        header('Content-Type: application/json'); 
        echo(json_encode($data));       
    }
}
?>