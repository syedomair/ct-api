<?php
namespace SyedOmair\Bundle\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository 
{
    public function findProductsForCategory($category, $page, $limit, $orderby, $sort){
        switch ($orderby)
        {
            case 'name':
                $orderby = 'product.name';
                break;
            default:
                $orderby = 'product.name';
        }

        $users = $this->createQueryBuilder('product')
            ->select('product')
            ->join('AppBundle:Category', 'cate', 'WITH', 'product.category = cate.id')
            ->where('cate.id = :category')
            ->orderBy($orderby, $sort)
            ->setParameter('category', $category)
            ->setFirstResult( $page )
            ->setMaxResults( $limit )
            ->getQuery()
            ->getResult();

        return $users;
    }

    public function findProductsForCategoryCount($category){

        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT COUNT(product.id) as product_count FROM AppBundle:Product product  
                    JOIN product.category cate
                    WHERE cate.id =:category ' )
            ->setParameter('category', $category);
    try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }    
    }

}
