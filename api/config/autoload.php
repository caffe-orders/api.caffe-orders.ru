<?php 
//echo 'AUTOLOAD</br>';
function __autoload($className)
{
    $className = strtolower($className);
    if (file_exists(API_PATH . '/' . $className . '.php'))
    { 
       require_once API_PATH . '/' . $className . '.php';          
    }     
    if (file_exists(CLASSES_PATH . '/' . $className . '.php'))
    { 
       require_once CLASSES_PATH . '/' . $className . '.php';          
    } 
    if (file_exists(MODULES_PATH . '/' . $className . '.php'))
    { 
       require_once MODULES_PATH . '/' . $className . '.php';          
    }
    if (file_exists(INTERFACES_PATH . '/' . $className . '.php'))
    { 
       require_once INTERFACES_PATH . '/' . $className . '.php';          
    } 
}
?>