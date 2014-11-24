<?php
class Logs extends Module implements Module_Interface
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
        $this->_accessLevel = $accessLevel;
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
        $this->get('info', 0, function($args)
        {
            $parametersArray = array(
                'id'
            ); 

            $queryResponseData = array();
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM logs WHERE id = :id');
                if($query->execute(array(':id' => $args['id'])))
                {
                      $queryResponseData = array('err_code' => '200', 'data' => $query->fetch());
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
        
        $this->get('list', 0, function($args)
        {
            $parametersArray = array(
                'limit',
                'offset'
            ); 
            
            $queryResponseData = array();
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $offset = (int)$args['offset'];
                $limit = (int)$args['limit'];

                $query = DbWorker::GetInstance()->prepare('SELECT * FROM caffes ORDER BY id DESC LIMIT :offset , :limit');
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
    }
    
    public function SetPostFunctions()
    {
        $this->get('new', 0, function($args)
        {
            $parametersArray = array(
                'user_id', 
                'process'
            ); 
            
            $queryResponseData = array();
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $str = 'INSERT logs (user_id, process, date)'
                        . ' VALUES (:user_id, :process, :date)';
                    
                $query = DbWorker::GetInstance()->prepare($str);
                $queryArgsList = array(                     
                    ':user_id' => $args['user_id'], 
                    ':preview_img' => $args['preview_img'], 
                    ':date' => date('Y-m-d H:i:s')
                ); 
                
                if($query->execute($queryArgsList))
                {
                    $queryResponseData = array('err_code' => '200','data' => $query->lastInsertId());
                }
                else
                {
                    $queryResponseData = array('err_code' => '401');
                }        
            }
            else
            {                
                $queryResponseData = array('err_code' => '602');
            }
            
            return $queryResponseData;
        });
    }
    
    public function SetPutFunctions()
    {
        
    }
    
    public function SetDeleteFunctions()
    {
    }
}
?>