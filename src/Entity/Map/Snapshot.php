<?php

declare(strict_types=1);

namespace App\Entity\Map;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\Map\SnapshotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SnapshotRepository::class)]
#[ORM\Table(name: 'map_snapshots')]
#[ORM\Index(fields: ['cell', 'sum', 'compound'])]
#[ORM\UniqueConstraint(fields: ['cell', 'turn', 'type'])]
#[ApiResource(routePrefix: '/map')]
#[Get(normalizationContext: ['groups' => self::GROUP_READ_ITEM])]
#[GetCollection(normalizationContext: ['groups' => self::GROUP_READ_COLLECTION])]
final class Snapshot
{
    /**
     * Render group for item.
     */
    public const GROUP_READ_ITEM = 'read:map:snapshot:item';

    /**
     * Render group for collection.
     */
    public const GROUP_READ_COLLECTION = 'read:map:snapshot:collection';

    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        self::GROUP_READ_ITEM,
        self::GROUP_READ_COLLECTION,
    ])]
    private ?int $id = null;

    /**
     * @var Cell|null
     */
    #[ORM\ManyToOne(inversedBy: 'snapshots')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        self::GROUP_READ_ITEM,
    ])]
    private ?Cell $cell = null;

    /**
     * @var SnapshotType|null
     */
    #[ORM\Column(type: 'enum_snapshot_type', length: 20)]
    #[Groups([
        self::GROUP_READ_ITEM,
        self::GROUP_READ_COLLECTION,
    ])]
    private ?SnapshotType $type = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    #[Groups([
        self::GROUP_READ_ITEM,
        self::GROUP_READ_COLLECTION,
    ])]
    private ?int $turn = null;

    /**
     * @var array<string, float>
     */
    #[ORM\Column(type: 'json', options: ['jsonb' => true])]
    #[Groups([
        self::GROUP_READ_ITEM,
    ])]
    private array $compound = [];

    /**
     * @var self|null
     */
    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'operands')]
    private ?self $sum = null;

    /**
     * @var Collection<int, Snapshot>
     */
    #[ORM\OneToMany(mappedBy: 'sum', targetEntity: self::class)]
    private Collection $operands;

    /**
     * @var array<string, float>
     */
    #[ORM\Column(type: 'json', options: ['jsonb' => true])]
    #[Groups([
        self::GROUP_READ_ITEM,
    ])]
    private array $impacts = [];

    public function __construct()
    {
        $this->operands = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Cell|null
     */
    public function getCell(): ?Cell
    {
        return $this->cell;
    }

    /**
     * @param Cell|null $cell
     *
     * @return self
     */
    public function setCell(?Cell $cell): self
    {
        $this->cell = $cell;

        return $this;
    }

    /**
     * @return SnapshotType|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param SnapshotType $type
     *
     * @return self
     */
    public function setType(SnapshotType $type): self
    {
        $this->type = $type;

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

    /**
     * @return self|null
     */
    public function getSum(): ?self
    {
        return $this->sum;
    }

    /**
     * @param Snapshot|null $sum
     *
     * @return self
     */
    public function setSum(?self $sum): self
    {
        $this->sum = $sum;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getOperands(): Collection
    {
        return $this->operands;
    }

    /**
     * @param Snapshot $operand
     *
     * @return self
     */
    public function addOperand(self $operand): self
    {
        if (!$this->operands->contains($operand)) {
            $this->operands->add($operand);
            $operand->setSum($this);
        }

        return $this;
    }

    /**
     * @param Snapshot $operand
     *
     * @return self
     */
    public function removeOperand(self $operand): self
    {
        if ($this->operands->removeElement($operand)) {
            // set the owning side to null (unless already changed)
            if ($operand->getSum() === $this) {
                $operand->setSum(null);
            }
        }

        return $this;
    }

    /**
     * @return array<string, float>
     */
    public function getImpacts(): array
    {
        return $this->impacts;
    }

    /**
     * @param array $impacts
     *
     * @return self
     */
    public function setImpacts(array $impacts): self
    {
        $this->impacts = $impacts;

        return $this;
    }
}
