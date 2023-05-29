<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function save(Task $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Task $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllByFilterField($user_id, $completed, $title, $details, $deadline): ?array
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->andWhere('t.user = :user')
            ->setParameter('user', $user_id);
        if (!empty($title)) {
            $queryBuilder->andWhere('t.title = :title')
                ->setParameter('title', $title);
        }
        if (!empty($details)) {
            $queryBuilder->andWhere('t.details LIKE :details')
                ->setParameter('details','%'.$details.'%');
        }

        if (!empty($deadline)) {
            $queryBuilder->andWhere('t.deadline = :deadline')
                ->setParameter('deadline', $deadline);
        }

        if ($completed !== null) {
            $queryBuilder->andWhere('t.completed = :completed')
                ->setParameter('completed', $completed);
        }

        return $queryBuilder
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
