<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CarRepository")
 */
class Car
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Mileage", inversedBy="cars", cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="mileage_id", referencedColumnName="id", nullable=false)
     */
    private $mileage;

    /**
     * @var Collection $components
     * @ORM\OneToMany(targetEntity="App\Entity\Price", mappedBy="car")
     * @ORM\JoinColumn(name="car_id", nullable=false)
     */
    private $prices;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->prices = new ArrayCollection();
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
     * @return Car
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Mileage|null
     */
    public function getMileage(): ?Mileage
    {
        return $this->mileage;
    }

    /**
     * @param Mileage|null $mileage
     * @return Car
     */
    public function setMileage(?Mileage $mileage): self
    {
        $this->mileage = $mileage;

        return $this;
    }

    /**
     * @return Collection|Price[]
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    /**
     * @param Price $price
     * @return Car
     */
    public function addPrice(Price $price): self
    {
        if (!$this->prices->contains($price)) {
            $this->prices[] = $price;
            $price->setCar($this);
        }

        return $this;
    }

    /**
     * @param Price $price
     * @return Car
     */
    public function removePrice(Price $price): self
    {
        if ($this->prices->contains($price)) {
            $this->prices->removeElement($price);
            // set the owning side to null (unless already changed)
            if ($price->getCar() === $this) {
                $price->setCar(null);
            }
        }

        return $this;
    }

}
