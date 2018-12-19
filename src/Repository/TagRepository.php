<?php

namespace App\Repository;

use App\Entity\Tag;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tag::class);
    }
    
    /**
     * 
     * @param string $tagName the name of the tag we are looking for
     * @return Tag the corresponding tag in db or a new Tag instance if no 
     * corresponding tag is found
     */
    public function getCorrespondingTag($tagName)
    {
        $tagName = trim($tagName);
        $slugify = new Slugify();
        $tagSlug = $slugify->slugify($tagName);
        // SELECT t.* FROM tag as t WHERE slug = :tagSlug LIMIT 1
        $tag = $this->findOneBy(['slug' => $tagSlug]);
        
        if( ! $tag) {
            $tag = new Tag();
            $tag->setName($tagName);
            $tag->setSlug($tagSlug);
        }
        return $tag;
    }
    
    public function searchBySlug($slug)
    {
        return $this->createQueryBuilder('t')
                ->where('t.slug LIKE :slug')
                ->setParameter('slug', '%'.$slug.'%')
                ->getQuery()->getResult();
    }

//    /**
//     * @return Tag[] Returns an array of Tag objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tag
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
