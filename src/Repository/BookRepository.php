<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
    public function findBooksTitle($value): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.title = :t')
            ->setParameter('t', $value)
            ->orderBy('b.ref', 'ASC')
            ->setMaxResults(10)
            ->getQuery() //obligatoire
            ->getResult() //obligatoire
        ;
    }
    public function findBooksAuthor($value): array
    {
        return $this->createQueryBuilder('b')
        ->join('b.author','author')
        ->addSelect('author')
        ->andWhere('author.username = :name')
        ->setParameter('name', $value)
        ->getQuery() //obligatoire
        ->getResult() //obligatoire
        ;
    }


//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
