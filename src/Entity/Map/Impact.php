<?php

declare(strict_types=1);

namespace App\Entity\Map;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Contract\DictionaryItemInterface;
use App\Repository\Map\ImpactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ImpactRepository::class)]
#[ORM\Table(
    name: 'map_impacts'
)]
#[ApiResource(routePrefix: '/map')]
#[Get(normalizationContext: ['groups' => self::GROUP_READ_ITEM])]
#[GetCollection(normalizationContext: ['groups' => self::GROUP_READ_COLLECTION])]
final class Impact implements DictionaryItemInterface
{
    /**
     * Render group for item.
     */
    public const GROUP_READ_ITEM = 'read:map:impact:item';

    /**
     * Render group for collection.
     */
    public const GROUP_READ_COLLECTION = 'read:map:impact:collection';

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
     * @var string|null
     */
    #[ORM\Column(length: 40)]
    #[Groups([
        self::GROUP_READ_ITEM,
        self::GROUP_READ_COLLECTION,
    ])]
    private ?string $name = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 40)]
    #[Groups([
        self::GROUP_READ_ITEM,
        self::GROUP_READ_COLLECTION,
    ])]
    private ?string $slug = null;

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
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return self
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
