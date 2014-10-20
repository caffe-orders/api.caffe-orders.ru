<?php 
//echo 'AUTOLOAD</br>';
function __autoload($className)
{
    $className = strtolower($className);
    if (file_exists(API_PATH . '/' . $className . '.php'))
    { 
       require_once API_PATH . '/' . $className . '.php';          
    }     
    if (file_exists(CLASS_PATH . '/' . $className . '.php'))
    { 
       require_once CLASS_PATH . '/' . $className . '.php';          
    } 
    if (file_exists(MODULE_PATH . '/' . $className . '.php'))
    { 
       require_once MODULE_PATH . '/' . $className . '.php';          
    }
    if (file_exists(INTERFACE_PATH . '/' . $className . '.php'))
    { 
       require_once INTERFACE_PATH . '/' . $className . '.php';          
    } 
}
?>