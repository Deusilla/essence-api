<?php

namespace App\Entity\Flat;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\Flat\CellRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CellRepository::class)]

#[ApiResource]
#[Get(normalizationContext: ['groups' => self::GROUP_READ_ITEM])]
#[GetCollection(normalizationContext: ['groups' => self::GROUP_READ_COLLECTION])]
class Cell
{
    /**
     * Render group for item.
     */
    public const GROUP_READ_ITEM = 'read:cell:item';

    /**
     * Render group for collection.
     */
    public const GROUP_READ_COLLECTION = 'read:cell:collection';

    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        self::GROUP_READ_ITEM,
        self::GROUP_READ_COLLECTION,
        World::GROUP_READ_ITEM,
    ])]
    private ?int $id = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    #[Groups([
        self::GROUP_READ_ITEM,
        self::GROUP_READ_COLLECTION,
        World::GROUP_READ_ITEM,
    ])]
    private ?int $x = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    #[Groups([
        self::GROUP_READ_ITEM,
        self::GROUP_READ_COLLECTION,
        World::GROUP_READ_ITEM,
    ])]
    private ?int $y = null;

    /**
     * @var World|null
     */
    #[ORM\ManyToOne(inversedBy: 'cells')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        self::GROUP_READ_ITEM,
    ])]
    private ?World $world = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getX(): ?int
    {
        return $this->x;
    }

    /**
     * @param int $x
     *
     * @return $this
     */
    public function setX(int $x): self
    {
        $this->x = $x;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getY(): ?int
    {
        return $this->y;
    }

    /**
     * @param int $y
     *
     * @return $this
     */
    public function setY(int $y): self
    {
        $this->y = $y;

        return $this;
    }

    /**
     * @return World|null
     */
    public function getWorld(): ?World
    {
        return $this->world;
    }

    /**
     * @param World|null $world
     *
     * @return $this
     */
    public function setWorld(?World $world): self
    {
        $this->world = $world;

        return $this;
    }
}
