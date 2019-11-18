<?php

namespace App\Repositories\Data;

use App\Repositories\Interfaces\QuotesInterface;

/**
 * Class QuotesDataRepository
 * @package App\Repositories\Data
 */
class QuotesDataRepository implements QuotesInterface
{
    /**
     * @var string path
     */
    public const PATH_TO_FILE = __DIR__ . '/../../../data/quotes.json';

    /**
     * @var array data
     */
    protected $data;

    /**
     * QuotesDataRepository constructor.
     */
    public function __construct()
    {
        $this->data = json_decode(file_get_contents(self::PATH_TO_FILE), true)['quotes'];
    }

    /**
     * @param string $author
     * @param int $limit
     *
     * @return array
     */
    public function getByAuthor(string $author, int $limit): array
    {
        $author = $this->prepareString($author);
        $quotes = [];
        foreach ($this->data as $item) {
            if (count($quotes) === $limit) {
                break;
            }
            if (strtolower($item['author']) === $author) {
                $quotes[] = $item['quote'];
            }
        }

        return $this->shoutQuotes($quotes);
    }

    /**
     * @param string $author
     *
     * @return string
     */
    private function prepareString(string $author): string
    {
        return strtolower(str_replace('-', ' ', $author));
    }

    /**
     * 'Shout' quotes.
     *
     * @param array $quotes
     * @return array
     */
    private function shoutQuotes(array $quotes): array
    {
        $onlyText = [];
        foreach ($quotes as $quote) {
            if (substr($quote, -1, 1) !== self::EXCLAMATION_CHARACTER) {
                $quote = substr($quote, 0, -1) . self::EXCLAMATION_CHARACTER;
            }
            $onlyText[] = mb_strtoupper($quote);
        }

        return $onlyText;
    }
}