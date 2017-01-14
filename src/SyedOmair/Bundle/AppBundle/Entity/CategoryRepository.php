<?php
namespace SyedOmair\Bundle\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository 
{
    public function findCategoriesForCatalog($catalog, $page, $limit, $orderby, $sort){
        switch ($orderby)
        {
            case 'name':
                $orderby = 'cate.name';
                break;
            default:
                $orderby = 'cate.name';
        }

        $users = $this->createQueryBuilder('cate')
            ->select('cate')
            ->join('AppBundle:Catalog', 'cat', 'WITH', 'cate.catalog = cat.id')
            ->where('cat.id = :catalog')
            ->orderBy($orderby, $sort)
            ->setParameter('catalog', $catalog)
            ->setFirstResult( $page )
            ->setMaxResults( $limit )
            ->getQuery()
            ->getResult();

        return $users;
    }

    public function findCategoriesForCatalogCount($catalog){

        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT COUNT(cate.id) as category_count FROM AppBundle:Category cate  
                    JOIN cate.catalog cat
                    WHERE cat.id =:catalog ' )
            ->setParameter('catalog', $catalog);
    try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }    
    }

}
