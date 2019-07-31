<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Mileage")
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

}