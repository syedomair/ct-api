<?php
namespace SyedOmair\Bundle\AppBundle\Exception;

class ProductServiceException  extends CustomException
{
    public function getProductsInvalidParameterId()
    {
        $this->errorMessage = 'Parameter missing: id ';
        $this->errorCode = 20001;
        return $this;
    }
}
