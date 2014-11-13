<?php 
class Auth extends Module implements Module_Interface
{    
    public function __construct()
    {        
        $this->SetGetFunctions();
        $this->SetPostFunctions();
        $this->SetPutFunctions();
        $this->SetDeleteFunctions();
    }
    
    public function RunModuleFunction($functionType, $functionName,  $functionArgs,  $accessLevel)
    {
        $this->_accessLevel = $accessLevel;
        $this->_functionArgs = $functionArgs;
        $functionType = strtolower($functionType);
        $functionName = strtolower($functionName);
        $outputData = function($args)
        {
            return array('err_code' => '400');
        };
        
        switch($functionType)
        {
            case "get": 
                foreach($this->_getFunctionsList as $functionData)
                {
                    if($functionData['access'] <= $accessLevel && $functionData['name'] == $functionName)
                    {
                        $outputData = $functionData['function'];
                        break;
                    }
                }
            break;
            case "put": 
                foreach($this->_putFunctionsList as $functionData)
                {
                    if($functionData['access'] <= $accessLevel && $functionData['name'] == $functionName)
                    {
                        $outputData = $functionData['function'];
                        break;
                    }
                }
            break;
            case "post": 
                foreach($this->_postFunctionsList as $functionData)
                {
                    if($functionData['access'] <= $accessLevel && $functionData['name'] == $functionName)
                    {
                        $outputData = $functionData['function'];
                        break;
                    }
                }
            break;
            case "delete": 
                foreach($this->_deleteFunctionsList as $functionData)
                {
                    if($functionData['access'] <= $accessLevel && $functionData['name'] == $functionName)
                    {
                        $outputData = $functionData['function'];
                        break;
                    }
                }
            break;
            
        }
        
        return $outputData($this->_functionArgs);
    }        
    
    public function SetGetFunctions()
    {   
        $this->get('login', 0, function($args)
        {
            $parametersArray = array(
                'email',
                'password'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $query = DbWorker::GetInstance()->prepare('SELECT id, password_hash FROM users WHERE email = ?');
                $query->execute(array($args['email']));
                if($response = $query->fetch())
                {
                    if($response['password_hash'] == md5($args['password']))
                    {
                        $_SESSION['id'] = $response['id'];
                        $queryResponseData = array('err_code' => '200', 'data' => $response['id']);
                    }
                    else
                    {
                        $queryResponseData = array('err_code' => '401');
                    }
                }
            }
            else
            {                
                $queryResponseData = array('err_code' => '400');
            }
            
            return $queryResponseData;
        });
        
        $this->get('logout', 0, function($args)
        {
            session_destroy();
            $queryResponseData = array('err_code' => '200');
            
            return $queryResponseData;
        });
    }
    
    public function SetPostFunctions()
    {
        
    }
    
    public function SetPutFunctions()
    {
        
    }
    
    public function SetDeleteFunctions()
    {
        
    }
}

    