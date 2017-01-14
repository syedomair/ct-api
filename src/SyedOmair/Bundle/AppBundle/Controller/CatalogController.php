<?php
namespace SyedOmair\Bundle\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

class CatalogController  extends BaseFOSRestController
{
    /**
     *  Get Catalogs 
     *             
     * @QueryParam(name="page", requirements="\d+", default="0", description="record offset.")
     * @QueryParam(name="limit", requirements="\d+", default="100", description="number of records.")
     * @QueryParam(name="orderby", requirements="[a-z]+", allowBlank=true, default="name", description="OrderBy field")
     * @QueryParam(name="sort", requirements="(asc|desc)+", allowBlank=true, default="asc", description="Sorting order")
     *             
     * @Route("/catalogs")
     * @Method("GET")
     */
    public function getCatalogsAction(ParamFetcher $paramFetcher) 
    {
        $page = $paramFetcher->get('page');
        $limit = $paramFetcher->get('limit');
        $orderby = $paramFetcher->get('orderby');
        $sort = $paramFetcher->get('sort');

        return $this->handleView($this->createView($this->get('catalog_service')->getCatalogs($page, $limit, $orderby, $sort)));
    }

    /**
     * Create a new Catalog
     *
     * @Route("/catalog")
     * @Method("POST")
     */
    public function postCatalogAction(Request $request) 
    {
        $parameters = json_decode($request->getContent(), true);
        return $this->handleView($this->createView($this->get('catalog_service')->create($parameters)));
    }

    /**
     * Retrieve information about this Catalog
     *    
     * @Route("/catalog/{catalog_id}")
     * @Method("GET")
     */
    public function getCatalogAction($catalog_id) 
    {
        return $this->handleView($this->createView($this->get('catalog_service')->getACatalog($catalog_id)));
    }
}
