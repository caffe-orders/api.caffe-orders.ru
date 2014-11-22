<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of comments
 *
 * @author Broff
 */
class comments extends Module implements Module_Interface
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
                'id'
            ); 
            //(id, name, addres, phones, working_time, short_info, info, img, album)
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $query = DbWorker::GetInstance()->prepare('SELECT id, user_id, comment FROM comments WHERE id = :id');
                $query->execute(array(':id' => $args['id']));
                $result = $query->fetch();
                if($result != false)
                {
                    $query = DbWorker::GetInstance()->prepare('SELECT * FROM users WHERE id = :id');
                    $query->execute(array(':id' => $result['user_id']));
                    $resultUser = $query->fetch();
                    if($resultUser != false)
                    {
                        $result['firstname'] = $resultUser['firstname'];
                        $result['lastname'] = $resultUser['lastname'];
                        $queryResponseData = array('err_code' => '200', 'data' => $result);
                    }
                    else
                    {
                        $queryResponseData = array('err_code' => '602');
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
        
        $this->get('list', 1, function($args)
        {
            $parametersArray = array(
                'limit',
                'offset',
                'record_type',
                'record_id'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args) == true)
            {
                $offset = (int)$args['offset'];
                $limit = (int)$args['limit'];
                $record_type = (int)$args['record_type'];                
                $record_id = (int)$args['record_id'];
                //(id, name, address, phones, working_time, short_info, info, img, album)
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM comments WHERE record_id = :record_id AND record_type = :record_type ORDER BY id DESC LIMIT :offset , :limit');
                $query->bindParam(':offset',$offset , PDO::PARAM_INT); 
                $query->bindParam(':limit', $limit, PDO::PARAM_INT); 
                $query->bindParam(':record_type', $record_type, PDO::PARAM_INT); 
                $query->bindParam(':record_id', $record_id, PDO::PARAM_INT); 
                $query->execute();
                $result = $query->fetchAll();
                if($result != false)
                {
                    $sendData = array();
                    foreach($result as $item)
                    {
                        $query = DbWorker::GetInstance()->prepare('SELECT * FROM users WHERE id = :id');
                        $query->execute(array(':id' => $item['user_id']));
                        $resultUser = $query->fetch();
                        if($resultUser != false)
                        {
                            $sendData[] = array
                            (
                                'id' => $item['id'],
                                'firstname' => $resultUser['firstname'],
                                'lastname' => $resultUser['lastname'],
                                'user_id' => $item['user_id'],
                                'comment' => $item['comment']
                            );
                        }
                    }
                    $queryResponseData = array('err_code' => '200', 'data' => $sendData);
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
        
        $this->get('new', 10, function($args)
        {
            $parametersArray = array(
                'user_id',
                'record_type',
                'record_id',
                'comment'
            ); 
            //(id, name, addres, phones, working_time, short_info, info, img, album)
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $query = 'INSERT comments (record_type, user_id, record_id, comment) 
                                VALUES (:record_type, :user_id, :record_id, :comment)';                    
                    $query = DbWorker::GetInstance()->prepare($query);
                    
                    $queryArgsList = array(
                        ':record_type' => $args['record_type'],
                        ':user_id' => $args['user_id'], 
                        ':record_id' => $args['record_id'],
                        ':comment' => $args['comment']
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
