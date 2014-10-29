<?php
class Caffes extends Module implements Module_Interface
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
                $query = DbWorker::GetInstance()->prepare('SELECT id, name, adress, phones, working_time, short_info, img, album FROM caffes WHERE id = :id');
                $query->execute(array(':id' => $args['id']));
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
                'offset'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args) == true)
            {
                $offset = (int)$args['offset'];
                $limit = (int)$args['limit'];
                //(id, name, address, phones, working_time, short_info, info, img, album)
                $query = DbWorker::GetInstance()->prepare('SELECT id, name, address, phones, working_time, short_info, img, album FROM caffes ORDER BY id DESC LIMIT :offset , :limit');
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
        
    }
    
    public function SetPutFunctions()
    {
        
    }
    
    public function SetDeleteFunctions()
    {
        //can be not working
        $this->delete('delete', 10, function($args)
        {
            $requiredParams = array(
                'id'
            );
           
            $queryResponseData =  array();
            if(Module::CheckFunctionArgs($requiredParams, $args))
            {
                $query = DbWorker::GetInstance()->prepare('DELETE FROM caffes WHERE id = :id');
                if($query->execute(array(':id' => $args['id'])))
                {
                    $queryResponseData = array('err_code' => '200');
                    
                    $query = DbWorker::GetInstance()->prepare('DELETE FROM orders WHERE caffe_id = :id');
                    if($query->execute(array(':id' => $args['id'])))
                    {
                        $queryResponseData = array('err_code' => '200');
                        
                        $query = DbWorker::GetInstance()->prepare('DELETE FROM rooms WHERE caffe_id = :id');
                        if($query->execute(array(':id' => $args['id'])))
                        {
                             $queryResponseData = array('err_code' => '200');
                             
                             $query = DbWorker::GetInstance()->prepare('DELETE FROM tables AS del_tables '
                                 . 'INNER JOIN rooms AS rooms_data ON rooms_data.id = del_tables.room_index WHERE rooms_data.id = :id');
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
                            $queryResponseData = array('err_code' => '603');
                        }  
                    }
                    else
                    {
                        $queryResponseData = array('err_code' => '603');
                    }
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
            
    }
}
?>