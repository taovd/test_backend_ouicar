<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Mileage;
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
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * CarController
 * @Route("/car", name="car")
 */
class CarController extends AbstractFOSRestController
{
    /**
     * create a car
     *
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
     * get a car detail
     *
     * @SWG\Get(
     *     path="/car/{id}",
     *     summary="Get a cat detail",
     *     description="Get a car detail",
     *     operationId="getCar",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @SWG\Schema(ref=@Model(type=Car::class))
     *     )
     * )
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

    /**
     * update a car
     *
     * @ParamConverter("car", options={"id" = "id"})
     * @ParamConverter("carConvert", converter="fos_rest.request_body")
     *
     * @Rest\Put(
     *     name="update_car",
     *     path="/{id}/update"
     * )
     * @param Car                              $car
     * @param Car                              $carConvert
     * @param CarConstraintValidator           $carConstraintValidator
     * @param ConstraintViolationListInterface $validationErrors
     * @return View
     * @throws \Exception
     */
    public function updateAction(
        Car $car,
        Car $carConvert,
        CarConstraintValidator $carConstraintValidator,
        ConstraintViolationListInterface $validationErrors
    ) {
        // validate constraint violation
        $carConstraintValidator->validate($validationErrors);
        $em = $this->getDoctrine()->getManager();

        // TODO check if the mileage is mandatory in update
        $mileage = $em->getRepository(Mileage::class)->find($carConvert->getMileage()->getId());
        $car->setMileage($mileage);
        $car->setMileageExact($carConvert->getMileageExact());

        $em->flush();

        return View::create($car, Response::HTTP_OK);
    }
}
