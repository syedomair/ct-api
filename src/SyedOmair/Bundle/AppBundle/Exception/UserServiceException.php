<?php
namespace SyedOmair\Bundle\AppBundle\Exception;

class UserServiceException  extends CustomException
{
    public function createUserAlreadyExists()
    {
        $this->errorMessage = 'User already exists';
        $this->errorCode = 30001;
        return $this;
    }
}
