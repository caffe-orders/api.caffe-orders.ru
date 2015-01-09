<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of downloadSessions
 *
 * @author Broff
 */
class downloadsessions extends Module implements Module_Interface
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
        //return user info GET responce type
        $this->get('info', 1, function($args)
        {
            $parametersArray = array(
                'code',
                'user_id'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args) == true)
            {
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM download_sessions WHERE user_id = :user_id AND code = :code');
                $query->execute(array(':user_id' => $args['user_id'], ':code' => $args['code']));
                $result = $query->fetch();
                if($result)
                {
                    if($result['files_count'] > 0)
                    {
                        $queryResponseData = array('err_code' => '200', 'data' => $result['files_count']);
                    }
                    else
                    {
                                         
                        $query = DbWorker::GetInstance()->prepare('DELETE FROM download_sessions WHERE user_id = :user_id AND code = :code');                
                        if($query->execute(array(':user_id' => $args['user_id'], ':code' => $args['code'])))
                        {
                            $queryResponseData = array('err_code' => '200', 'data' => 'FALSE'); 
                        }
                        else
                        {
                            $queryResponseData = array('err_code' => '603', 'data' => 'FALSE');
                        }
                    }                    
                }
                else
                {
                    $queryResponseData = array('err_code' => '200', 'data' => 'FALSE');
                }                
            }
            else
            {                
                $queryResponseData = array('err_code' => '602');
            }
            
            return $queryResponseData;
        });
        
        $this->get('fileload', 1, function($args)
        {
            $parametersArray = array(
                'code',
                'user_id'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args) == true)
            {
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM download_sessions WHERE user_id = :user_id AND code = :code');
                $query->execute(array(':user_id' => $args['user_id'], ':code' => $args['code']));
                $result = $query->fetch();
                if($result)
                {
                    if($result['files_count'] > 0)
                    {
                        $count = $result['files_count'] - 1;
                        $query = DbWorker::GetInstance()->prepare('UPDATE download_sessions SET files_count = :count WHERE user_id = :user_id AND code = :code');     
                        $query->execute(array(':user_id' => $args['user_id'], ':code' => $args['code'], ':count' => $count));
                        $queryResponseData = array('err_code' => '200', 'data' => $count);
                    }
                    else
                    {
                                         
                        $query = DbWorker::GetInstance()->prepare('DELETE FROM download_sessions WHERE user_id = :user_id AND code = :code');                
                        if($query->execute(array(':user_id' => $args['user_id'], ':code' => $args['code'])))
                        {
                            $queryResponseData = array('err_code' => '200', 'data' => 'FALSE'); 
                        }
                        else
                        {
                            $queryResponseData = array('err_code' => '603', 'data' => 'FALSE');
                        }
                    }                    
                }
                else
                {
                    $queryResponseData = array('err_code' => '200', 'data' => 'FALSE');
                }                
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
                'user_id',
                'code',
                'files_count'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args) == true)
            {                
                    $query = 'INSERT download_sessions (code, files_count, user_id) 
                                VALUES (:code, :files_count, :user_id)'; 
                    $sth = DbWorker::GetInstance();
                    $query = $sth->prepare($query);
                    
                    $queryArgsList = array(
                        ':code' => $args['code'],
                        ':files_count' => (int)$args['files_count'], 
                        ':user_id' => (int)$args['user_id']
                    );
                    if($query->execute($queryArgsList))
                    {
                        $queryResponseData = array('err_code' => '200', 'data' => $sth->lastInsertId());
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
