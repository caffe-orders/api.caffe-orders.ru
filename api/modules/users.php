<?php 
class Users extends Module implements Module_Interface
{    
    public function __construct()
    {        
        $aza = function ($Args)
        {
            print_r($Args);
        };
        
        $this->get('name', 0, $aza);
    }
    
    public function RunModuleFunction( $functionType, $functionName,  $functionArgs,  $accessLevel)
    {
        $this->_functionArgs = $functionArgs;
        //$functionType = strtolower($functionType);
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
                    }
                }
            break;
        }
        
        return $outputData($functionArgs);
    }     
}
    