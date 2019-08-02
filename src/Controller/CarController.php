<?php

namespace App\Controller;

use App\Entity\Car;
use App\Service\CarService;
use App\Validator\Constraints\CarConstraintValidator;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * CarController
 * @Route("/car", name="car")
 */
class CarController extends AbstractFOSRestController
{
    /**
     * @ParamConverter(
     *     "carConvert",
     *     converter="fos_rest.request_body"
     * )
     *
     * @Rest\Post(
     *     name="new_car",
     *     path="/new"
     * )
     * @param Car                              $carConvert
     * @param Request                          $request
     * @param CarService                       $carService
     * @param CarConstraintValidator           $carConstraintValidator
     * @param ConstraintViolationListInterface $validationErrors
     * @return View
     * @throws \Exception
     */
    public function newAction(
        Car $carConvert,
        Request $request,
        CarService $carService,
        CarConstraintValidator $carConstraintValidator,
        ConstraintViolationListInterface $validationErrors
    ) {
        // validate constraint violation
        $carConstraintValidator->validate($validationErrors);

        // validate car prices for days
        $carPrices = $request->get('carPrices');
        $carDays = $carService->getCarDays();
        $carConstraintValidator->validateCarPrices($carPrices, $carDays);

        // create a new car
        $newCar = $carService->createNewCar($carConvert, $carDays, $carPrices);

        $em = $this->getDoctrine()->getManager();

        $em->flush();

        return View::create($newCar, Response::HTTP_CREATED);
    }

    /**
     *
     * @Rest\Get(
     *     name="car_detail",
     *     path="/{id}",
     *     requirements = {"id"="\d+"}
     * )
     *
     * @ParamConverter("car", options={"id" = "id"})
     * @param Car $car
     * @return View
     */
    public function getAction(Car $car)
    {
        return View::create($car, Response::HTTP_OK);
    }
}
