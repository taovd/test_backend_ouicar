<?php
namespace App\EventListener;

use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Class ExceptionListener
 * @package App\EventListener
 */
class ExceptionListener
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * ExceptionListener constructor.
     *
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer   = $serializer;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();
        $result['body'] = [
            'code'    => $exception->getCode(),
            'message' => $exception->getMessage(),
        ];
        $body = $this->serializer->serialize($result['body'], 'json');
        // Customize your response object to display the exception details
        $response = new Response();
        $response->setContent($body);
        $response->setStatusCode($exception->getCode());

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}