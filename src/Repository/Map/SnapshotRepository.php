<?php

declare(strict_types=1);

namespace App\Repository\Map;

use App\Entity\Map\Snapshot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Snapshot>
 *
 * @method Snapshot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Snapshot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Snapshot[]    findAll()
 * @method Snapshot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class SnapshotRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Snapshot::class);
    }

    /**
     * @param Snapshot $entity
     * @param bool     $flush
     *
     * @return void
     */
    public function save(Snapshot $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param Snapshot $entity
     * @param bool     $flush
     *
     * @return void
     */
    public function remove(Snapshot $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
