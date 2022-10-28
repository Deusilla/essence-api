<?php

declare(strict_types=1);

namespace App\Repository\Map;

use App\Entity\Map\World;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<World>
 *
 * @method World|null find($id, $lockMode = null, $lockVersion = null)
 * @method World|null findOneBy(array $criteria, array $orderBy = null)
 * @method World[]    findAll()
 * @method World[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class WorldRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, World::class);
    }

    /**
     * @param World $entity
     * @param bool  $flush
     *
     * @return void
     */
    public function save(World $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param World $entity
     * @param bool  $flush
     *
     * @return void
     */
    public function remove(World $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
