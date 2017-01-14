<?php
namespace SyedOmair\Bundle\AppBundle\Services;

use SyedOmair\Bundle\AppBundle\Entity\Category;

class CategoryService extends BaseService
{
    public function __construct($entityManager, $errorService)
    {
        parent::__construct($entityManager, $errorService);
    }

    public function getACategory($id)
    {
        $category =  $this->entityManager->getRepository('AppBundle:Category')->findOneById($id);

        $dataArray = array('category' => $this->responseArray($category));
        return $this->successResponse($dataArray);
    }

    public function getCategoriesForCatalog($catalog_id, $page, $limit,  $orderby, $sort)
    {
        $categories = $this->entityManager->getRepository('AppBundle:Category')->findCategoriesForCatalog($catalog_id, $page, $limit, $orderby, $sort);
        $categoriesCount = $this->entityManager->getRepository('AppBundle:Category')->findCategoriesForCatalogCount($catalog_id);
        
        $rtnCategories = array();
        $i=0;
        foreach($categories as $key=>$category)
        {
            $rtnCategories[$i] = $this-> responseArray($category);
            $i++;
        }
        return $this->successResponseList($rtnCategories, $categoriesCount['category_count'], $page, $limit);
    }

    public function create($parameters, $catalog_id)
    {
        $catalog = $this->entityManager->getRepository('AppBundle:Catalog')->findOneById($catalog_id);

        $category = new Category();
        $category->setName($parameters['name']);
        $category->setCatalog($catalog);
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        $dataArray = array('category_id' => $category->getId());
        return $this->successResponse($dataArray);
    }

    private function responseArray($category)
    {
        $responseArray = array(
            'id' => $category->getId(),
            'name' => $category->getName(),
            'catalog_name' => $category->getCatalog()->getName()
        );
    return $responseArray;
    }
}
