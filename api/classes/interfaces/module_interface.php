<?php
interface Module_Interface
{
    public function RunModuleFunction($functionType, $functionName, $functionArgs, $accessLevel);
    
    public function setGetFunction();
    
    public function setPostFunction();
    
    public function setPutFunction();
    
    public function setDeleteFunction();
}