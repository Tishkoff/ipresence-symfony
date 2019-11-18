<?php

namespace App\Controller;

use App\Exceptions\ApiException;
use App\Exceptions\ValidationException;
use FOS\RestBundle\Controller\AbstractFOSRestController as FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class AbstractController
 * @package App\Controller
 */
abstract class AbstractController extends FOSRestController
{
    /**
     * Content type for known errors
     */
    public const CONTENT_TYPE     = 'application/json';

    /**
     * @var array Request data
     */
    protected $data;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * AbstractController constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(
        ValidatorInterface $validator
    ) {
        $this->validator = $validator;
    }

    /**
     * Validates data based on constraints
     *
     * @param array             $data
     * @param Assert\Collection $constraints
     */
    protected function validateRequestData(array $data, Assert\Collection $constraints): void
    {
        $this->data = $data;
        $errors = $this->validator->validate($this->data, $constraints);
        if ($errors->count() > 0) {
            throw new ValidationException($this->createValidationErrorMessage($errors), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Creates error response based on validation
     *
     * @param ConstraintViolationListInterface $violations
     *
     * @return string
     */
    private function createValidationErrorMessage(ConstraintViolationListInterface $violations): string
    {
        $errors = [];

        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $field = substr($violation->getPropertyPath(), 1, -1);
            $errors[$field] = $violation->getMessage();
        }

        return json_encode(['errors' => $errors]);
    }

    /**
     * Returns api error
     *
     * @param array $errors
     * @param int   $statusCode
     */
    protected function throwError(array $errors, int $statusCode): void
    {
        throw new ApiException(json_encode(['errors' => $errors]), $statusCode);
    }
}