<?php

namespace App\Repository;

use App\Entity\AvatarUpgrade;
use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AvatarUpgrade>
 *
 * @method AvatarUpgrade|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvatarUpgrade|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvatarUpgrade[]    findAll()
 * @method AvatarUpgrade[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvatarUpgradeRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, AvatarUpgrade::class);
    }

    public function save(AvatarUpgrade $entity, bool $flush = false): void {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AvatarUpgrade $entity, bool $flush = false): void {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAvailableUpgrades(Student $student): array {
        $qb = $this->createQueryBuilder('u')
            ->where('u.relativeThreshold <= :studentThreshold')
            ->setParameter('studentThreshold', $student->getRelativeProgression())
            ->orderBy('u.relativeThreshold', 'ASC');


        $upgrades = $student->getUpgradesNames();
        if (count($upgrades) > 0) {
            $qb->andWhere('u.name NOT IN (:studentUpgrades)')
                ->setParameter('studentUpgrades', $upgrades);
        }

        return $qb->getQuery()
            ->getResult();
    }
}
