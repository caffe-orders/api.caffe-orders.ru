<?php 
class Users extends Module implements Module_Interface
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
        $this->_functionArgs = $functionArgs;
        $functionName = strtolower($functionName);
        $outputData = function($args)
        {
            return array('err_code' => '400 Bad Request');
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
        
        return $outputData($this->_functionArgs);
    }     
    
    public function SetGetFunctions()
    {   
        $this->get('info', 0, function($args)
        {
            $parametersArray = array(
                'id'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args) == true)
            {
                $query = DbWorker::GetInstance()->prepare('SELECT id, mail, password_hash, name, lastname, access_level, reg_code FROM users WHERE id = ?');
                $query->execute(array($args['id']));
                $queryResponseData = array('err_code' => '200', 'data' => $query->fetch());
            }
            else
            {                
                $queryResponseData = array('err_code' => '400');
            }
            
            return $queryResponseData;
        });
        
        $this->get('new', 0, function($args)
        {
            $parametersArray = array(
                'mail',
                'password',
                'phone',
                'firstname',
                'lastname'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $query = DbWorker::GetInstance()->prepare('SELECT id FROM users WHERE phone = ?');
                $query->execute(array($args['phone']));
                if($result = $query->fetch();)
                {
                    $query = 'INSERT users (mail, password_hash, phone, firstname, lastname, access_level, reg_code, reg_time) 
                        VALUES (:mail, :password_hash, :phone, :firstname, :lastname, :access_level, :reg_time, reg_time)';
                    $generatedRegCode = rand(0,9999); //to be continued
                    
                    $query = DbWorker::GetInstance()->prepare($query);
                    $queryArgsList = array(
                        ':mail' => $args['mail'],
                        ':password_hash' => md5($args['password']),
                        ':phone' => $args['phone'], 
                        ':firstname' => $args['firstname'],
                        ':lastname' => $args['lastname'], 
                        ':access_level' => '1',                             
                        ':reg_code' => $generatedRegCode, 
                        ':reg_time' => date('Y-m-d H:i:s');
                    );
                    if($query->execute($queryArgsList))
                    {
                        $queryResponseData = array('err_code' => '200');
                    }
                    else
                    {
                        $queryResponseData = array('err_code' => '401');
                    }        
                }
                else
                {
                    $queryResponseData = array('err_code' => '400', 'data' => 'phone is occupied');
                }
            }
            else
            {                
                $queryResponseData = array('err_code' => '401', 'data' => 'args error');
            }
            
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

    