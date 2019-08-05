<?php
namespace App\Service;

use App\Entity\Car;
use App\Entity\CarDay;
use App\Entity\CarPrice;
use App\Entity\Mileage;
use App\Entity\Rental;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class CarService
 * @package App\Service
 */
class CarService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * CarService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @return array
     */
    public function getCarDays()
    {
        $allCarDays = [];
        $carDays = $this->em->getRepository(CarDay::class)->findAll();
        foreach ($carDays as $carDay) {
            /** @var $carDay CarDay */
            $allCarDays[$carDay->getId()] = $carDay;
        }

        return $allCarDays;
    }

    /**
     * Create a new car
     *
     * @param Car   $carConvert
     * @param array $carDays
     * @param array $carPrices
     * @return Car
     */
    public function createNewCar(Car $carConvert, array $carDays, array $carPrices)
    {
        $mileage = $this->em->getRepository(Mileage::class)->find($carConvert->getMileage()->getId());
        $newCar = new Car();
        $newCar->setName($carConvert->getName());
        $newCar->setMileage($mileage);
        foreach ($carPrices as $carPrice) {
            $carPriceEntity = new CarPrice();
            $carPriceEntity->setCarDay($carDays[$carPrice['carDay']['id']]);
            $carPriceEntity->setCar($newCar);
            $carPriceEntity->setValue($carPrice['value']);
            $newCar->addCarPrice($carPriceEntity);
            $this->em->persist($carPriceEntity);
        }
        $this->em->persist($newCar);

        return $newCar;
    }

    /**
     * @param Car    $car
     * @param string $startDate
     * @param string $endDate
     * @return bool
     */
    public function checkAvailability(Car $car, string $startDate, string $endDate)
    {
        $rental = $this->em->getRepository(Rental::class)->findCarAvailability($car, $startDate, $endDate);

        return (count($rental)) ? false : true;
    }

    /**
     * @param Car       $car
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return Rental
     */
    public function bookCar(Car $car, \DateTime $startDate, \DateTime $endDate)
    {
        $carRental = new Rental();
        $carRental->setCar($car);
        $carRental->setStartDate($startDate);
        $carRental->setEndDate($endDate);

        // calculate the days
        $days = ceil(($endDate->getTimestamp() - $startDate->getTimestamp()) / 86400);
        $price = $this->calculatePriceByDays($car, $days);
        $carRental->setPrice($price);

        $this->em->persist($carRental);
        $this->em->flush();

        return $carRental;
    }

    /**
     * @param $car
     * @param $days
     * @return float|int
     */
    private function calculatePriceByDays($car, $days)
    {
        $price = 0;

        $carPriceDay1 = $this->em->getRepository(CarPrice::class)->findOneBy(['car' => $car, 'carDay' => CarDay::PRICE_DAY_1]);
        $carPriceDay3 = $this->em->getRepository(CarPrice::class)->findOneBy(['car' => $car, 'carDay' => CarDay::PRICE_DAY_3]);
        $carPriceDay7 = $this->em->getRepository(CarPrice::class)->findOneBy(['car' => $car, 'carDay' => CarDay::PRICE_DAY_7]);
        if ($days > 6) {
            $price += $carPriceDay1->getValue() * 2 + $carPriceDay3->getValue() * 4 + $carPriceDay7->getValue() * ($days - 6);
        } elseif ($days > 2) {
            $price += $carPriceDay1->getValue() * 2 + $carPriceDay3->getValue() * ($days - 2);
        } else {
            $price += $carPriceDay1->getValue() * $days;
        }

        return $price;
    }
}