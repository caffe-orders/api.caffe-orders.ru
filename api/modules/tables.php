<?php
class Tables extends Module implements Module_Interface
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
                'room_id'
            ); 
            //(id, name, addres, phones, working_time, short_info, info, img, album)
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM tables WHERE room_id = :room_id');
                $query->execute(array(':room_id' => $args['room_id']));
                $queryResponseData = array('err_code' => '200', 'data' => $query->fetchAll());
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
                'room_id'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args) == true)
            {
                $offset = (int)$args['offset'];
                $limit = (int)$args['limit'];
                $caffe_id = (int)$args['room_id'];
                //(id, name, address, phones, working_time, short_info, info, img, album)
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM tables WHERE room_id = :room_id ORDER BY id DESC LIMIT :offset , :limit');
                $query->bindParam(':offset',$offset , PDO::PARAM_INT); 
                $query->bindParam(':limit', $limit, PDO::PARAM_INT); 
                $query->bindParam(':room_id', $caffe_id, PDO::PARAM_INT); 
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
        $this->get('new', 1, function($args)
        {
            $parametersArray = array(
                'number',
                'room_id',
                'xPos',
                'yPos',
                'tableType',
                'status'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $query = DbWorker::GetInstance()->prepare('SELECT id FROM rooms WHERE id = :room_id');
                $query->execute(array(':room_id' => $args['room_id']));
                $result = $query->fetch();
                if($result)
                {
                    $query = DbWorker::GetInstance()->prepare('SELECT id FROM tables WHERE number = :number AND room_id = :room_id');
                    $query->execute(array(':number' => $args['number'], ':room_id' => $args['room_id']));
                    $result = $query->fetch();
                    if(!$result)
                    {
                        $queryStr = 'INSERT tables (number, room_id, xPos, yPos, tableType, status) 
                                VALUES (:number, :room_id, :xPos, :yPos, :tableType, :status)';                    
                        $query = DbWorker::GetInstance()->prepare($queryStr);
                    
                        $queryArgsList = array(
                            ':number' => $args['number'],
                            ':room_id' => $args['room_id'],
                            ':xPos' => $args['xPos'],
                            ':yPos' => $args['yPos'],
                            ':tableType' => $args['tableType'],
                            ':status' => $args['status']
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
                        $queryResponseData = array('err_code' => '401','data' => 'Table exist');
                    }
                }
                else
                {
                    $queryResponseData = array('err_code' => '400', 'data' => 'room in not found');
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
                'id'
            );
           
            $queryResponseData =  array();
            if(Module::CheckFunctionArgs($requiredParams, $args))
            {
                $query = DbWorker::GetInstance()->prepare('DELETE FROM tables WHERE id = :id');                
                if($query->execute(array(':id' => $args['id'])))
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
                $queryResponseData = array('err_code' => '602');
            }
            return $queryResponseData;
        });
        
        $this->get('status', 1, function($args)
        {
            $requiredParams = array(
                'id',
                'status'
            );
           
            $queryResponseData =  array();
            if(Module::CheckFunctionArgs($requiredParams, $args))
            {                    
                $query = DbWorker::GetInstance()->prepare('SELECT id FROM tables WHERE id = :id');
                $query->execute(array(':id' => $args['id']));
                $result = $query->fetch();
                if($result)
                {
                    $query = DbWorker::GetInstance()->prepare('UPDATE tables SET status = :status WHERE id = :id');                
                    if($query->execute(array(':id' => $args['id'], ':status' => $args['status'])))
                    {
                        $queryResponseData = array('err_code' => '200', 'data' => 'set status ok');
                    }
                    else
                    {
                        $queryResponseData = array('err_code' => '601');
                    }    
                }
                else
                {
                    $queryResponseData = array('err_code' => '400', 'data' => 'table not found');
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

