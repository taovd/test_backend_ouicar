<?php
namespace App\Tests\Controller;

/**
 * Class ObjectData
 * @package App\Tests\Controller
 */
class ObjectData
{
    /**
     * @return string
     */
    public static function getNewCarJson()
    {
        return '
            {
                "name": "testNewCar",
                "mileage": {
                    "id": 2
                },
                "carPrices": [
                    {
                        "carDay": {
                            "id":1
                        },
                        "value": 55.5
                    },
                    {
                        "carDay": {
                            "id":2
                        },
                        "value": 52.5
                    },
                    {
                        "carDay": {
                            "id":3
                        },
                        "value": 49.5
                    }
                ]
            }          
        ';
    }

    /**
     * @return string
     */
    public static function getCarUpdateJson()
    {
        return '
            {
                "mileage": {
                    "id": 3
                },
                "mileageExact": 130000
            }
        ';
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function getCarBookingJson()
    {
        $now = new \DateTime();
        $endDate = clone $now;
        $start = $now->modify('+ 1 hour')->format('Y-m-d H:i:s');
        $end = $endDate->modify('+10 days')->format('Y-m-d H:i:s');

        return '
            {
                "startDate": "'.$start.'",
                "endDate": "'.$end.'"
            }
        ';
    }
}