<?php

declare(strict_types=1);

namespace App\Entity\Map;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\Map\CellRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CellRepository::class)]
#[ORM\Table(name: 'map_cells')]
#[ApiResource(routePrefix: '/map')]
#[Get(normalizationContext: ['groups' => self::GROUP_READ_ITEM])]
#[GetCollection(normalizationContext: ['groups' => self::GROUP_READ_COLLECTION])]
final class Cell
{
    /**
     * Render group for item.
     */
    public const GROUP_READ_ITEM = 'read:map:cell:item';

    /**
     * Render group for collection.
     */
    public const GROUP_READ_COLLECTION = 'read:map:cell:collection';

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
     * @var Collection<int, Snapshot>
     */
    #[ORM\OneToMany(mappedBy: 'cell', targetEntity: Snapshot::class, orphanRemoval: true)]
    private Collection $snapshots;

    /**
     * @var array<string, float>
     */
    #[ORM\Column(type: 'json', options: ['jsonb' => true])]
    #[Groups([
        self::GROUP_READ_ITEM,
    ])]
    private array $compound = [];

    public function __construct()
    {
        $this->snapshots = new ArrayCollection();
    }

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
     * @return self
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
     * @return self
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
     * @return self
     */
    public function setWorld(?World $world): self
    {
        $this->world = $world;

        return $this;
    }

    /**
     * @return Collection<int, Snapshot>
     */
    public function getSnapshots(): Collection
    {
        return $this->snapshots;
    }

    /**
     * @param Snapshot $snapshot
     *
     * @return self
     */
    public function addSnapshot(Snapshot $snapshot): self
    {
        if (!$this->snapshots->contains($snapshot)) {
            $this->snapshots->add($snapshot);
            $snapshot->setCell($this);
        }

        return $this;
    }

    /**
     * @param Snapshot $snapshot
     *
     * @return self
     */
    public function removeSnapshot(Snapshot $snapshot): self
    {
        if ($this->snapshots->removeElement($snapshot)) {
            // set the owning side to null (unless already changed)
            if ($snapshot->getCell() === $this) {
                $snapshot->setCell(null);
            }
        }

        return $this;
    }

    /**
     * @return array<string, float>
     */
    public function getCompound(): array
    {
        return $this->compound;
    }

    /**
     * @param array<string, float> $compound
     *
     * @return self
     */
    public function setCompound(array $compound): self
    {
        $this->compound = $compound;

        return $this;
    }
}
