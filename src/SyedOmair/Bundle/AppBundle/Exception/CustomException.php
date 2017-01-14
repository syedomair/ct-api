<?php
namespace SyedOmair\Bundle\AppBundle\Exception;

class CustomException extends \Exception
{
    protected $errorMessage;
    protected $errorCode;

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function getErrorCode() 
    {
        return $this->errorCode;
    }
}
