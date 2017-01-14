<?php
namespace SyedOmair\Bundle\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;

class UserController  extends BaseFOSRestController
{
    /**
     * @Rest\Get("secure/api-login")
     */
    public function getApiLoginAction(Request $request) 
    {
        $token = $request->headers->get('custom-auth');
        return $this->handleView($this->createView($this->get('user_service')->apiLogin($token)));
    }

    /**
     * Create a new User
     *
     * @Rest\Post("secure/user")
     */
    public function postUserAction(Request $request) 
    {
        $parameters = json_decode($request->getContent(), true);
        return $this->handleView($this->createView($this->get('user_service')->create($parameters)));
    }

    /**
     * Retrieve information about the User 
     *    
     * @Rest\Get("/user/{user_id}")
     */
    public function getUserAction($user_id) 
    {
        return $this->handleView($this->createView($this->get('user_service')->getAUser($user_id)));
    }
}
