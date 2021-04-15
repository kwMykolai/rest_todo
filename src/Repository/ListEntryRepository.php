<?php

namespace App\Repository;

use App\Entity\ListEntry;
use App\Entity\ToDoList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ListEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListEntry[]    findAll()
 * @method ListEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListEntry::class);
    }

    /**
     * @param ToDoList $list
     * @param string $query
     * @param int|null $max_results
     * @return ListEntry[] Returns an array of ToDoList objects
     */
    public function searchAnyForList(ToDoList $list, string $query, ?int $max_results = null)
    {
        $words = explode(" ", trim($query));
        $query_builder = $this->createQueryBuilder('e')
            ->join("e.toDoList", "l")
            ->where("l.id = :list")
            ->setParameter(":list", $list->getId())
        ;
        $likes = [];
        for($i = 0; $i < count($words); $i++) {
            $word = $words[$i];
            $likes[] = $query_builder->expr()->like("e.title", ":title_{$i}");
            $likes[] = $query_builder->expr()->like("e.comment", ":title_{$i}");
            $query_builder->setParameter(":title_{$i}", "%{$word}%");
        }
        if (count($likes)) {
            $query_builder->andWhere($query_builder->expr()->orX()->addMultiple($likes));
            $query_builder->select("e");
        }

        $query_builder->orderBy('e.id', 'ASC')->setMaxResults($max_results);

        return $query_builder->getQuery()->getResult();
    }

    /**
     * @param ToDoList $list
     * @return ListEntry[] Returns an array of ToDoList objects
     */
    public function findAllForList(ToDoList $list)
    {
        return $this->findBy([
            "toDoList"=>$list->getId()
        ]);
    }
}
