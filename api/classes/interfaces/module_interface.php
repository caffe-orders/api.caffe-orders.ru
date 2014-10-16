<?php
interface Module_Interface
{
    public function RunModuleFunction($functionType, $functionName, $functionArgs, $accessLevel);
    
    public function SetGetFunctions();
    
    public function SetPostFunctions();
    
    public function SetPutFunctions();
    
    public function SetDeleteFunctions();
}