<?php

namespace App\Repository;

use App\Entity\ClassroomHasSessions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClassroomHasSessions>
 *
 * @method ClassroomHasSessions|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClassroomHasSessions|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClassroomHasSessions[]    findAll()
 * @method ClassroomHasSessions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassroomHasSessionsRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, ClassroomHasSessions::class);
    }

    public function save(ClassroomHasSessions $entity, bool $flush = false): void {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ClassroomHasSessions $entity, bool $flush = false): void {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
