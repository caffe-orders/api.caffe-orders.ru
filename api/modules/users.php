<?php 
class Users extends Module implements Module_Interface
{    
    public function __construct()
    {
        echo 'users module loaded<br>';
        $this->get('name', 0, function(array $functionArgs) {
            echo 'get name zazazaza zaabotalo lalka';
        });
                   
    }
    
    public function RunModuleFunction(string $functionName, string $functionType, $functionArgs, int $accessLevel)
    {
        $this->_functionArgs = $functionArgs;
        $functionType = strtolower($functionType);
        $functionName = strtolower($functionName);
        $outputData = null;
        switch($functionType)
        {
            case 'get': 
                foreach($this->_getFunctionsList as $functionData)
                {
                    if($functionsData['access'] == 0 && $functionsData['name'] == $functionName)
                    {
                        $outputData = $functionData['function'];
                    }
                }
            break;
        }
        
        return $outputData($functionArgs);
    }   
}
    