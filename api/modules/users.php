<?php 
class Users extends Module implements Module_Interface
{    
    public function __construct()
    {        
        $this->setGetFunction();
        $this->setPostFunction();
        $this->setPutFunction();
        $this->setDeleteFunction();
    }
    
    public function RunModuleFunction( $functionType, $functionName,  $functionArgs,  $accessLevel)
    {
        $this->_functionArgs = $functionArgs;
        $functionName = strtolower($functionName);
        $outputData = function ($Args)
        {
            echo "error";
        };
        
        switch($functionType)
        {
            case "GET": 
                foreach($this->_getFunctionsList as $functionData)
                {
                    if($functionData['access'] <= $accessLevel && $functionData['name'] == $functionName)
                    {
                        $outputData = $functionData['function'];
                        break;
                    }
                }
            break;
            case "PUT": 
                foreach($this->_putFunctionsList as $functionData)
                {
                    if($functionData['access'] <= $accessLevel && $functionData['name'] == $functionName)
                    {
                        $outputData = $functionData['function'];
                        break;
                    }
                }
            break;
            case "POST": 
                foreach($this->_postFunctionsList as $functionData)
                {
                    if($functionData['access'] <= $accessLevel && $functionData['name'] == $functionName)
                    {
                        $outputData = $functionData['function'];
                        break;
                    }
                }
            break;
            case "DELETE": 
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
        
        return $outputData($functionArgs);
    }     
    
    public function setGetFunction()
    {
        $defaultFunction = function ($Args)
        {
            print_r($Args);
        };
        
        $this->get('name', 0, $defaultFunction);
    }
    
    public function setPostFunction()
    {
        
    }
    
    public function setPutFunction()
    {
        
    }
    
    public function setDeleteFunction()
    {
        
    }
}
    