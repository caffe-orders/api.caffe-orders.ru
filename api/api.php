<?php
class Api
{
    private $_requestType;
    private $_requestArgs;
    private $_requestUrl;
    public function __construct()
    {
        $this->_requestType = $_SERVER['REQUEST_METHOD'];
        $this->_requestArgs = $_REQUEST;
        $this->_requestUrl = $_SERVER['REQUEST_URI'];
        echo $this->_requestType . '<br>';
        print_r($this->_requestArgs);
        echo $this->_requestUrl . '<br>';
    }
}
?>