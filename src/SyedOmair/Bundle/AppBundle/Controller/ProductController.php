<?php
namespace SyedOmair\Bundle\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

class ProductController  extends BaseFOSRestController
{
    /**
     *  Get Products 
     *             
     * @QueryParam(name="page", requirements="\d+", default="0", description="record offset.")
     * @QueryParam(name="limit", requirements="\d+", default="100", description="number of records.")
     * @QueryParam(name="orderby", requirements="[a-z]+", allowBlank=true, default="name", description="OrderBy field")
     * @QueryParam(name="sort", requirements="(asc|desc)+", allowBlank=true, default="asc", description="Sorting order")
     *             
     * @Route("/products/{category_id}")
     * @Method("GET")
     */
    public function getProductsAction($category_id, ParamFetcher $paramFetcher) 
    {
        $page = $paramFetcher->get('page');
        $limit = $paramFetcher->get('limit');
        $orderby = $paramFetcher->get('orderby');
        $sort = $paramFetcher->get('sort');

        return $this->handleView($this->createView($this->get('product_service')->getProductsForCategory($category_id, $page, $limit, $orderby, $sort)));
    }

    /**
     * Create a new Product
     *
     * @Route("/product/{category_id}")
     * @Method("POST")
     */
    public function postProductAction(Request $request, $category_id) 
    {
        $parameters = json_decode($request->getContent(), true);
        return $this->handleView($this->createView($this->get('product_service')->create($parameters, $category_id)));
    }

    /**
     * Retrieve information about this Product
     *    
     * @Route("/product/{product_id}")
     * @Method("GET")
     */
    public function getProductAction($product_id) 
    {
        return $this->handleView($this->createView($this->get('product_service')->getAProduct($product_id)));
    }
}
