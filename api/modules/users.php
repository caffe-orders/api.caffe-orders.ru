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
                'telephone',
                'name',
                'lastname'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args) == true)
            {
                $query = DbWorker::GetInstance()->prepare('SELECT id FROM users WHERE telephone = :telephone');
                $query->execute(array(':telephone' => $args['telephone']));
                $result = $query->fetch();
                
                if($result==null)
                {
                    $str='INSERT users (mail, password_hash, telephone, name, lastname, access_level, reg_code, reg_time) VALUES (:mail, :password_hash, :telephone, :name, :lastname, :access_level, :reg_code, :reg_time)';
                    $generetedRegCode = rand(0,999);                            //Тут нужна функция для генерации случайного кода приблизительно из 3-4 цифр
                    $arr = array(
                        ':mail' => $args['mail'], 
                        ':password_hash' => md5($args['password'].'hash'),      //Пускай доступ 1 будет у пользователей с неподтвержденным номером телефона
                        ':telephone' => $args['telephone'], 
                        ':name' => $args['name'], 
                        ':lastname' => $args['lastname'], 
                        ':access_level' => '1',                             
                        ':reg_code' => $generetedRegCode, 
                        ':reg_time' => microtime()
                    );
                    
                    $query = DbWorker::GetInstance()->prepare($str);
                    $query->execute($arr);
                    $insertedId = $query->fetch();
                    
                    if($insertedId > 0)
                    {
                        $queryResponseData = array('err_code' => '200', 'data' => 'User add true');
                    }
                    else
                    {
                        $queryResponseData = array('err_code' => '400', 'data' => 'User add faild');
                    }                    
                }
                else
                {
                    $queryResponseData = array('err_code' => '400', 'data' => 'telephone use other user');
                }
            }
            else
            {                
                $queryResponseData = array('err_code' => '400', 'data' => 'error in entered args');
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

    