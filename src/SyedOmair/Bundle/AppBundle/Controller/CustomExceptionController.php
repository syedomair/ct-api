<?php
namespace SyedOmair\Bundle\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class CustomExceptionController  implements ContainerAwareInterface  
{
    use ContainerAwareTrait;

    public function showAction(Request $request, $exception, DebugLoggerInterface $logger = null) 
    {
        $view = new View();
        $view->setData(json_decode($exception->getMessage(), true));
        $view->setFormat('json');
        return $this->container->get('fos_rest.view_handler')->handle($view);
    }
}
