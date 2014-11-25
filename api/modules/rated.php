<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of rated
 *
 * @author Broff
 */
class Rated extends Module implements Module_Interface
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
        $this->get('info', 0, function($args)
        {
            $parametersArray = array(
                'user_id',
                'record_id',
                'record_type'
            );
            
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM rated WHERE user_id = :user_id AND record_id = :record_id AND record_type = :record_type ');
                $arrayList = array(
                    ':user_id' => $args['user_id'],  
                    ':record_id' => $args['record_id'] ,
                    ':record_type' => $args['record_type']
                        ); 
                $query->execute($arrayList);
                $result = $query->fetch();
                
                if($result != false)
                {
                    $queryResponseData = array('err_code' => '200', 'data' => 'true');
                }
                else
                {
                    $queryResponseData = array('err_code' => '400');
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
                'user_id'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args) == true)
            {
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM rated WHERE user_id = :user_id');
                $query->bindParam(':user_id', $args['user_id'] , PDO::PARAM_INT); 
                
                $result = $query->execute();
                if($result != false)
                {
                    $queryResponseData = array('err_code' => '200', 'data' => $query->fetch());
                }
                else
                {
                    $queryResponseData = array('err_code' => '400');
                }
            }
            else
            {                
                $queryResponseData = array('err_code' => '602');
            }
            
            return $queryResponseData;
        });
        
        //create new room PUT responce type
        $this->get('new', 0, function($args)
        {
            $parametersArray = array(
                'user_id',
                'record_id',
                'record_type'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                
                $queryStr = 'INSERT rated (user_id, record_id, record_type) VALUES (:user_id, :record_id, :record_type)';                    
                $query = DbWorker::GetInstance()->prepare($queryStr);
                $arrayList = array(
                    ':user_id' => $args['user_id'],  
                    ':record_id' => $args['record_id'] ,
                    ':record_type' => $args['record_type']
                        );                 
                if($query->execute($arrayList))
                {
                    $queryResponseData = array('err_code' => '200', 'data' => 'true');
                }
                else
                {
                    $queryResponseData = array('err_code' => '400');
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
