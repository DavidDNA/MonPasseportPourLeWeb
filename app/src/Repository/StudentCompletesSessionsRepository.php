<?php

namespace App\Repository;

use App\Entity\StudentCompletesSessions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StudentCompletesSessions>
 *
 * @method StudentCompletesSessions|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentCompletesSessions|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentCompletesSessions[]    findAll()
 * @method StudentCompletesSessions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentCompletesSessionsRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, StudentCompletesSessions::class);
    }

    public function save(StudentCompletesSessions $entity, bool $flush = false): void {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(StudentCompletesSessions $entity, bool $flush = false): void {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
