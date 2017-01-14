<?php
namespace SyedOmair\Bundle\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

class CategoryController  extends BaseFOSRestController
{
    /**
     *  Get Categories 
     *             
     * @QueryParam(name="page", requirements="\d+", default="0", description="record offset.")
     * @QueryParam(name="limit", requirements="\d+", default="100", description="number of records.")
     * @QueryParam(name="orderby", requirements="[a-z]+", allowBlank=true, default="name", description="OrderBy field")
     * @QueryParam(name="sort", requirements="(asc|desc)+", allowBlank=true, default="asc", description="Sorting order")
     *             
     * @Route("/categories/{catalog_id}")
     * @Method("GET")
     */
    public function getCategoriesAction($catalog_id, ParamFetcher $paramFetcher) 
    {
        $page = $paramFetcher->get('page');
        $limit = $paramFetcher->get('limit');
        $orderby = $paramFetcher->get('orderby');
        $sort = $paramFetcher->get('sort');

        return $this->handleView($this->createView($this->get('category_service')->getCategoriesForCatalog($catalog_id, $page, $limit, $orderby, $sort)));
    }

    /**
     * Create a new Category
     *
     * @Route("/category/{catalog_id}")
     * @Method("POST")
     */
    public function postCategoryAction(Request $request, $catalog_id) 
    {
        $parameters = json_decode($request->getContent(), true);
        return $this->handleView($this->createView($this->get('category_service')->create($parameters, $catalog_id)));
    }

    /**
     * Retrieve information about this Category
     *    
     * @Route("/category/{category_id}")
     * @Method("GET")
     */
    public function getCategoryAction($category_id) 
    {
        return $this->handleView($this->createView($this->get('category_service')->getACategory($category_id)));
    }
}
