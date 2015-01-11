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
    //returns info about selected caffe
        $this->get('info', 0, function($args)
        {
            $parametersArray = array(
                'id'
            ); 

            $queryResponseData = array();
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM caffes WHERE id = :id');
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

                $query = DbWorker::GetInstance()->prepare('SELECT id, name, address, telephones, working_time, short_info, info, wifi, type, average_check, rating, number_voters, sum_votes, preview_img, album_name FROM caffes ORDER BY id DESC LIMIT :offset , :limit');
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
                'name', 
                'address', 
                'telephones', 
                'working_time', 
                'short_info', 
                'info', 
                'wifi', 
                'type', 
                'average_check',
                'number_voters', 
                'sum_votes', 
                'preview_img', 
                'album_name'
            ); 
            
            $queryResponseData = array();
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $str = 'INSERT caffes (name, address, telephones, working_time, short_info, info, wifi, type, average_check, rating, number_voters, sum_votes, preview_img, album_name)'
                        . ' VALUES (:name, :address, :telephones, :working_time, :short_info, :info, :wifi, :type, :average_check, :rating, :number_voters, :sum_votes, :preview_img, :album_name)';
                    
                $query = DbWorker::GetInstance()->prepare($str);
                $queryArgsList = array(
                    ':name' => $args['name'], 
                    ':address' => $args['address'], 
                    ':telephones' => $args['telephones'], 
                    ':working_time' => $args['working_time'], 
                    ':short_info' => $args['short_info'], 
                    ':info' => $args['info'], 
                    ':wifi' => $args['wifi'], 
                    ':type' => $args['type'], 
                    ':average_check' => $args['average_check'],
                    ':rating' => 0, 
                    ':number_voters' => $args['number_voters'], 
                    ':sum_votes' => $args['sum_votes'], 
                    ':preview_img' => $args['preview_img'], 
                    ':album_name' => $args['album_name']
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
        
        $this->get('edit', 0, function($args)
        {
            $parametersArray = array(
                'id',
                'name', 
                'address', 
                'telephones', 
                'working_time', 
                'short_info', 
                'info', 
                'wifi', 
                'type', 
                'average_check',
                'number_voters', 
                'sum_votes', 
                'preview_img', 
                'album_name'
            ); 
            
            $queryResponseData = array();
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $str = 'UPDATE caffes SET name = :name, address = :address, telephones = :telephones, working_time = :working_time, short_info = :short_info, info = :info, wifi = :wifi, type =:type, average_check = :average_check, rating = :rating, number_voters = :number_voters, sum_votes = :sum_votes, preview_img = :preview_img, album_name = :album_name WHERE id =:id';
                    
                $query = DbWorker::GetInstance()->prepare($str);
                $queryArgsList = array(
                    ':id' => $args['id'], 
                    ':name' => $args['name'], 
                    ':address' => $args['address'], 
                    ':telephones' => $args['telephones'], 
                    ':working_time' => $args['working_time'], 
                    ':short_info' => $args['short_info'], 
                    ':info' => $args['info'], 
                    ':wifi' => $args['wifi'], 
                    ':type' => $args['type'], 
                    ':average_check' => $args['average_check'],
                    ':rating' => 0, 
                    ':number_voters' => $args['number_voters'], 
                    ':sum_votes' => $args['sum_votes'], 
                    ':preview_img' => $args['preview_img'], 
                    ':album_name' => $args['album_name']
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
        
        $this->get('editpreview', 0, function($args)
        {
            $parametersArray = array(
                'id',
                'preview_img'
            ); 
            
            $queryResponseData = array();
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $str = 'UPDATE caffes SET  preview_img = :preview_img WHERE id =:id';
                    
                $query = DbWorker::GetInstance()->prepare($str);
                $queryArgsList = array(
                    ':id' => $args['id'], 
                    ':preview_img' => $args['preview_img']
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
        
        $this->get('addimgalbum', 0, function($args)
        {
            $parametersArray = array(
                'id',
                'album_img'
            ); 
            
            $queryResponseData = array();
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM caffes WHERE id = :id');
                $arrayList = array(
                    ':id' => $args['id']
                        ); 
                $query->execute($arrayList);
                $result = $query->fetch();
                if($result)
                {
                    $imgs = $result['album_imgs'].$args['album_img'].'/';
                    $str = 'UPDATE caffes SET  album_imgs = :album_imgs WHERE id =:id';
                    
                    $query = DbWorker::GetInstance()->prepare($str);
                    $queryArgsList = array(
                        ':id' => $args['id'], 
                        ':album_imgs' => $imgs
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
                    $queryResponseData = array('err_code' => '400');
                }
            }
            else
            {                
                $queryResponseData = array('err_code' => '602');
            }
            
            return $queryResponseData;
        });
        
        $this->get('rate', 0, function($args)
        {
            $parametersArray = array(
                'id',
                'value',
                'userId'
            ); 
            
            $queryResponseData = array();
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {                
                $query = DbWorker::GetInstance()->prepare('SELECT * FROM rated WHERE user_id = :user_id AND record_id = :record_id AND record_type = :record_type ');
                $arrayList = array(
                    ':user_id' => $args['userId'],  
                    ':record_id' => $args['id'] ,
                    ':record_type' => 1
                        ); 
                $query->execute($arrayList);
                $result = $query->fetch();
                
                if($result == false)
                {
                    $query = DbWorker::GetInstance()->prepare('SELECT id, rating, number_voters, sum_votes FROM caffes WHERE id = :id');
                    if($query->execute(array(':id' => $args['id'])))
                    {
                        $data = $query->fetch();
                        $numberVoters = $data['number_voters'] + 1;
                        if($args['value'] > 5 || $args['value'] < 0)
                        {
                            $args['value'] = 5;
                        }
                        $summVotes = $data['number_voters'] + $args['value'];
                        $newRating = round(abs($summVotes / $numberVoters), 1);
                    
                        $str = 'UPDATE caffes SET  rating = :rating, number_voters = :number_voters, sum_votes = :sum_votes WHERE id =:id';
                    
                        $query = DbWorker::GetInstance()->prepare($str);
                        $queryArgsList = array(
                            ':id' => $args['id'],
                            ':rating' => $newRating, 
                            ':number_voters' => $numberVoters, 
                            ':sum_votes' => $summVotes
                        ); 
                
                        if($query->execute($queryArgsList))
                        {
                           $queryResponseData = array('err_code' => '200','data' => 'true');
                        }
                        else
                        {
                            $queryResponseData = array('err_code' => '401');
                        }
                    }   
                    else
                    {
                        $queryResponseData = array('err_code' => '400');
                    }
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
        
        $this->get('delete', 0, function($args)
        {
            $parametersArray = array(
                'id'
            ); 
            
            $queryResponseData = array();
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                  
                $query = DbWorker::GetInstance()->prepare('DELETE FROM caffes WHERE id = :id');
                $queryArgsList = array(
                    ':id' => $args['id']
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
    }
    
    public function SetPutFunctions()
    {
        
    }
    
    public function SetDeleteFunctions()
    {
        //can be not working
/*        $this->get('delete', 0, function($args)
        {
            $parametersArray = array(
                'id'
            );
           
            $queryResponseData = array();
            if(Module::CheckFunctionArgs($parametersArray, $args))
            {
                $caffeQuery = DbWorker::GetInstance->prepare('SELECT id FROM caffes WHERE id = :id');
                $caffeQuery->execute(array(':id' => $args['id']))
                    
                if($caffeQuery->fetch());
                {
                    $deleteCaffeQuery = DbWorker::GetInstance->prepare('DELETE FROM caffes WHERE id = :id');
                    $deleteCaffeQuery->execute(array(':id' => $args['id']));
                    
                    $deleteRoomsQuery = DbWorker::GetInstance->prepare('SELECT DISTINCT id FROM rooms WHERE caffe_id = :id');
                    
                    if($deleteRoomsQuery->execute(array(':id' => $args['id'])))
                    {
                        while($data = $deleteRoomsQuery->fetch())
                        {
                            $functionArgs = array('id' => $data['id']);
                            Module::RunOtherModuleFunction('Rooms', 'delete', 'get', $this->_accessLevel, $functionArgs);
                        }
                        $queryResponseData = array('err_code' => '200');
                    }
                    else
                    {
                        $queryResponseData = array('err_code' => '604');
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
        });*/
    }
}
?>