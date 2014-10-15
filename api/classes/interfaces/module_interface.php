<?php
interface Module_Interface
{
    public function RunModuleFunction($functionType, $functionName, $functionArgs, $accessLevel);
}