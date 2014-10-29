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
        $functionType = strtolower($functionType);
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
        //return all user info GET responce type
        $this->get('allinfo', 10, function($args)
        {
            $parametersArray = array(
                'id'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args) == true)
            {
                $query = DbWorker::GetInstance()->prepare('SELECT id, mail, password_hash, phone, access_level, firstname, lastname, reg_code FROM users WHERE id = ?');
                $query->execute(array($args['id']));
                $queryResponseData = array('err_code' => '200', 'data' => $query->fetch());
            }
            else
            {                
                $queryResponseData = array('err_code' => '602');
            }
            
            return $queryResponseData;
        });
                
        //return user info GET responce type
        $this->get('info', 1, function($args)
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
                $queryResponseData = array('err_code' => '602');
            }
            
            return $queryResponseData;
        });
        
        //return user list GET responce type
        $this->get('list', 1, function($args)
        {
            $parametersArray = array(
                'limit',
                'offset'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args) == true)
            {
                $offset = (int)$args['offset'];
                $limit = (int)$args['limit'];
                $query = DbWorker::GetInstance()->prepare('SELECT id, access_level, firstname, lastname FROM users ORDER BY id DESC LIMIT :offset , :limit');
                $query->bindParam(':offset',$offset , PDO::PARAM_INT); 
                $query->bindParam(':limit', $limit, PDO::PARAM_INT); 
                $query->execute();
                $queryResponseData = array('err_code' => '200', 'data' => $query->fetchAll());
            }
            else
            {                
                $queryResponseData = array('err_code' => '602');
            }
            
            return $queryResponseData;
        });        
        
        //create new user PUT responce type
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
                    $generatedRegCode = rand(0,9999); //We need send this cod in phone user on sms message
                    
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
                        $queryResponseData = array('err_code' => '200','data' => $generatedRegCode);
                    }
                    else
                    {
                        $queryResponseData = array('err_code' => '401','data' => 'Undefined error');
                    }        
                }
                else
                {
                    $queryResponseData = array('err_code' => '400', 'data' => 'phone or email is occupied');
                }
            }
            else
            {                
                $queryResponseData = array('err_code' => '602');
            }
            
            return $queryResponseData;
        });
        
        //that function receives a registration code POST responce type
        $this->get('code', 0, function($args)
        {
            $parametersArray = array(
                'id',
                'code'
            );
            
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {            
                
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

    