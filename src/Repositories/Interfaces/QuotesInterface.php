<?php

namespace App\Repositories\Interfaces;

/**
 * Interface QuotesInterface
 * @package App\Repositories\Interfaces
 */
interface QuotesInterface
{
    /**
     * Exclamation Character
     */
    public const EXCLAMATION_CHARACTER = '!';

    /**
     * @param string $author
     * @param int $limit
     *
     * @return mixed
     */
    public function getByAuthor(string $author, int $limit);
}