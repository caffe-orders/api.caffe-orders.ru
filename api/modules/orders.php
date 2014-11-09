<?php
class Orders extends Module implements Module_Interface
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
        //returns info about selected caffe
        $this->get('info', 1, function($args)
        {
            $parametersArray = array(
                'order_id'
            ); 
            //(id, name, addres, phones, working_time, short_info, info, img, album)
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM orders WHERE id = :order_id');
                $query->execute(array(':order_id' => $args['order_id']));
                $queryResponseData = array('err_code' => '200', 'data' => $query->fetch());
            }
            else
            {                
                $queryResponseData = array('err_code' => '602');
            }
            
            return $queryResponseData;
        });
        
        $this->get('list', 1, function($args)
        {
            $parametersArray = array(
                'limit',
                'offset',
                'order_id'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args) == true)
            {
                $offset = (int)$args['offset'];
                $limit = (int)$args['limit'];
                $caffe_id = (int)$args['room_id'];
                //(id, name, address, phones, working_time, short_info, info, img, album)
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM orders WHERE order_id = :order_id ORDER BY id DESC LIMIT :offset , :limit');
                $query->bindParam(':offset',$offset , PDO::PARAM_INT); 
                $query->bindParam(':limit', $limit, PDO::PARAM_INT); 
                $query->bindParam(':order_id', $caffe_id, PDO::PARAM_INT); 
                $query->execute();
                $queryResponseData = array('err_code' => '200', 'data' => $query->fetchAll());
            }
            else
            {                
                $queryResponseData = array('err_code' => '602');
            }
            
            return $queryResponseData;
        });
        
        //create new room PUT responce type
        $this->get('newshort', 0, function($args)
        {
            $parametersArray = array(
                'table_id'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM tables WHERE id = :table_id');
                $query->execute(array(':table_id' => (int)$args['table_id']));
                $result = $query->fetch();
                if($result)
                {
                    if((int)$result['status'] == 0)
                    {
                        $userId = (int)$_SESSION['id'];
                        $query = DbWorker::GetInstance()->prepare('SELECT * FROM orders WHERE user_id = :user_id');
                        $query->execute(array(':user_id' => $userId));
                        $result = $query->fetch();
                        if(!$result)
                        {
                            $query = DbWorker::GetInstance()->prepare('UPDATE tables SET status = 1 WHERE id = :id');     
                            $query->execute(array(':id' => (int)$args['table_id']));
                            
                            $queryStr = 'INSERT orders (table_id, user_id, time, type, status, enter_time, activated_code, activate_attempts) 
                                VALUES (:table_id, :user_id, :time, :type, :status, :enter_time, :activated_code, :activate_attempts)';                    
                            $query = DbWorker::GetInstance()->prepare($queryStr);
                    
                            $code = rand(0, 9999);
                            
                            $queryArgsList = array(
                            ':table_id' => (int)$args['table_id'], 
                            ':user_id' => $userId,
                            ':time' => date('Y-m-d H:i:s'),
                            ':type' => 1,
                            ':status' => 1, 
                            ':enter_time' => date('Y-m-d H:i:s'),
                            ':activated_code' => $code,
                            ':activate_attempts' => 0
                            );
                            if($query->execute($queryArgsList))
                            {
                                $queryResponseData = array('err_code' => '200');
                            }
                            else
                            {
                                $queryResponseData = array('err_code' => '401','data' => 'Undefined error');
                            }
                        }
                        else
                        {
                            $queryResponseData = array('err_code' => '401','data' => 'The user has already registered table');
                        }
                    }
                    else
                    {
                        $queryResponseData = array('err_code' => '400', 'data' => 'Table busy');
                    }    
                }
                else
                {
                    $queryResponseData = array('err_code' => '400', 'data' => 'Table in not found');
                }
            }
            else
            {                
                $queryResponseData = array('err_code' => '602');
            }
            
            return $queryResponseData;
        });
        
        $this->get('newlong', 0, function($args)
        {
            $parametersArray = array(
                'table_id',
                'enter_time'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM tables WHERE id = :table_id');
                $query->execute(array(':table_id' => (int)$args['table_id']));
                $result = $query->fetch();
                if($result)
                {
                    if((int)$result['status'] == 0)
                    {
                        $userId = (int)$_SESSION['id'];
                        $query = DbWorker::GetInstance()->prepare('SELECT * FROM orders WHERE user_id = :user_id');
                        $query->execute(array(':user_id' => $userId));
                        $result = $query->fetch();
                        if(!$result)
                        {
                            $query = DbWorker::GetInstance()->prepare('UPDATE tables SET status = 1 WHERE id = :id');     
                            $query->execute(array(':id' => (int)$args['table_id']));
                            
                            $queryStr = 'INSERT orders (table_id, user_id, time, type, status, enter_time, activated_code, activate_attempts) 
                                VALUES (:table_id, :user_id, :time, :type, :status, :enter_time, :activated_code, :activate_attempts)';                    
                            $query = DbWorker::GetInstance()->prepare($queryStr);
                            
                            $code = rand(0, 9999);
                            
                            $queryArgsList = array(
                            ':table_id' => (int)$args['table_id'], 
                            ':user_id' => $userId,
                            ':time' => date('Y-m-d H:i:s'),
                            ':type' => 2,
                            ':status' => 1, 
                            ':enter_time' => date('Y-m-d H:i:s'),
                            ':activated_code' => $code,
                            ':activate_attempts' => 0
                            );
                            if($query->execute($queryArgsList))
                            {
                                $queryResponseData = array('err_code' => '200');
                            }
                            else
                            {
                                $queryResponseData = array('err_code' => '401','data' => 'Undefined error');
                            }
                        }
                        else
                        {
                            $queryResponseData = array('err_code' => '401','data' => 'The user has already registered table');
                        }
                    }
                    else
                    {
                        $queryResponseData = array('err_code' => '400', 'data' => 'Table busy');
                    }    
                }
                else
                {
                    $queryResponseData = array('err_code' => '400', 'data' => 'Table in not found');
                }
            }
            else
            {                
                $queryResponseData = array('err_code' => '602');
            }
            
            return $queryResponseData;
        });
        
        $this->get('activate', 1, function($args)
        {
            $requiredParams = array(
                'code'
            );
           
            $queryResponseData =  array();
            if(Module::CheckFunctionArgs($requiredParams, $args))
            {
                $userId = (int)$_SESSION['id'];
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM orders WHERE user_id = :user_id');     
                $query->execute(array(':user_id' => $userId));
                $result = $query->fetch();
                if($result)
                {
                    $timeNow = date('Y-m-d H:i:s', mktime(date("H"), date("i")-3, date("s"), date("m"), date("d"), date("Y")));
                    $timeReg = $result['time'];
                    if($timeNow > $timeReg)
                    {
                        $query = DbWorker::GetInstance()->prepare('UPDATE tables SET status = 0 WHERE id = :id');     
                        $query->execute(array(':id' => $result['table_id']));
                                    
                        $query = DbWorker::GetInstance()->prepare('DELETE FROM orders WHERE user_id = :user_id');                
                        $query->execute(array(':user_id' => $userId));
                                
                        $queryResponseData = array('err_code' => '603', 'data' => 'order deleted');
                    }
                    else
                    {
                        if($result['status'] == 1)
                        {
                            if($result['activated_code'] == $args['code'])
                            {                        
                                $query = DbWorker::GetInstance()->prepare('UPDATE tables SET status = 2 WHERE id = :id');     
                                $query->execute(array(':id' => (int)$result['table_id']));
                        
                                $query = DbWorker::GetInstance()->prepare('UPDATE orders SET status = 2 WHERE user_id = :user_id');     
                                $query->execute(array(':user_id' => $userId));
                        
                                $queryResponseData = array('err_code' => '200'); 
                            }
                            else
                            {
                                if($result['activate_attempts'] == 2)
                                {
                                    $query = DbWorker::GetInstance()->prepare('UPDATE tables SET status = 0 WHERE id = :id');     
                                    $query->execute(array(':id' => $result['table_id']));
                                    
                                    $query = DbWorker::GetInstance()->prepare('DELETE FROM orders WHERE user_id = :user_id');                
                                    $query->execute(array(':user_id' => $userId));
                                
                                    $queryResponseData = array('err_code' => '603', 'data' => 'order deleted');
                                }
                                else
                                {
                                    $attempts = (int)$result['activate_attempts'] + 1;
                                    $query = DbWorker::GetInstance()->prepare('UPDATE orders SET activate_attempts = :attempts WHERE user_id = :user_id');     
                                    $query->execute(array(':attempts' => $attempts,':user_id' => $userId));
                            
                                    $queryResponseData = array('err_code' => '603', 'data' => 'Wrong code');
                                }                            
                            }
                        }
                        else
                        {
                            $queryResponseData = array('err_code' => '200');
                        }
                    }
                }
                else
                {
                    $queryResponseData = array('err_code' => '604');
                }   
            }
            else
            {
                $queryResponseData = array('err_code' => '602');
            }
            return $queryResponseData;
        });
        
        $this->get('delete', 1, function($args)
        {
            $requiredParams = array(
                'order_id'
            );
           
            $queryResponseData =  array();
            if(Module::CheckFunctionArgs($requiredParams, $args))
            {
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM orders WHERE id = :order_id');     
                $query->execute(array(':order_id' => $args['order_id']));
                $result = $query->fetch();
                if($result)
                {
                    $tableId = (int)$result['table_id'];
                    $query = DbWorker::GetInstance()->prepare('UPDATE tables SET status = 0 WHERE id = :id');     
                    $query->execute(array(':id' => $tableId));
                                    
                    $query = DbWorker::GetInstance()->prepare('DELETE FROM orders WHERE id = :order_id');                
                    if($query->execute(array(':order_id' => $args['order_id'])))
                    {
                        $queryResponseData = array('err_code' => '200'); 
                    }
                    else
                    {
                        $queryResponseData = array('err_code' => '603');
                    }
                }
                else
                {
                    $queryResponseData = array('err_code' => '604');
                }   
            }
            else
            {
                $queryResponseData = array('err_code' => '602');
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
?>


