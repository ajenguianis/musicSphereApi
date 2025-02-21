<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BrandRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Put(),
        new Post(),
        new Delete()
    ]
)]
#[ORM\HasLifecycleCallbacks]
class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $originCountry = null;

    #[ORM\Column(length: 100)]
    private ?string $city = null;

    #[ORM\Column]
    private ?int $yearStart = null;

    #[ORM\Column(nullable: true)]
    private ?int $yearEnd = null;

    #[ORM\ManyToOne(inversedBy: 'brands')]
    private ?MusicGenre $musicGenre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, BrandMember>
     */
    #[ORM\OneToMany(targetEntity: BrandMember::class, mappedBy: 'brand')]
    private Collection $brandMembers;

    /**
     * @var Collection<int, Concert>
     */
    #[ORM\ManyToMany(targetEntity: Concert::class, mappedBy: 'brands')]
    private Collection $concerts;

    public function __construct()
    {
        $this->brandMembers = new ArrayCollection();
        $this->concerts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getOriginCountry(): ?string
    {
        return $this->originCountry;
    }

    public function setOriginCountry(string $originCountry): static
    {
        $this->originCountry = $originCountry;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getYearStart(): ?int
    {
        return $this->yearStart;
    }

    public function setYearStart(int $yearStart): static
    {
        $this->yearStart = $yearStart;

        return $this;
    }

    public function getYearEnd(): ?int
    {
        return $this->yearEnd;
    }

    public function setYearEnd(?int $yearEnd): static
    {
        $this->yearEnd = $yearEnd;

        return $this;
    }

    public function getMusicGenre(): ?MusicGenre
    {
        return $this->musicGenre;
    }

    public function setMusicGenre(?MusicGenre $musicGenre): static
    {
        $this->musicGenre = $musicGenre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }


    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, BrandMember>
     */
    public function getBrandMembers(): Collection
    {
        return $this->brandMembers;
    }

    public function addBrandMember(BrandMember $brandMember): static
    {
        if (!$this->brandMembers->contains($brandMember)) {
            $this->brandMembers->add($brandMember);
            $brandMember->setBrand($this);
        }

        return $this;
    }

    public function removeBrandMember(BrandMember $brandMember): static
    {
        if ($this->brandMembers->removeElement($brandMember)) {
            // set the owning side to null (unless already changed)
            if ($brandMember->getBrand() === $this) {
                $brandMember->setBrand(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Concert>
     */
    public function getConcerts(): Collection
    {
        return $this->concerts;
    }

    public function addConcert(Concert $concert): static
    {
        if (!$this->concerts->contains($concert)) {
            $this->concerts->add($concert);
            $concert->addBrand($this);
        }

        return $this;
    }

    public function removeConcert(Concert $concert): static
    {
        if ($this->concerts->removeElement($concert)) {
            $concert->removeBrand($this);
        }

        return $this;
    }
    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTimeImmutable();
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    // PreUpdate to update the updatedAt field on entity updates
    #[ORM\PreUpdate]
    public function updateTimestamp(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
    public function getMusicGenreAsArray(): array
    {
        return [
            'id' => $this->musicGenre?->getId(),
            'name' => $this->musicGenre?->getName(),
        ];
    }
}
