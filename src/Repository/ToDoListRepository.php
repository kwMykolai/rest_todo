<?php

namespace App\Repository;

use App\Entity\ListEntry;
use App\Entity\ToDoList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ToDoList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToDoList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToDoList[]    findAll()
 * @method ToDoList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToDoListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToDoList::class);
    }

    /**
     * @param string $query
     * @param int|null $max_results
     * @return ToDoList[] Returns an array of ToDoList objects
     */
    public function searchAny(string $query, ?int $max_results = null)
    {
        $words = explode(" ", trim($query));
        $query_builder = $this->createQueryBuilder('l')
            ->select("l")
            ->leftJoin("l.entries", "e", "ON")
        ;
        for($i = 0; $i < count($words); $i++) {
            $word = $words[$i];
            $query_builder->orWhere("l.title LIKE :title_{$i}")
                ->orWhere("e.title LIKE :title_{$i}")
                ->orWhere("e.comment LIKE :title_{$i}")
                ->setParameter(":title_{$i}", "%{$word}%");
        }

        $query_builder->orderBy('l.id', 'ASC')
            ->groupBy("l.id")
            ->setMaxResults($max_results);

        return $query_builder->getQuery()
            ->getResult();
    }
}
