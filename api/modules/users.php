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
            return array('err_code' => '400');
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
        //return  all user info 
        $this->get('allinfo', 10, function($args)
        {
            $parametersArray = array(
                'id'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args) == true)
            {
                $query = DbWorker::GetInstance()->prepare('SELECT id, mail, password_hash, access_level, firstname, lastname, reg_code FROM users WHERE id = ?');
                $query->execute(array($args['id']));
                $queryResponseData = array('err_code' => '200', 'data' => $query->fetch());
            }
            else
            {                
                $queryResponseData = array('err_code' => '400');
            }
            
            return $queryResponseData;
        });
        
        //return user info
        $this->get('info', 0, function($args)
        {
            $parametersArray = array(
                'id'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args) == true)
            {
                $query = DbWorker::GetInstance()->prepare('SELECT id, access_level, firstname, lastname FROM users WHERE id = :id');
                $query->execute(array(':id' => $args['id']));
                $queryResponseData = array('err_code' => '200', 'data' => $query->fetch());
            }
            else
            {                
                $queryResponseData = array('err_code' => '400');
            }
            
            return $queryResponseData;
        });
        
        //create new user
        $this->get('new', 0, function($args)
        {
            $parametersArray = array(
                'email',
                'password',
                'phone',
                'firstname',
                'lastname'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $query = DbWorker::GetInstance()->prepare('SELECT id FROM users WHERE email = :email OR phone = :phone');
                $query->execute(array(':email' => $args['email'], ':phone' => $args['phone']));
                $result = $query->fetch();
                if(!$result)
                {
                    $query = 'INSERT users (email, password_hash, phone, access_level, firstname, lastname,  reg_code, reg_time) 
                                VALUES (:email, :password_hash, :phone, :access_level, :firstname, :lastname, :reg_code, :reg_time)';
                    $generatedRegCode = rand(0,9999); //to be continued
                    
                    $query = DbWorker::GetInstance()->prepare($query);
                    $queryArgsList = array(
                        ':email' => $args['email'],
                        ':password_hash' => md5($args['password']),
                        ':phone' => $args['phone'], 
                        ':firstname' => $args['firstname'],
                        ':lastname' => $args['lastname'], 
                        ':access_level' => 1,                             
                        ':reg_code' => $generatedRegCode, 
                        ':reg_time' => date('Y-m-d H:i:s')
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
                    $queryResponseData = array('err_code' => '400', 'data' => 'phone or email is occupied');
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

    