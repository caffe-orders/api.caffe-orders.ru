<?php
class Api
{
    private $_requestType;
    private $_requestArgs;
    private $_requestUrl;
    
    private $_dbWorker;
    private $_module;
    private $_accessLevel;
    
    public function __construct()
    {
        $this->_requestType = $_SERVER['REQUEST_METHOD'];
        $this->_requestArgs = $_REQUEST;
        $this->_requestUrl = $_SERVER['REQUEST_URI'];
        $this->_initDbWorker();
        $this->loadModuleByName($this->parseModuleName($this->_requestUrl));
    }
    
    private function initDbWorker()
    {
        $this->_dbWorker = new DbWorker();
    }
    
    private function parseModuleName($url)
    {
        $urlNodesList = explode('/', $url);
        $moduleName = $urlNodesList[1];
        return $moduleName;
    }
    
    private function loadModuleByName($moduleName)
    {
        try
        {
            $this->_module = new $moduleName;
            throw new Exeption('Module does not exists');
        }
        catch(Exeption $e)
        {
            $this->_module = new Module_404();
        }
    }
    
}
?>