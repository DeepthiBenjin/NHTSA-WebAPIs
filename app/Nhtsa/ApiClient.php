<?php

namespace App\Nhtsa;

use GuzzleHttp\Client;

/**
 * Trait ApiClient to sent request nhtsa.gov
 * @package App\Nhtsa
 */
trait ApiClient
{
    /**
     * @var string
     */
    protected $endPoint = 'https://one.nhtsa.gov/webapi/api/SafetyRatings/%s?format=json';

    /**
     * @param string $request
     * @return string
     * @throws \Exception
     */
    protected function send(string $request)
    {
        $client = new Client();
        $response = $client->request('GET', $request);

        $this->checkStatus($response);
        $this->checkBody($response);
        $this->checkContentType($response);

        return $response->getBody()->getContents();
    }

    /**
     * @param $response
     * @throws \Exception
     */
    protected function checkStatus($response)
    {
        if ($response->getStatusCode() != 200) {
            throw new \Exception("Bad Http status code");
        }
    }

    /**
     * @param $response
     * @throws \Exception
     */
    protected function checkBody($response)
    {
        if (empty($response->getBody())) {
            throw new \Exception("Empty response body from API");
        }
    }

    /**
     * @param $response
     * @throws \Exception
     */
    protected function checkContentType($response)
    {
        if (!stristr($response->getHeaderLine('content-type'), 'application/json')) {
            throw new \Exception("Bad content type");
        }
    }
}
