<?php

namespace App\Entity;

use App\Repository\MusicGenreRepository;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MusicGenreRepository::class)]
#[ApiResource]
class MusicGenre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private string $name;

    /**
     * @var Collection<int, Brand>
     */
    #[ORM\OneToMany(targetEntity: Brand::class, mappedBy: 'musicGenre')]
    private Collection $brands;

    public function __construct()
    {
        $this->brands = new ArrayCollection();
    } // Non-nullable without default initialization to null

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Brand>
     */
    public function getBrands(): Collection
    {
        return $this->brands;
    }

    public function addBrand(Brand $brand): static
    {
        if (!$this->brands->contains($brand)) {
            $this->brands->add($brand);
            $brand->setMusicGenre($this);
        }

        return $this;
    }

    public function removeBrand(Brand $brand): static
    {
        if ($this->brands->removeElement($brand)) {
            // set the owning side to null (unless already changed)
            if ($brand->getMusicGenre() === $this) {
                $brand->setMusicGenre(null);
            }
        }

        return $this;
    }
}
