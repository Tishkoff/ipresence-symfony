<?php
namespace App\Listeners;

use App\Controller\AbstractController;
use App\Exceptions\ApplicationException;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ExceptionEventListener
 * @package App\Listeners
 */
class ExceptionEventListener
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ExceptionEventListener constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        if ($event->getException() instanceof ApplicationException) {
            $response = $this->handleKnownExceptions($event->getException());
        } else {
            $response = $this->handleUnknownExceptions($event->getException());
        }
 
        $event->setResponse($response);
    }

    /**
     * @param Exception $exception
     *
     * @return Response
     */
    private function handleKnownExceptions(Exception $exception): Response
    {
        $header = [];
        if (Response::HTTP_BAD_REQUEST === $exception->getStatusCode()) {
            $header = ['Content-Type' => AbstractController::CONTENT_TYPE];
        } else {
            $this->logger->error($exception);
        }
 
        return new Response($exception->getMessage(), $exception->getStatusCode(), $header);
    }

    /**
     * @param Exception $exception
     *
     * @return Response
     */
    private function handleUnknownExceptions(Exception $exception): Response
    {
        $this->logger->error($exception);
        if ($exception instanceof HttpException) {
            return new Response(json_encode(['errors' => ['error' => $exception->getMessage()]]), $exception->getStatusCode());
        }

        return new Response(json_encode(['errors' => ['error' => 'An unknown exception occurred!']]), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
