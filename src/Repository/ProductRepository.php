<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findPaginated($page = 1)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->leftJoin('p.owner', 'u')// p.owner_id = u.id
            ->addSelect('u')
            ->leftJoin('p.tags', 't')
            ->addSelect('t')
            ->leftJoin('p.loans', 'l')
            ->leftJoin('p.translations', 'tr')
            ->addSelect('tr')
            ->where('l.status = :status1')
            ->orWhere('l.status = :status2')
            ->orWhere('l.status is NULL')
            ->setParameter('status1', 'finished')
            ->setParameter('status2', 'refused')
            ->orderBy('p.id', 'DESC');
        $pager = new DoctrineORMAdapter($queryBuilder);
        $fanta = new Pagerfanta($pager);
        return $fanta->setMaxPerPage(12)->setCurrentPage($page);
    }

    public function findPaginatedByUser(User $user, $page = 1)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->leftJoin('p.owner', 'u')
            ->addSelect('u')
            ->leftJoin('p.tags', 't')
            ->addSelect('t')
            ->leftJoin('p.translations', 'tr')
            ->addSelect('tr')
            ->where('u = :user')
            ->setParameter('user', $user)// user_id = 7
            ->orderBy('p.id', 'ASC');
        $pager = new DoctrineORMAdapter($queryBuilder);
        $fanta = new Pagerfanta($pager);
        return $fanta->setMaxPerPage(12)->setCurrentPage($page);
    }

    public function findPaginatedByLoaner(User $user, $page = 1)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->leftJoin('p.tags', 't')
            ->addSelect('t')
            ->leftJoin('p.loans', 'l')
            ->addSelect('l')
            ->leftJoin('p.translations', 'tr')
            ->addSelect('tr')
            ->where('l.loaner = :user')
            ->setParameter('user', $user)
            ->orderBy('p.id', 'ASC');
        $pager = new DoctrineORMAdapter($queryBuilder);
        $fanta = new Pagerfanta($pager);
        return $fanta->setMaxPerPage(12)->setCurrentPage($page);
    }

    public function findPaginatedByTag(Tag $tag, $page = 1)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->leftJoin('p.owner', 'u')
            ->addSelect('u')
            ->leftJoin('p.tags', 't')
            ->leftJoin('p.tags', 't2')
            ->addSelect('t')
            ->leftJoin('p.translations', 'tr')
            ->addSelect('tr')
            ->where('t2 = :tag')
            ->leftJoin('p.loans', 'l')
            ->setParameter('tag', $tag)// tag_id = 7
            ->orderBy('p.id', 'DESC');

        $orGroup = $queryBuilder->expr()->orX();
        $orGroup->add($queryBuilder->expr()->eq('l.status', ':status1'));
        $orGroup->add($queryBuilder->expr()->eq('l.status', ':status2'));
        $orGroup->add($queryBuilder->expr()->isNull('l.status'));

        $queryBuilder->andWhere($orGroup)
            ->setParameter('status1', 'refused')
            ->setParameter('status2', 'finished');

        $pager = new DoctrineORMAdapter($queryBuilder);
        $fanta = new Pagerfanta($pager);
        return $fanta->setMaxPerPage(12)->setCurrentPage($page);
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('p')
      ->andWhere('p.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('p.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?Product
      {
      return $this->createQueryBuilder('p')
      ->andWhere('p.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
}

