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
        $urlNodesList = explode('/', $url);
        foreach($urlNodesList as $val)
        {
            $this->_requestUrlNodes[] = strtolower($val);
        }
    }
    // load module by name | need fix?
    private function loadModule()
    {
        $moduleName = $this->_requestUrlNodes[1];
        if(file_exists(MODULE_PATH."/".$moduleName))
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
        return array('err_code' => '400 Bad Request');
    }
    // send response to client and sets header content type | need to add xml format responce?
    private function sendResponse($data)
    {        
        $errorCode = isset($data['err_code']) ? $data['err_code'] : '200 OK';
        $this->setResponseHeaders('application/json', $errorCode); 
        echo(json_encode($data));       
    }
    // sets responce content type and error state
    private function setResponseHeaders($responceType, $errorCode)
    {
        header('Content-Type: ' . $responceType);
        header('HTTP/1.0 ' . $errorCode);
    }
    // returns access level if user is logged in, if not return 0
    private function checkAccessLevel()
    {
        $userId = $_SESSION['id'];
        $accessLevel = 0;
        if(isset($userId))
        {
            $query = DbWorker::GetInstance()->prepare('SELECT access_level FROM users WHERE id = ?');
            $query->execute(array($args['id']));
            $queryResponseData = $query->fetch();
            
            if(count($queryResponceData) == 1)
            {
                $accessLevel = $queryResponseData['access_level'];
            }

        }
        $this->_accessLevel = $accessLevel;
    }
}
?>