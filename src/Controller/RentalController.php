<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Rental;
use App\Service\CarService;
use App\Validator\Constraints\CarConstraintValidator;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class RentalController
 * @package App\Controller
 * @Route("/rental", name="rental")
 */
class RentalController extends AbstractFOSRestController
{
    /**
     * @QueryParam(
     *     name="startDate",
     *     description="start date",
     *     nullable=true
     * )
     *
     * @QueryParam(
     *     name="endDate",
     *     description="end date",
     *     nullable=true
     * )
     *
     * @Rest\Get(
     *     name="car_rental_available",
     *     path="/availability/car/{id}",
     *     requirements = {"id"="\d+"}
     * )
     *
     * @ParamConverter("car", options={"id" = "id"})
     *
     * @param Car                    $car
     * @param ParamFetcher           $paramFetcher
     * @param CarService             $carService
     * @param CarConstraintValidator $carConstraintValidator
     * @return View
     * @throws \Exception
     */
    public function checkAvailabilityAction(
        Car $car,
        ParamFetcher $paramFetcher,
        CarService $carService,
        CarConstraintValidator $carConstraintValidator
    ) {
        $startDate = $paramFetcher->get('startDate');
        $endDate   = $paramFetcher->get('endDate');

        //validate startDate and endDate
        $carConstraintValidator->validateSearchDate($startDate, $endDate);
        // check car rental availability
        $availability = $carService->checkAvailability($car, $startDate, $endDate);

        return View::create(['availability' => $availability], Response::HTTP_OK);
    }

   /**
    * @ParamConverter(
    *     "rentalConvert",
    *     converter="fos_rest.request_body"
    * )
    *
    * @Rest\Post(
    *     name="book_car",
    *     path="/car/{id}/book",
    *     requirements = {"id"="\d+"}
    * )
    *
    * @ParamConverter("car", options={"id" = "id"})
    * @ParamConverter("rentalConvert", converter="fos_rest.request_body")
    *
     * @param Car                              $car
     * @param Rental                           $rentalConvert
     * @param CarService                       $carService
     * @param CarConstraintValidator           $carConstraintValidator
     * @param ConstraintViolationListInterface $validationErrors
     * @return View
     * @throws \Exception
     */
    public function bookCar(
        Car $car,
        Rental $rentalConvert,
        CarService $carService,
        CarConstraintValidator $carConstraintValidator,
        ConstraintViolationListInterface $validationErrors
    ) {
        // validate constraint violation
        $carConstraintValidator->validate($validationErrors);

        // check car rental availability
        $startDate = $rentalConvert->getStartDate();
        $endDate = $rentalConvert->getEndDate();

        $availability = $carService->checkAvailability($car, $startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s'));
        if (!$availability) {
            throw new BadRequestHttpException(sprintf("The car is not available from %s to %s", $startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')));
        }

        $rental = $carService->bookCar($car, $startDate, $endDate);

        return View::create($rental, Response::HTTP_CREATED);
    }
}
