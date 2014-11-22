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
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM users WHERE id = ?');
                $query->execute(array($args['id']));
                $queryResponseData = array('err_code' => '200', 'data' => $query->fetch());
            }
            else
            {                
                $queryResponseData = array('err_code' => '602');
            }
            
            return $queryResponseData;
        });
                
        $this->get('login', 10, function($args)
        {
            if(isset($args['email']) && isset($args['password']))
            {
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM users WHERE email = ?');
                $query->execute(array($args['email']));
                if($response = $query->fetch())
                {
                    if($response['password_hash'] == md5($args['password']))
                    {
                        $queryResponseData = array('err_code' => '200', 'data' => $response);
                    }
                    else
                    {
                        $queryResponseData = array('err_code' => '401');
                    }
                }
            }
            elseif(isset($args['id']) && isset($args['key']))
            {
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM users WHERE id = ?');
                $query->execute(array($args['id']));
                if($response = $query->fetch())
                {
                    if($response['secret_key'] == $args['key'])
                    {
                        $queryResponseData = array('err_code' => '200', 'data' => $response);
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
        
        $this->get('fullinfolist', 10, function($args)
        {
            $parametersArray = array(
                'limit',
                'offset'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args) == true)
            {
                $offset = (int)$args['offset'];
                $limit = (int)$args['limit'];
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM users ORDER BY id DESC LIMIT :offset , :limit');
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
                    $query = 'INSERT users (email, password_hash, secret_key, phone, access_level, firstname, lastname,  reg_code, reg_time) 
                                VALUES (:email, :password_hash, :secret_key, :phone, :access_level, :firstname, :lastname, :reg_code, :reg_time)';
                    $generatedRegCode = rand(0,9999); //We need send this cod in phone user on sms message
                    
                    $query = DbWorker::GetInstance()->prepare($query);
                    $queryArgsList = array(
                        ':email' => $args['email'],
                        ':password_hash' => md5($args['password']),
                        ':secret_key' => md5($args['password'].$agrs['phone']),
                        ':phone' => $args['phone'], 
                        ':firstname' => $args['firstname'],
                        ':lastname' => $args['lastname'], 
                        ':access_level' => 0,                             
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
        $this->get('code', 1, function($args)
        {
            $parametersArray = array(
                'email',
                'code'
            );
            
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {            
                $query = DbWorker::GetInstance()->prepare('SELECT id, access_level, reg_code, reg_time FROM users WHERE email = :email');
                $query->execute(array(':email' => $args['email']));
                $result = $query->fetch();
                if($result)
                {
                    if($result['access_level']==0)
                    {
                        $timeNow = date('Y-m-d H:i:s', mktime(date("H"), date("i")-2, date("s"), date("m"), date("d"), date("Y")));
                        $timeReg = $result['reg_time'];
                        if($timeNow > $timeReg)
                        {
                            $generatedRegCode = rand(0,9999);
                            //send sms
                            $query = DbWorker::GetInstance()->prepare('UPDATE users SET reg_code = :reg_code , reg_time = :reg_time WHERE email = :email');
                            $query->execute(array(':email' => $args['email'], ':reg_code' => $generatedRegCode, ':reg_time' => date('Y-m-d H:i:s')));
                            $queryResponseData = array('err_code' => '400', 'data' => 'time lose'.$generatedRegCode);    
                        }
                        else
                        {                        
                            if($result['reg_code']==$args['code'])
                            {                                
                                $query = DbWorker::GetInstance()->prepare('UPDATE users SET access_level = 1, reg_code = 0 , reg_time = :reg_time WHERE email = :email');
                                $query->execute(array(':email' => $args['email'], ':reg_time' => date('Y-m-d H:i:s')));
                                $queryResponseData = array('err_code' => '200', 'data' => 'user activated');
                            }
                            else
                            {
                                $generatedRegCode = rand(0,9999);
                                //send sms
                                $query = DbWorker::GetInstance()->prepare('UPDATE users SET reg_code = :reg_code , reg_time = :reg_time WHERE email = :email');
                                $query->execute(array(':email' => $args['email'], ':reg_code' => $generatedRegCode, ':reg_time' => date('Y-m-d H:i:s')));
                                $queryResponseData = array('err_code' => '400', 'data' => 'entered code incorrect'.$generatedRegCode);
                            }
                        }    
                    }
                    else
                    {
                        $queryResponseData = array('err_code' => '200', 'data' => 'user activated');
                    }
                }
                else
                {
                    $queryResponseData = array('err_code' => '400', 'data' => 'entered code incorrect');
                }
            }
            else
            {                
                $queryResponseData = array('err_code' => '602', 'data' => 'args error');
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

    