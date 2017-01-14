<?php
namespace SyedOmair\Bundle\AppBundle\Services;

use SyedOmair\Bundle\AppBundle\Entity\User;
use SyedOmair\Bundle\AppBundle\Exception\UserServiceException;

class UserService extends BaseService
{

    public function __construct($entityManager, $errorService)
    {
        parent::__construct($entityManager, $errorService);
    }

    public function getAUser($id)
    {
        $user =  $this->entityManager->getRepository('AppBundle:User')->findOneById($id);

        $dataArray = array('user' => $this->responseArray($user));
        return $this->successResponse($dataArray);
    }

    public function create($parameters)
    {
        $email = $parameters['email'];
        $existingUser = $this->entityManager->getRepository('AppBundle:User')->findByEmail($email);

        if($existingUser)
            $this->errorService->throwException((new UserServiceException())->createUserAlreadyExists());

        $user = new User();
        $user->setEmail($email);
        $user->setFirstName($parameters['first_name']);
        $user->setLastName($parameters['last_name']);
        $user->setCreatedAt(new \DateTime());
        $user->setUpdatedAt(new \DateTime());
        $this->passwordEncryption($parameters['password'], $user);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $dataArray = array('user_id' => $user->getId());
        return $this->successResponse($dataArray);
    }

    private function passwordEncryption($password, $user){
        $salt = $this->generateUserSalt();
        $user->setPassword(crypt($password,$salt));
        $user->setSalt($salt);
    }

    private function generateUserSalt(){
        return base_convert(uniqid(mt_rand(), true), 16, 36);
    }

    private function responseArray($user)
    {
        $responseArray = array(
            'id' => $user->getId(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName()
        );
    return $responseArray;
    }

    public function apiLogin($token)
    {
        $rtnArray =  array('token' => $token);
        return $this->successResponse($rtnArray);
    }
}
