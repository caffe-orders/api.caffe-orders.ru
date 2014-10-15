<?php
interface Module_Interface
{
    public function RunModuleFunction(string $functionName, string $functionType, $functionArgs);
}