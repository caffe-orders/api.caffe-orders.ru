<?php
class Api
{
    private $_requestType;
    private $_requestArgs;
    private $_requestUrlNodes = array();
    
    private $_module;
    private $_accessLevel;
    
    public function __construct()
    {
        $this->_requestType = $_SERVER['REQUEST_METHOD'];
        $this->_requestArgs = $_REQUEST;
        $this->parseUrl($_SERVER['REQUEST_URI']);
        $this->checkAccessLevel();
        $this->loadModule();        
        $responceData = $this->getResponseData($this->_requestType, $this->_requestUrlNodes[2], $this->_requestArgs, $this->_accessLevel);
        $this->sendResponse($responceData);
    }
    // parse url into nodes
    private function parseUrl($url)
    {
        $delimetersList = array('/', '?');
        $transUrl = str_replace($delimetersList, $delimetersList[0], $url);
        $urlNodesList = explode($delimetersList[0], $transUrl);
        foreach($urlNodesList as $val)
        {
            $this->_requestUrlNodes[] = strtolower($val);
        }
    }
    // load module by name | need fix?
    private function loadModule()
    {
        $moduleName = $this->_requestUrlNodes[1];
        if(file_exists(MODULE_PATH . '/' . $moduleName . '.php'))
        {
            $this->_module = new $moduleName;  
        }
    }
    // send request to module, module returned output data (need to be array)
    private function getResponseData($functionType = 'GET', $functionName = null, $functionArgs = null, $accessLevel = 0)
    {
        if(isset($this->_module))
        {
            $outputData = $this->_module->RunModuleFunction($functionType, $functionName, $functionArgs, $accessLevel);
            return $outputData;
        }
        return array('err_code' => '400');
    }
    // send response to client and sets header content type | need to add xml format responce?
    private function sendResponse($data)
    {   
        $errorCode = '200';
        
        if(isset($data['err_code']))
        {       
            $errorCode = $data['err_code'];        
        }         
        
        $this->setResponseHeaders('application/json', $errorCode); 
        echo(json_encode($data));       
    }
    // sets responce content type and error state
    private function setResponseHeaders($responceType, $errorCode)
    {
        $errorCode = ErrorCodeDictionary::GetCode($errorCode);
        
        header('Content-Type: ' . $responceType);
        header('HTTP/1.0 ' . $errorCode);
    }
    // returns access level if user is logged in, if not return 0
    private function checkAccessLevel()
    {        
        $accessLevel = 0;
        if(isset($_SESSION['id']))
        {
            $userId = (int)$_SESSION['id'];
            $query = DbWorker::GetInstance()->prepare('SELECT access_level FROM users WHERE id = ?');
            $query->execute(array($userId));
            $queryResponseData = $query->fetch();
            
            if(count($queryResponseData) == 1)
            {
                $accessLevel = 1;
            }

        }
        $this->_accessLevel = $accessLevel;
    }
}
?>