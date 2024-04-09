<?php

namespace App\Repository;

use App\Entity\SessionActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SessionActivity>
 *
 * @method SessionActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method SessionActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method SessionActivity[]    findAll()
 * @method SessionActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionActivityRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, SessionActivity::class);
    }

    public function save(SessionActivity $entity, bool $flush = false): void {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SessionActivity $entity, bool $flush = false): void {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
