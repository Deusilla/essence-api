<?php

declare(strict_types=1);

namespace App\Entity\Map;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\Map\WorldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WorldRepository::class)]
#[ORM\Table(name: 'map_worlds')]
#[ApiResource(routePrefix: '/map')]
#[Get(normalizationContext: ['groups' => self::GROUP_READ_ITEM])]
#[GetCollection(normalizationContext: ['groups' => self::GROUP_READ_COLLECTION])]
#[Post]
#[Put]
#[Delete]
final class World
{
    /**
     * Render group for item.
     */
    public const GROUP_READ_ITEM = 'read:map:world:item';

    /**
     * Render group for collection.
     */
    public const GROUP_READ_COLLECTION = 'read:map:world:collection';

    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        self::GROUP_READ_ITEM,
        self::GROUP_READ_COLLECTION,
        Cell::GROUP_READ_ITEM,
    ])]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 40)]
    #[Groups([
        self::GROUP_READ_ITEM,
        self::GROUP_READ_COLLECTION,
        Cell::GROUP_READ_ITEM,
    ])]
    private ?string $name = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    #[Groups([
        self::GROUP_READ_ITEM,
        self::GROUP_READ_COLLECTION,
        Cell::GROUP_READ_ITEM,
    ])]
    private ?int $width = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    #[Groups([
        self::GROUP_READ_ITEM,
        self::GROUP_READ_COLLECTION,
        Cell::GROUP_READ_ITEM,
    ])]
    private ?int $height = null;

    /**
     * @var Collection<int, Cell>
     */
    #[ORM\OneToMany(mappedBy: 'world', targetEntity: Cell::class, orphanRemoval: true)]
    #[Groups([
        self::GROUP_READ_ITEM,
    ])]
    private Collection $cells;

    /**
     * @var int|null
     */
    #[ORM\Column]
    #[Groups([
        self::GROUP_READ_ITEM,
    ])]
    private ?int $turn = null;

    public function __construct()
    {
        $this->cells = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * @param int $width
     *
     * @return self
     */
    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @param int $height
     *
     * @return self
     */
    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return Collection<int, Cell>
     */
    public function getCells(): Collection
    {
        return $this->cells;
    }

    /**
     * @param Cell $cell
     *
     * @return self
     */
    public function addCell(Cell $cell): self
    {
        if (!$this->cells->contains($cell)) {
            $this->cells->add($cell);
            $cell->setWorld($this);
        }

        return $this;
    }

    /**
     * @param Cell $cell
     *
     * @return self
     */
    public function removeCell(Cell $cell): self
    {
        if ($this->cells->removeElement($cell)) {
            // set the owning side to null (unless already changed)
            if ($cell->getWorld() === $this) {
                $cell->setWorld(null);
            }
        }

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTurn(): ?int
    {
        return $this->turn;
    }

    /**
     * @param int $turn
     *
     * @return self
     */
    public function setTurn(int $turn): self
    {
        $this->turn = $turn;

        return $this;
    }
}
