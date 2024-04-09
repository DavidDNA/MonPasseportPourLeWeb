<?php

namespace App\Repository;

use App\Entity\YearGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<YearGroup>
 *
 * @method YearGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method YearGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method YearGroup[]    findAll()
 * @method YearGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class YearGroupRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, YearGroup::class);
    }

    public function save(YearGroup $entity, bool $flush = false): void {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(YearGroup $entity, bool $flush = false): void {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
