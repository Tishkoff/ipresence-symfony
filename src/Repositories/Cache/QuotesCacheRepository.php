<?php

namespace App\Repositories\Cache;

use App\Repositories\Interfaces\QuotesInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

/**
 * Class QuotesCacheRepository
 * @package App\Repositories\Cache
 */
class QuotesCacheRepository implements QuotesInterface
{
    /**
     * @var QuotesInterface
     */
    protected $repository;
    /**
     * @var CacheItemPoolInterface
     */
    protected $cache;

    /**
     * QuotesCacheRepository constructor.
     *
     * @param QuotesInterface $repository
     * @param CacheItemPoolInterface $cache
     */
    public function __construct(QuotesInterface $repository, CacheItemPoolInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * @param string $author
     * @param int $limit
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public function getByAuthor(string $author, int $limit): array
    {
        $items = $this->cache->getItem('quotes_' . $author . '_' . $limit);

        if ( ! $items->isHit()) {
            $quotes = $this->repository->getByAuthor($author, $limit);

            $items->set($quotes);
            $this->cache->save($items);
        }

        return $items->get();
    }
}