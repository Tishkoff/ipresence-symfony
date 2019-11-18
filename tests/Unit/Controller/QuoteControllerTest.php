<?php

namespace App\Tests\Unit\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class QuoteControllerTest
 * @package App\Tests\Unit\Controller
 */
class QuoteControllerTest extends WebTestCase
{

    /**
     * @return array
     */
    public function provideSuccessfullUrls(): array
    {
        return [
            ['/shout/steve-jobs'],
            ['/shout/steve-jobs?limit=4'],
            ['/shout/steve-jobs?limit=9'],
            ['/shout/steve-jobs?limit=1'],
            ['/shout/steve-jobs?limit=1'],
            ['/shout/booker-t.-washington?limit=1'],
            ['/shout/booker-t.-washington?limit=5'],
            ['/shout/booker-t.-washington?limit=10'],
            ['/shout/booker-t.-washington'],
            ['/shout/dalai-lama?limit=1'],
            ['/shout/dalai-lama?limit=1'],
            ['/shout/Dalai-Lama?limit=1'],
            ['/shout/dalai-Lama?limit=5'],
            ['/shout/dalai-lama'],
        ];
    }

    /**
     * @return array
     */
    public function provideNoAuthorUrls(): array
    {
        return [
            ['/shout/steve'],
            ['/shout/alisa'],
            ['/shout/greg'],
            ['/shout/bob?limit=4'],
            ['/shout/uncle?limit=9'],
            ['/shout/foo?limit=1'],
            ['/shout/bar?limit=1'],
            ['/shout/nowhereman?limit=1'],
        ];
    }

    /**
     * @return array
     */
    public function provideLimitValidatorError(): array
    {
        return [
            ['/shout/steve-jobs?limit=11'],
            ['/shout/steve-jobs?limit=12'],
            ['/shout/steve-jobs?limit=22'],
            ['/shout/steve-jobs?limit=4234'],
            ['/shout/steve-jobs?limit=1.5'],
            ['/shout/dalai-lama?limit=1123123'],
            ['/shout/dalai-lama?limit=0'],
            ['/shout/dalai-lama?limit=-1'],
            ['/shout/dalai-lama?limit=foo'],
            ['/shout/dalai-lama?limit=asdasdasd'],
        ];
    }


    /**
     * @dataProvider provideSuccessfullUrls
     *
     * @param string $url
     */
    public function testGetQuotesSuccessfull(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);
        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
        $this->assertJson((string)$response->getContent());
    }

    /**
     * @dataProvider provideNoAuthorUrls
     *
     * @param string $url
     */
    public function testGetNoAuthor(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);
        $response = $client->getResponse();
        $this->assertTrue($response->isNotFound());
        $this->assertJson((string)$response->getContent());
        $this->assertJsonStringEqualsJsonString('{"errors":{"author":"Not found"}}', (string)$response->getContent());


    }

    /**
     * @dataProvider provideLimitValidatorError
     *
     * @param string $url
     */
    public function testFailLimitValidator(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $response = $client->getResponse();
        $this->assertSame($response->getStatusCode(), 422);
        $this->assertJson((string)$response->getContent());
    }


}
