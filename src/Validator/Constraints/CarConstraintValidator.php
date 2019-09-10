<?php

namespace App\Validator\Constraints;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Class CarConstraintValidator
 * @package App\Validator\Constraints
 */
class CarConstraintValidator
{
    /**
     * @param array $violations
     * @throws \Exception
     */
    public function validate($violations)
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                /**
                 * @var ConstraintViolationInterface $violation
                 */
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new BadRequestHttpException($message);
        }
    }

    /**
     * @param array $carPrices
     * @param array $carDays
     */
    public function validateCarPrices(array $carPrices, array $carDays)
    {
        foreach ($carPrices as $carPrice) {
            if (isset($carDays[$carPrice['carDay']['id']])) {
                switch ($carPrice['carDay']['id']) {
                    case 1:
                        $priceDay1 = $carPrice['value'];
                        break;
                    case 2:
                        $priceDay3 = $carPrice['value'];
                        break;
                    case 3:
                        $priceDay7 = $carPrice['value'];
                        break;
                    default:
                        break;
                }
            } else {
                throw new BadRequestHttpException(sprintf("The price day of car not found (carDay id: %d)", $carPrice['carDay']['id']));
            }
        }

        if (empty($priceDay1) || empty($priceDay3) || empty($priceDay7)) {
            throw new BadRequestHttpException("We don't set the price for 1-2 days or 3-6 days or more than 7 days");
        }

        if ($priceDay3 >= $priceDay1) {
            throw new BadRequestHttpException("The price of 3-6 days can not be more than one of the 1-2 days");
        }

        if ($priceDay7 >= $priceDay3) {
            throw new BadRequestHttpException("The price of more than 7 days can not be more than one of the 3-6 days");
        }
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @throws \Exception
     */
    public function validateSearchDate(string $startDate, string $endDate)
    {
        if (empty($startDate) || empty($endDate)) {
            throw new BadRequestHttpException("The start date and the end date are mandatory");
        }
        $now = new \DateTime();
        if ($startDate >= $endDate) {
            throw new BadRequestHttpException("The start date must be less than the end date");
        }

        if ($startDate <= $now->format('Y-m-d H:i:s')) {
            throw new BadRequestHttpException("The start date must be more than the time now");
        }
    }
}