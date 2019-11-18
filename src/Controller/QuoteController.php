<?php

namespace App\Controller;

use App\Repositories\Interfaces\QuotesInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Quote controller.
 */
class QuoteController extends AbstractController
{
    /**
     * @var QuotesInterfrace
     */
    private $repository;

    /**
     * QuoteController constructor.
     *
     * @param QuotesInterface $repository
     * @param ValidatorInterface $validator
     */
    public function __construct(QuotesInterface $repository,
                                ValidatorInterface $validator)
    {
        parent::__construct($validator);
        $this->repository = $repository;
    }

    /**
     * @Rest\Get("/shout/{author}")
     *
     * @QueryParam(name="limit", description="How many quotes to send.")
     *
     * @param string $author
     * @param Request $request
     *
     * @return Response
     */
    public function getQuotes(string $author, Request $request): Response
    {
        $limit = $request->get('limit', 10);
        $constraint = new Assert\Collection([
            'limit'  => [
                new Assert\Range(['min' => 1, 'max' => 10]),
                new Assert\DivisibleBy(1),
            ],
            'author' => new Assert\Regex('/[a-z0-9-]+/'),
        ]);
        $this->validateRequestData(['limit' => $limit, 'author' => $author], $constraint);

        $quotes = $this->repository->getByAuthor($author, $limit);
        if (empty($quotes)) {
            $this->throwError(['author' => 'Not found'], 404);
        }

        return $this->handleView($this->view($quotes));
    }
}