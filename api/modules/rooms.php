<?php
class Rooms extends Module implements Module_Interface
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
            if($args['caffe_id']!=0)
            {
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM rooms WHERE caffe_id = :caffe_id');                
                $query->bindParam(':caffe_id', $args['caffe_id'], PDO::PARAM_INT); 
                $query->execute();
                $queryResponseData = array('err_code' => '200', 'data' => $query->fetchAll());
            }            
            else if($args['id']!=0)
            {
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM rooms WHERE id = :id');
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
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM rooms ORDER BY id DESC LIMIT :offset , :limit');
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
        
        //create new room PUT responce type
        $this->get('new', 1, function($args)
        {
            $parametersArray = array(
                'caffe_id',
                'name',
                'background_img',
                'xLength',
                'yLength'
            ); 
            
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $query = DbWorker::GetInstance()->prepare('SELECT id FROM caffes WHERE id = :caffe_id');
                $query->execute(array(':caffe_id' => $args['caffe_id']));
                $result = $query->fetch();
                if($result)
                {
                    $query = 'INSERT rooms (caffe_id, name, background_img, xLength, yLength) 
                                VALUES (:caffe_id, :name, :background_img, :xLength, :yLength)'; 
                    $sth = DbWorker::GetInstance();
                    $query = $sth->prepare($query);
                    
                    $queryArgsList = array(
                        ':caffe_id' => $args['caffe_id'],
                        ':name' => $args['name'], 
                        ':background_img' => $args['background_img'],
                        ':xLength' => $args['xLength'], 
                        ':yLength' => $args['yLength']
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
                    $queryResponseData = array('err_code' => '400', 'data' => 'caffe noy found');
                }
            }
            else
            {                
                $queryResponseData = array('err_code' => '602');
            }
            
            return $queryResponseData;
        });
        
        $this->get('edit', 0, function($args)
        {
            $parametersArray = array(
                'id',
                'caffe_id',
                'name',
                'background_img',
                'xLength',
                'yLength'
            ); 
            
            $queryResponseData = array();
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $str = 'UPDATE rooms SET caffe_id = :caffe_id, name = :name, background_img = :background_img, xLength = :xLength, yLength = :yLength WHERE id = :id';
                    
                $query = DbWorker::GetInstance()->prepare($str);
                $queryArgsList = array(
                    ':id' => $args['id'], 
                    ':name' => $args['name'], 
                    ':caffe_id' => $args['caffe_id'], 
                    ':background_img' => $args['background_img'], 
                    ':xLength' => $args['xLength'], 
                    ':yLength' => $args['yLength']
                ); 
                
                if($query->execute($queryArgsList))
                {
                    $queryResponseData = array('err_code' => '200');
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
        
        $this->get('editimg', 0, function($args)
        {
            $parametersArray = array(
                'id',
                'room_img'
            ); 
            
            $queryResponseData = array();
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $str = 'UPDATE rooms SET  background_img = :room_img WHERE id =:id';
                    
                $query = DbWorker::GetInstance()->prepare($str);
                $queryArgsList = array(
                    ':id' => $args['id'], 
                    ':room_img' => $args['room_img']
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
        
        $this->get('delete', 1, function($args)
        {
            $requiredParams = array(
                'id'
            );
           
            $queryResponseData =  array();
            if(Module::CheckFunctionArgs($requiredParams, $args))
            {
                $query = DbWorker::GetInstance()->prepare('DELETE FROM rooms WHERE id = :id');
                
                if($query->execute(array(':id' => $args['id'])))
                {
                    $query = DbWorker::GetInstance()->prepare('DELETE FROM tables WHERE room_id = :id');
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

