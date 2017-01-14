<?php
namespace SyedOmair\Bundle\AppBundle\Services;

use SyedOmair\Bundle\AppBundle\Entity\Product;
use SyedOmair\Bundle\AppBundle\Exception\ProductServiceException;

class ProductService extends BaseService
{

    public function __construct($entityManager, $errorService)
    {
        parent::__construct($entityManager, $errorService);
    }

    public function getAProduct($id)
    {
        if(!is_numeric($id) )
            $this->errorService->throwException((new ProductServiceException())->getProductsInvalidParameterId());

        $product =  $this->entityManager->getRepository('AppBundle:Product')->findOneById($id);

        $dataArray = array('product' => $this->responseArray($product));
        return $this->successResponse($dataArray);
    }

    public function getProductsForCategory($category_id, $page, $limit,  $orderby, $sort)
    {
        $products = $this->entityManager->getRepository('AppBundle:Product')->findProductsForCategory($category_id, $page, $limit, $orderby, $sort);
        $productsCount = $this->entityManager->getRepository('AppBundle:Product')->findProductsForCategoryCount($category_id);
        
        $rtnProducts = array();
        $i=0;
        foreach($products as $key=>$product)
        {
            $rtnProducts[$i] = $this-> responseArray($product);
            $i++;
        }
        return $this->successResponseList($rtnProducts, $productsCount['product_count'], $page, $limit);
    }

    public function create($parameters, $category_id)
    {
        $category = $this->entityManager->getRepository('AppBundle:Category')->findOneById($category_id);

        $product = new Product();
        $product->setName($parameters['name']);
        $product->setSku($parameters['sku']);
        $product->setPrice($parameters['price']);
        $product->setShortDescription($parameters['short_desc']);
        $product->setImage($parameters['image']);
        $product->setCategory($category);
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $dataArray = array('product_id' => $product->getId());
        return $this->successResponse($dataArray);
    }

    private function responseArray($product)
    {
        $responseArray = array(
            'id' => $product->getId(),
            'catalog_name' => $product->getCategory()->getCatalog()->getName(),
            'category_name' => $product->getCategory()->getName(),
            'name' => $product->getName(),
            'sku' => $product->getSku(),
            'price' => $product->getPrice(),
            'short_desc' => $product->getShortDescription(),
            'image' => $product->getImage(),
        );
    return $responseArray;
    }
}
