<?php

namespace App\Repository;

use App\Entity\AvatarHasUpgrades;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AvatarHasUpgrades>
 *
 * @method AvatarHasUpgrades|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvatarHasUpgrades|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvatarHasUpgrades[]    findAll()
 * @method AvatarHasUpgrades[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvatarHasUpgradesRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, AvatarHasUpgrades::class);
    }

    public function save(AvatarHasUpgrades $entity, bool $flush = false): void {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AvatarHasUpgrades $entity, bool $flush = false): void {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
