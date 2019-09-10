<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="mileage")
 * @ORM\Entity(repositoryClass="App\Repository\MileageRepository")
 */
class Mileage
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(name="min", type="integer", length=25)
     */
    public $min;

    /**
     * @ORM\Column(name="max", type="integer", length=25)
     */
    public $max;

    /**
     * @return int|null
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @param int $min
     * @return Mileage
     */
    public function setMin(int $min): self
    {
        $this->min = $min;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @param int|null $max
     * @return $this
     */
    public function setMax(int $max = null): self
    {
        $this->max = $max;

        return $this;
    }

}