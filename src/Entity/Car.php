<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="car")
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
     * @Assert\NotBlank(message = "The mileage should not be blank")
     */
    private $mileage;

    /**
     * @var Collection $carPrices
     * @ORM\OneToMany(targetEntity="App\Entity\CarPrice", mappedBy="car", cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="car_id", nullable=true)
     * @Serializer\SerializedName("carPrices")
     */
    private $carPrices;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->carPrices = new ArrayCollection();
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
     * @return Collection|CarPrice[]
     */
    public function getCarPrices(): Collection
    {
        return $this->carPrices;
    }

    /**
     * @param CarPrice $carPrice
     * @return Car
     */
    public function addCarPrice(CarPrice $carPrice): self
    {
        if (!$this->carPrices->contains($carPrice)) {
            $this->carPrices[] = $carPrice;
            $carPrice->setCar($this);
        }

        return $this;
    }

    /**
     * @param CarPrice $carPrice
     * @return Car
     */
    public function removeCarPrice(CarPrice $carPrice): self
    {
        if ($this->carPrices->contains($carPrice)) {
            $this->carPrices->removeElement($carPrice);
            // set the owning side to null (unless already changed)
            if ($carPrice->getCar() === $this) {
                $carPrice->setCar(null);
            }
        }

        return $this;
    }
}
